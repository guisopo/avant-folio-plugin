<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\CustomField;
use Includes\Api\ImageGallery;

class ExhibitionsCpt {

  public $cpt = array();

  public function register() 
  {
    $this->cpt_name = 'Exhibitions';
    $this->gallery_title = 'Exhibitions Gallery';
    $this->custom_field_id = 'exhibition-information';
    $this->custom_field_title = 'Exhibition Details';
    $this->meta_key = 'avant_folio_exhibition_info';
    $this->gallery_meta_key = 'avant_folio_gallery';

    $this->store_cpt_data();

    isset( $this->cpt['arguments'] ) ? $this->create_cpt() : '';
  }

  public function store_cpt_data() 
  {
    $this
      ->add_cpt_arguments()
      ->add_cpt_custom_fields()
      ->add_cpt_image_gallery();
  }


  public function add_cpt_arguments() 
  {
    $this->cpt['arguments'] = array(
      'cpt_name'       => $this->cpt_name,
      'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'       => 'dashicons-awards'
    );

    return $this;
  }

  public function create_cpt() {
    $custom_post_type = new CustomPostType();

    $custom_post_type
      ->store_cpt( $this->cpt['arguments'] )
      ->register();

    isset( $this->cpt['custom_fields'] ) ? $this->create_custom_fields() : '';
    isset( $this->cpt['image_gallery'] ) ? $this->create_image_gallery() : '';
  }

  public function add_cpt_custom_fields() 
  {
    $this->cpt['custom_fields'] = array(
      'id'       => $this->custom_field_id,
      'title'    =>	esc_html__( $this->custom_field_title, 'string' ),
      'screen'   => $this->cpt_name,
      'meta-key' => $this->meta_key
    );

    return $this;
  }

  public function create_custom_fields() 
  {
    $custom_fields = new CustomField();

		$custom_fields
			->set_metabox( $this->cpt['custom_fields'] )
			->register();
  }

  public function add_cpt_image_gallery() 
  {
    $this->cpt['image_gallery'] = array(
      array (
        'id'       => $this->gallery_title,
        'title'    =>	esc_html__( $this->gallery_title, 'string' ),
        'screen'   => $this->cpt_name,
        'context'	 => 'advanced',
        'priority' => 'high',
        'meta-key' => $this->gallery_meta_key
      )
    );

    return $this;
  }

  public function create_image_gallery() 
  {
    foreach ($this->cpt['image_gallery'] as $gallery_arguments) {
      $gallery = new ImageGallery();
      
      $gallery
        ->set_gallery( $gallery_arguments )
        ->register();
    }
  }
}