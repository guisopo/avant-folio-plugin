<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use \Includes\Base\BaseController;

class Enqueue extends BaseController
{
  public function register() {
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue') );
  }

  function enqueue() {
    wp_enqueue_style( 'avant-folio-admin', $this->plugin_url . 'assets/avant-folio-admin.css' );
  }
}