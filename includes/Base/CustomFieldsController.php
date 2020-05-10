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

}