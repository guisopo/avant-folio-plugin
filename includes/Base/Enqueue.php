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

  function enqueue() {
    wp_enqueue_style( 'avant-folio-admin', $this->plugin_url . 'assets/avant-folio-admin.css' );
    wp_enqueue_script( 'avant-folio-script', $this->plugin_url . 'assets/avant-folio-inputs.js' );
    wp_enqueue_media();
    wp_enqueue_script( 'avant-folio-gallery', $this->plugin_url . 'assets/avant-folio-gallery.js' );
  }
}