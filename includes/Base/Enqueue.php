<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use \Includes\Api\BaseController;

class Enqueue extends BaseController
{
  public function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue') );
  }

  function enqueue($hook_suffix) {
    wp_enqueue_style( 'avant-folio-admin', $this->plugin_url . 'assets/avant-folio-admin.css' );
    wp_enqueue_media();
    wp_enqueue_script( 'avant-folio-gallery', $this->plugin_url . 'assets/avant-folio-gallery.js' );

    $cpt = 'works';

    if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
      $screen = get_current_screen();
      
      if( is_object( $screen ) && $cpt == $screen->post_type ){
        // Register, enqueue scripts and styles here
        wp_enqueue_script( 'avant-folio-script-inputs', $this->plugin_url . 'assets/avant-folio-inputs.js' );
      }
    }
  }
}