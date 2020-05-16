<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Base\BaseController;

class CustomFieldsController extends BaseController
{
  
  public $metabox = array();
  public $metabox_nonce;

  public function register() 
  {
    if( ! empty($this->metabox) ) {
      add_action( 'add_meta_boxes', array( $this, 'createMetaBoxes' ) );
      add_action( 'save_post', array( $this, 'saveMetaboxesData' ), 12 );
    }
  }

  public function setMetabox( array $metabox ) 
  {
    $this->metabox       = $metabox;
    $this->metabox_nonce = $this->metabox['meta-key'] . '_nonce';
    return $this;
  }
  
  public function createMetaBoxes() 
  {
    add_meta_box(
      $this->metabox['id'],
      $this->metabox['title'],
      array($this, 'renderCustomField'),
      $this->metabox['screen'],  
      isset( $this->metabox['context'] ) ?: 'normal', 
      isset( $this->metabox['priority'] ) ?: 'core'   
    );
  }

  public function renderCustomField( $post ) 
  {
    wp_nonce_field( $this->plugin_path, $this->metabox_nonce );
    require_once( $this->plugin_path . 'templates/' . $this->metabox['id'] . '.php' );
  }

  public function saveMetaboxesData( $post_id ) 
  {
    //  Check Post Status
    if (  ! $this->checkPostStatus( $post_id ) ) return; 

    //  Check if Nonce is Set
    if (  ! isset(  $_POST[$this->metabox_nonce] ) ) return;
    
    //  Validate Nonce
    if (  ! wp_verify_nonce( $_POST[$this->metabox_nonce],  $this->plugin_path ) ) return;

    //  Check if Key is Set
    if (  ! isset( $_POST[$this->metabox['meta-key']] ) ) return;

    //  Sanitize Form Values and Save to new Variable
    $new_meta_value = $this->sanitizeFields( $_POST[ $this->metabox['meta-key'] ] );

    //  Get the Meta Key.
    $meta_key =  '_' . $this->metabox['meta-key'] . '_key';

    //  Get the Key Values from DB
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    //  Update Data
    $this->saveData( $post_id, $meta_key, $meta_value, $new_meta_value );
  }

  public function checkPostStatus( string $post_id ) 
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

  public function sanitizeFields( array $input ) 
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

  public function saveData( string $post_id, string $meta_key, $meta_value, array $new_meta_value ) {
    //  If 'gallery' key exist save it to variable, if not delete post thumbnail
    $gallery = $new_meta_value['gallery'] ?? delete_post_thumbnail( $post_id );

    if ( count($new_meta_value) === 0 ) {
      delete_post_meta( $post_id, $meta_key, $meta_value );
      return;
    }
    
    //  Set Taxonomies
    isset( $new_meta_value['work_type'] ) 
      ? wp_set_post_terms( $post_id, $new_meta_value['work_type'], 'work_type')
      : '';
      // : wp_remove_object_terms( $post_id, $meta_value['work_type'], 'work_type', true);

    isset( $new_meta_value['date_completed'] ) 
      ? wp_set_post_terms( $post_id, $new_meta_value['date_completed'], 'date_completed' )
      : '';
      // : wp_remove_object_terms( $post_id, $meta_value['date_completed' ?? ''], 'date_completed', true);

    //  Save Meta Data
    update_post_meta( $post_id, $meta_key, $new_meta_value );

    //  Set the Post Featured Image
    $this->setFeaturedImage( $post_id, $new_meta_value['featured_image'] ?? '', $gallery);

    //  Set the Post Format
    $this->setPostFormat( $post_id, $work_type ?? '', $gallery );

  }

  protected function setFeaturedImage( string $post_id, string $featured_image, string $gallery ) 
  {

    $images = explode(",", $gallery );
    $images_count = count($images);


    if( $featured_image && in_array( $featured_image, $images, true) ) 
    {
      set_post_thumbnail( $post_id,  $featured_image );
    }
    else if( $images ) {
      set_post_thumbnail( $post_id,  $images[0] );
    }
  }

  protected function setPostFormat( string $post_id, string $work_type, string $gallery ) 
  {
    $images = explode(",", $gallery );

    if ( $work_type != 'Video' && count($images) == 1  ) {
      set_post_format($post_id, 'image');
    } 
    else if ( $work_type != 'Video' && count($images) > 1 ) {
      set_post_format($post_id, 'gallery');
    } 
    else if ( $work_type == 'Video' ) {
      set_post_format($post_id, 'video');
    }  
    else {
      set_post_format($post_id, 'standard');
    }
  }
}