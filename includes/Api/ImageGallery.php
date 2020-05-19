<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api;

use Includes\Api\BaseController;

class ImageGallery extends BaseController
{
  
  public $name;
  public $version;

  public $gallery;

  public function register() 
  {
    if( ! empty($this->gallery) ) {
      add_action( 'add_meta_boxes', array( $this, 'add_gallery' ) );
      add_action( 'save_post', array( $this, 'save_gallery_data' ), 12 );

    }
  }

  public function set_gallery( array $gallery ) 
  {
    // if( $gallery['title'] === '' ) {
    //   $this->gallery['title'] = ucfirst($gallery['id']);
    // }

    // $available_context = array(
    //   'advanced',
    //   'side',
    //   'normal'
    // );

    // if( in_array( $gallery['context'], $available_context ) ) {
    //   $this->gallery['context'] = $gallery['context'];
    // }

    // $available_priority = array(
    //   'default',
    //   'high',
    //   'normal'
    // );

    // if( in_array( $gallery['priority'], $available_priority ) ) {
    //   $this->gallery['priority'] = $gallery['priority'];
    // }
    $this->gallery = $gallery;
    $this->gallery_nonce = $this->gallery['meta-key'] . '_nonce';
    return $this;
  }

  public function add_gallery( string $gallery ) 
  {
    add_meta_box(
      $this->gallery['id'],
      $this->gallery['title'],
      array( $this, 'render_gallery' ),
      $this->gallery['screen'],
      $this->gallery['context'],
      $this->gallery['priority']
    );
  }

  public function render_gallery( $post ) 
  {
    wp_nonce_field( $this->plugin_path, $this->gallery_nonce );
    require_once( $this->plugin_path . 'templates/avant-folio-gallery.php' );
  }


  public function save_gallery_data( $post_id ) 
  {
    //  Check Post Status
    if (  ! $this->check_post_status( $post_id ) ) return; 

    //  Check if Nonce is Set
    if (  ! isset(  $_POST[$this->gallery_nonce] ) ) return;
    
    //  Validate Nonce
    if (  ! wp_verify_nonce( $_POST[$this->gallery_nonce],  $this->plugin_path ) ) return;

    //  Check if Key is Set
    if (  ! isset( $_POST[$this->gallery['meta-key']] ) ) return;

    //  Sanitize Form Values and Save to new Variable
    $new_meta_value = $this->sanitize_fields( $_POST[ $this->gallery['meta-key'] ] );

    //  Get the Meta Key.
    $meta_key =  '_' . $this->gallery['meta-key'] . '_key';

    //  Get the Key Values from DB
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    //  Update Data
    $this->save_data( $post_id, $meta_key, $meta_value, $new_meta_value );
  }

  public function check_post_status( string $post_id ) 
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

    return $input;
  }

  public function save_data( string $post_id, string $meta_key, $meta_value, array $new_meta_value ) 
  {
    $gallery = $new_meta_value['gallery'] ?? delete_post_thumbnail( $post_id );

    if ( count($new_meta_value) === 0 ) {
      delete_post_meta( $post_id, $meta_key, $meta_value );
      return;
    }
    
    //  Save Meta Data
    update_post_meta( $post_id, $meta_key, $new_meta_value );

    //  Set the Post Featured Image
    $this->set_featured_image( $post_id, $new_meta_value['featured_image'] ?? '', $gallery);
  }

  protected function set_featured_image( string $post_id, string $featured_image, string $gallery ) 
  {

    $images = explode(",", $gallery );

    if( $featured_image && in_array( $featured_image, $images, true) ) 
    {
      set_post_thumbnail( $post_id,  $featured_image );
    }
    else if( $images ) {
      set_post_thumbnail( $post_id,  $images[0] );
    }
  }
}