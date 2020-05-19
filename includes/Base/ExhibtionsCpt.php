<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\BaseController;

class ExhibitionsCpt extends BaseController {

  public $cptData = array();

  public function register() 
  {
    $this->addCptData();
    $this->createCpt();
  }

  public function addCptData() 
  {
    $this->cptData = array(
      'cpt_name'       => 'Exhibitions',
      'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'       => 'dashicons-awards'
    );
  }

  public function createCpt() {
    $custom_post_type = new CustomPostType();

    $custom_post_type
      ->storeCpt( $this->cptData )
      ->register();
  }
}