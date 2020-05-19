<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\CustomField;
use Includes\Api\ImageGallery;
use Includes\Api\CustomTaxonomy;

class WorkCpt 
{
  public $cpt = array();
  public $cpt_name;
  public $custom_field_id;
  public $custom_field_title;
  public $gallery_title;
  public $meta_key;

  public function register() 
  {
    $this->cpt_name = 'Works';
    $this->gallery_title = 'Work Gallery';
    $this->custom_field_id = 'work-information';
    $this->custom_field_title = 'Work Details';
    $this->meta_key = 'avant_folio_work_info';
    $this->gallery_meta_key = 'avant_folio_gallery';

    $this->store_cpt_data();

    isset( $this->cpt['arguments'] ) ? $this->create_cpt() : '';
  }

  public function store_cpt_data() 
  {
    $this
      ->add_cpt_arguments()
      ->add_cpt_custom_fields()
      ->add_cpt_image_gallery()
      ->add_cpt_taxonomies();
  }

  public function add_cpt_arguments() 
  {
    $this->cpt['arguments'] = array(
      'cpt_name'      => $this->cpt_name,
      'cpt_supports'  => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'      => 'dashicons-visibility',
    );

    return $this;
  }

  public function create_cpt() 
  {
    $custom_post_type = new CustomPostType();

    $custom_post_type
      ->store_cpt( $this->cpt['arguments'] )
      ->register();

    isset( $this->cpt['custom_fields'] ) ? $this->create_custom_fields() : '';
    isset( $this->cpt['image_gallery'] ) ? $this->create_image_gallery() : '';
    isset( $this->cpt['taxonomies'] ) ? $this->create_taxonomies() : '';
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

  public function add_cpt_taxonomies() 
  {
    $this->cpt['taxonomies'] = array(
      array(
        'cpt'           => strtolower( $this->cpt_name ),
        'id'            => 'work_type',
        'plural_name'   => 'Types of Work',
        'singular_name' => 'Type of Work',
        'terms'         => [ 'painting', 'drawing', 'sculpture', 'ceramic', 'photography', 'collage', 'video', 'performance', 'installation', '3D Art']
      ),
      array(
        'cpt'           => strtolower( $this->cpt_name ),
        'id'            => 'date_completed',
        'plural_name'   => 'Dates',
        'singular_name' => 'Date',
        'show_ui'       => false
      )
    );
  }

  public function create_taxonomies() 
  {
    $taxonomies = new CustomTaxonomy();
    
    $taxonomies
      ->add_taxonomies( $this->cpt['taxonomies'] )
      ->register();
  }
}