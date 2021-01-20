<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\CustomPostType;
use Includes\Api\CustomField;
use Includes\Api\ImageGallery;
use Includes\Api\CustomTaxonomy;
use Includes\Api\CustomColumns;

class WorkCpt 
{
  public $cpt = array();
  public $cpt_name;

  public $custom_field_id;
  public $custom_field_title;

  public $gallery_title;
  public $meta_key;

  public $works_cpt_columns;
  public $works_cpt_custom_columns;

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
      ->add_cpt_taxonomies()
      ->add_cpt_custom_columns();
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
    isset( $this->cpt['columns'] ) ? $this->create_columns() : '';
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

        'id'       => $this->gallery_title,
        'title'    =>	esc_html__( $this->gallery_title, 'string' ),
        'screen'   => $this->cpt_name,
        'context'	 => 'advanced',
        'priority' => 'high',
        'meta-key' => $this->gallery_meta_key

    );

    return $this;
  }

  public function create_image_gallery() 
  {
      $gallery = new ImageGallery();
      
      $gallery
        ->set_gallery( $this->cpt['image_gallery'] )
        ->register();
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

    return $this;
  }

  public function create_taxonomies() 
  {
    $taxonomies = new CustomTaxonomy();
    
    $taxonomies
      ->add_taxonomies( $this->cpt['taxonomies'] )
      ->register();
  }

  public function add_cpt_custom_columns()
  {
    $this->cpt['columns'] = array(
      'cb'              =>  'cb',
      'image'           =>  __('Image'),
      'title'           =>  __('Title'),
      'work_type'       =>  __('Work Type', 'avant-folio'),
      'date_completed'  =>  __('Created', 'avant-folio'),
      'date'            =>  __('Published'),
    );

    $this->cpt['custom_columns'] = array(
      'work_type' => array(
        'sort_id' => 'work_type'
      ),
      'date_completed' => array(
        'sort_id' => 'date_completed'
      )
    );

    $this->cpt['custom_columns_filters'] = array(
      array(
        'show_option_all'   => 'All Work Type',
        'orderby'           => 'NAME',
        'order'             => 'ASC',
        'name'              => 'avant_folio_work_type_filter',
        'taxonomy'          => 'work_type'
      ),
      array(
        'show_option_all'   => 'All Years',
        'orderby'           => 'NAME',
        'order'             => 'ASC',
        'name'              => 'avant_folio_date_completed_filter',
        'taxonomy'          => 'date_completed'
      )
    );

    return $this;
  }

  public function create_columns()
  {
    $custom_columns = new CustomColumns();

    $custom_columns
      ->add_columns( strtolower( $this->cpt_name ), $this->cpt['columns'], $this->cpt['custom_columns'], $this->cpt['custom_columns_filters'] )
      ->add_meta_key( $this->meta_key )
      ->register();
  }
}