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
      var_dump(isset( $_POST[ $this->metabox['meta-key'] ] ));
      add_action( 'add_meta_boxes', array( $this, 'createMetaBoxes' ) );
      add_action( 'save_post', array( $this, 'saveMetaboxesData', 10, 1 ) );
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

  public function saveMetaboxesData( $post_id ) {
    // Checks save status
    $is_autosave    = wp_is_post_autosave( $post_id );
    $is_revision    = wp_is_post_revision( $post_id );
    $is_valid_nonce = $this->validateNonce( $_POST[$this->metabox_nonce] );
    $user_can_edit  = current_user_can( 'edit_post', $post_id );
  
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce || !$user_can_edit ) {
      return;
    }
    
    // Get the posted data and sanitize it
    if ( isset( $_POST[ $this->metabox['meta-key'] ] ) ) {
      $new_meta_value = $this->sanitize_fields( $_POST[ $this->metabox['meta-key'] ] );
    } else {
      return;
    }
    
    $new_meta_value['title'] = sanitize_text_field( $_POST[ 'post_title' ] );
    $new_meta_value = array_filter($new_meta_value);

    // Get the meta key.
    $meta_key =  '_' . $this->metabox['meta-key'] . '_key';

    // Set keys to work type and date completed.
    $work_type_meta_key      = '_avant_folio_work_type_key';
    $date_completed_meta_key = '_avant_folio_date_completed_key';
  
    // Get the meta value of the custom field key.
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    // If the new meta value does not match the old value, update it.
    if ( $new_meta_value != $meta_value ) {
      
      $work_type      = $new_meta_value['work_type'] ?? '';
      $date_completed = $new_meta_value['date_completed'] ?? '';
      // $gallery        = $new_meta_value['gallery'] ?? delete_post_thumbnail( $post_id );
      
      wp_set_post_terms( $post_id, $work_type, 'work_type' );
      wp_set_post_terms( $post_id, $date_completed, 'date_completed' );

      update_post_meta( $post_id, $work_type_meta_key, $work_type );
      update_post_meta( $post_id, $date_completed_meta_key, $date_completed );

      update_post_meta( $post_id, $meta_key, $new_meta_value );
      
      // $this->setPostFormat( $post_id, $work_type, $gallery );

      // $this->set_featured_image( $post_id, $new_meta_value['featured_image'], $gallery);
    }
    
    // If there is no new meta value but an old value exists, delete it.
    elseif ( $new_meta_value === '' && $meta_value )
      delete_post_meta( $post_id, $meta_key, $meta_value );
  }

  public function validateNonce( $nonce ) {
    $nonce_is_set = isset(  $nonce );
    $nonce_is_verified = wp_verify_nonce( $nonce, basename( __FILE__ ) );

    if ( $nonce_is_set  && $nonce_is_verified  ) {
      return true;
    } else {
      return false;
    }
  }

  public function sanitizeFields( $input ) {
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
}