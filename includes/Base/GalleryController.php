<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Base\BaseController;

class GalleryController extends BaseController
{
  
  public $name;
  public $version;

  public $gallery;

  public function register() 
  {
    if( ! empty($this->gallery) ) {
      add_action( 'add_meta_boxes', array( $this, 'addGallery' ) );
    }
  }

  public function setGallery( array $gallery ) 
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

  public function addGallery( string $gallery ) 
  {
    add_meta_box(
      $this->gallery['id'],
      $this->gallery['title'],
      array( $this, 'renderGallery' ),
      $this->gallery['screen'],
      $this->gallery['context'],
      $this->gallery['priority']
    );
  }

  public function renderGallery( $post ) 
  {
    require_once( $this->plugin_path . 'templates/avant-folio-gallery.php' );
  }
}