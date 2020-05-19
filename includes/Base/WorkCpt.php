<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\CustomField;
use Includes\Api\ImageGallery;
use Includes\Api\BaseController;

class WorkCpt extends BaseController 
{
  public $cpt = array();
  public $cpt_name;
  public $custom_field_id;
  public $custom_field_title;
  public $gallery_title;

  public function register() 
  {
    $this->cpt_name = 'Works';
    $this->custom_field_id = 'work-information';
    $this->custom_field_title = 'Work Details';
    $this->gallery_title = 'Work Gallery';

    $this->store_cpt_data();
    $this->create_cpt();
    $this->create_custom_fields();
    $this->create_image_gallery();
  }

  public function store_cpt_data() 
  {
    $this->add_cpt_arguments()
         ->add_cpt_custom_fields()
         ->add_cpt_image_gallery();
  }

  public function add_cpt_arguments() 
  {
    $this->cpt['cpt_arguments'] = array(
      'cpt_name'      => $this->cpt_name,
      'cpt_supports'  => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'      => 'dashicons-visibility',
    );

    return $this;
  }

  public function add_cpt_custom_fields() {
    $this->cpt['cpt_custom_fields'] = array(
      'id'       => $this->custom_field_id,
      'title'    =>	esc_html__( $this->custom_field_title, 'string' ),
      'screen'   => $this->cpt_name,
      'meta-key' => 'avant_folio_work_info',
    );

    return $this;
  }

  public function add_cpt_image_gallery() {
    $this->cpt['cpt_image_gallery'] = array(
      'id'       => $this->gallery_title,
      'title'    =>	esc_html__( $this->gallery_title, 'string' ),
      'screen'   => $this->cpt_name,
      'context'	 => 'advanced',
      'priority' => 'high'
    );

    return $this;
  }

  public function create_cpt() {
    $custom_post_type = new CustomPostType();

    $custom_post_type
      ->storeCpt( $this->cpt['cpt_arguments'] )
      ->register();
  }

  public function create_custom_fields() {
    $custom_fields = new CustomField();
    
		$custom_fields
			->setMetabox( $this->cpt['cpt_custom_fields'] )
			->register();
  }

  public function create_image_gallery() {
    $gallery = new ImageGallery();
		
		$gallery
			->setGallery( $this->cpt['cpt_image_gallery'] )
			->register();
  }
}