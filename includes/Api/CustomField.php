<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api;

use Includes\Api\BaseController;

class CustomField extends BaseController
{
  
  public $metabox = array();
  public $metabox_nonce;

  public function register() 
  {
    if( ! empty($this->metabox) ) {
      add_action( 'add_meta_boxes', array( $this, 'create_metabox' ) );
      add_action( 'save_post', array( $this, 'save_metaboxes_data' ), 12 );
    }
  }

  public function set_metabox( array $metabox ) 
  {
    $this->metabox       = $metabox;
    $this->metabox_nonce = $this->metabox['meta-key'] . '_nonce';
    return $this;
  }
  
  public function create_metabox() 
  {
    add_meta_box(
      $this->metabox['id'],
      $this->metabox['title'],
      array($this, 'render_custom_field'),
      $this->metabox['screen'],  
      isset( $this->metabox['context'] ) ?: 'normal', 
      isset( $this->metabox['priority'] ) ?: 'core'   
    );
  }

  public function render_custom_field( $post ) 
  {
    // Add an nonce field so we can check for it later
    wp_nonce_field( $this->plugin_path, $this->metabox_nonce );
    // Require template
    require_once( $this->plugin_path . 'templates/' . $this->metabox['id'] . '.php' );
  }

  public function save_metaboxes_data( $post_id ) 
  {
    //  Skip saving if user cannot save
    if (  ! $this->user_can_save( $post_id ) ) return; 

    //  Skip saving if nonce is not set
    if (  ! isset(  $_POST[$this->metabox_nonce] ) ) return;
    
    //  Skip saving if invalidated nonce
    if (  ! wp_verify_nonce( $_POST[$this->metabox_nonce],  $this->plugin_path ) ) return;

    //  Skip saving if key is not set
    if (  ! isset( $_POST[$this->metabox['meta-key']] ) ) return;

    //  Sanitize Form Values and Save to new Variable
    $new_meta_value = $this->sanitize_fields( $_POST[ $this->metabox['meta-key'] ] );

    //  Get the Meta Key.
    $meta_key =  '_' . $this->metabox['meta-key'] . '_key';

    //  Get the Key Values from DB
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    //  Update Data
    $this->save_data( $post_id, $meta_key, $meta_value, $new_meta_value );
  }

  public function user_can_save( string $post_id ) 
  {
    $is_autosave    = wp_is_post_autosave( $post_id );
    $is_revision    = wp_is_post_revision( $post_id );
    $user_can_edit  = current_user_can( 'edit_post', $post_id );

    //  Exit script
    if ( $is_autosave || $is_revision || ! $user_can_edit ) {
      return false;
    } else {
      return true;
    }
  }

  public function sanitize_fields( array $input ) 
  {
    //  Delete empty array keys
    $input = array_filter($input);
    //  Apply sanitization
    foreach ($input as $key => $value) {
      if ( $key == 'credits' || $key == 'description' ) {
        $input[$key] = sanitize_textarea_field( $value );
      }

      else {
        $input[$key] = sanitize_text_field( $value );
      }
    }

    return $input;
  }

  public function save_data( string $post_id, string $meta_key, $meta_value, array $new_meta_value ) 
  {
    if ( count($new_meta_value) === 0 ) {
      delete_post_meta( $post_id, $meta_key, $meta_value );
      return;
    }
    
    //  Set Taxonomies
    if ( isset( $new_meta_value['work_type'] ) ) {
      wp_set_post_terms( $post_id, $new_meta_value['work_type'], 'work_type');
      update_post_meta( $post_id, '_avant_folio_work_type_key', $new_meta_value['work_type'] );

    }

    if ( isset( $new_meta_value['date_completed'] ) ) {
      wp_set_post_terms( $post_id, $new_meta_value['date_completed'], 'date_completed' );
      update_post_meta( $post_id, '_avant_folio_date_completed_key', $new_meta_value['date_completed'] );

    }

    //  Save Meta Data
    update_post_meta( $post_id, $meta_key, $new_meta_value );
  }

  protected function set_post_format( string $post_id, string $work_type, string $gallery ) 
  {
    $images_count = $gallery ? count( explode(",", $gallery ) ) : null;

    if ( $work_type === 'Video' ) {
      set_post_format($post_id, 'video');
    }
    else if ( $work_type != 'Video' && $images_count === 1  ) {
      set_post_format($post_id, 'image');
    } 
    else if ( $work_type != 'Video' && $images_count > 1 ) {
      set_post_format($post_id, 'gallery');
    }  
    else {
      set_post_format($post_id, 'standard');
    }
  }
}