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

class ExhibitionsCpt {

  public $cpt = array();
  public $cpt_name;

  public $custom_field_id;
  public $custom_field_title;

  public $gallery_title;
  public $meta_key;

  public $exhibitions_cpt_columns;
  public $exhibitions_cpt_custom_columns;

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
      ->add_cpt_image_gallery()
      ->add_cpt_taxonomies()
      ->add_cpt_custom_columns();
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
        'id'            => 'exhibition_year',
        'plural_name'   => 'Years',
        'singular_name' => 'year'
        // 'show_ui'       => false
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
      'cb'              =>  '<input type="checkbox" />',
      'image'           =>  __('Image'),
      'title'           =>  __('Title'),
      'year'            =>  __('Date', 'avant-folio'),
      'date'            =>  __('Published'),
    );

    $this->cpt['custom_columns'] = array(
      'year' => array(
        'sort_id' => 'year'
      )
    );

    $this->cpt['custom_columns_filters'] = array(
      array(
        'show_option_all'   => 'All Years',
        'orderby'           => 'NAME',
        'order'             => 'ASC',
        'name'              => 'avant_folio_year_filter',
        'taxonomy'          => 'exhibition_year'
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