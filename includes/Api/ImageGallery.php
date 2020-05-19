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
    require_once( $this->plugin_path . 'templates/avant-folio-gallery.php' );
  }
}