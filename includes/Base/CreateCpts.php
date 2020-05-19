<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\BaseController;

class CreateCpts extends BaseController {

  public $cptData = array();

  public function register() 
  {
    $this->addCptData();
    $this->createCpt();
  }

  public function addCptData() 
  {
    $this->cptData = array(
      array(
        'cpt_name'     			=> 'Works',
        'cpt_supports' 			=> array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
        'cpt_icon'     			=> 'dashicons-visibility',
        'cpt_custom_fields' => array(
          'id'       => 'work-information',
          'title'    =>	esc_html__( 'Work Details', 'string' ),
          'screen'   => 'Works',
          'meta-key' => 'avant_folio_work_info',
        ),
        'cpt_gallery'				=> array(
          'id'       => 'Work Gallery',
          'title'    =>	esc_html__( 'Work Gallery', 'string' ),
          'screen'   => 'Works',
          'context'	 => 'advanced',
          'priority'  => 'high'
        )
      ),
      array(
        'cpt_name'       => 'Exhibitions',
        'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
        'cpt_icon'       => 'dashicons-awards'
      )
    );
  }

  public function createCpt() {
    $custom_post_type = new CustomPostType();

    $custom_post_type
      ->storeCpt( $this->cptData )
      ->register();
  }
}