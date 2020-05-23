<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api;

class CustomColumns 
{
  public $cpt_name;
  public $cpt_columns;
  public $cpt_custom_columns;
  
  public function register(  ) 
  {
    if ( ! empty( $this->cpt_columns ) ) {
      add_filter( 'manage_' . $this->cpt_name . '_posts_columns', array( $this, 'add_custom_columns' ) );
      add_action( 'manage_' . $this->cpt_name . '_posts_custom_column', array( $this, 'manage_custom_columns' ), 10, 2 );
      add_filter( 'manage_edit-' . $this->cpt_name . '_sortable_columns', array( $this, 'set_sortable_columns' ) );
      add_action( 'pre_get_posts', array( $this, 'set_posts_orderby' ) );
      add_action('restrict_manage_posts',array( $this, 'add_custom_filter' ) );

    }
  }

  public function add_columns( $cpt_name, $cpt_columns, $cpt_custom_columns, $cpt_custom_columns_filters ) 
  {  
    $this->cpt_name            = $cpt_name;
    $this->cpt_columns         = $cpt_columns;
    $this->cpt_custom_columns  = $cpt_custom_columns;
    $this->cpt_custom_columns_filters  = $cpt_custom_columns_filters;

    return $this;
  }

  public function add_custom_columns( $columns ) 
  {
    $columns = $this->cpt_columns;

    return $columns;
  }

  public function manage_custom_columns( $column, $post_id ) 
  {
    $meta_value = get_post_meta( $post_id, '_avant_folio_work_info_key', true );

    // Image Column
    if ( 'image' === $column ) {

      $thumbnail = get_the_post_thumbnail( $post_id, array(80, 80) );

      echo (
        '<a href="'. get_site_url() .'/wp-admin/post.php?post='. $post_id .'&action=edit">' 
          . $thumbnail . 
        '</a>'
      );
    }

    foreach ($this->cpt_custom_columns as $key => $value) {
      if ( $key === $column ) {

        if ( !$meta_value || !isset($meta_value[$key]) ) {
          _e( 'n/a' );  
        } else {
          echo (
            '<a href="'. get_site_url() .'/wp-admin/edit.php?post_type=' . $this->cpt_name . '&' . $key . '='. $meta_value[$key] .'">' 
              . $meta_value[$key] . 
            '</a>'
          );
        }
      }
    }
  }

  public function set_sortable_columns( $columns ) 
  {
    foreach ($this->cpt_custom_columns as $key => $value) {
      $columns[$key] = $value['sort_id'];
    };

    return $columns;
  }

  public function set_posts_orderby( $query ) 
  {
    if( ! is_admin() || ! $query->is_main_query() ) {
      return;
    }

    foreach ( $this->cpt_custom_columns as $key => $value ) {
      if ( $value['sort_id'] === $query->get( 'orderby' ) ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', '_avant_folio_' . $key . '_key' );
        // $query->set( 'meta_type', 'numeric' );

      }
    }

    global $post_type, $pagenow;

    //if we are currently on the edit screen of the post type listings
    if($pagenow == 'edit.php' && $post_type == $this->cpt_name){

      if( isset( $_GET['avant_folio_work_type_filter'] ) || isset( $_GET['avant_folio_date_completed_filter'] ) ){

        //get the desired post format
        $work_type = sanitize_text_field($_GET['avant_folio_work_type_filter']);
        $date_completed = sanitize_text_field($_GET['avant_folio_date_completed_filter']);
        //if the post format is not 0 (which means all)
        if( $work_type != 0 && $date_completed != 0 ){
          
          $query->query_vars['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy'  => 'work_type',
                'field'     => 'term_taxonomy_id',
                'terms'     => array($work_type)
            ),
            array(
                'taxonomy'  => 'date_completed',
                'field'     => 'term_taxonomy_id',
                'terms'     => array($date_completed)
            )
          );
        }
        if( $work_type != 0 && $date_completed == 0 ){
          
          $query->query_vars['tax_query'] = array(
            array(
                'taxonomy'  => 'work_type',
                'field'     => 'term_taxonomy_id',
                'terms'     => array($work_type)
            )
          );
        }
        if( $work_type == 0 && $date_completed != 0 ){
          
          $query->query_vars['tax_query'] = array(
            array(
                'taxonomy'  => 'date_completed',
                'field'     => 'term_taxonomy_id',
                'terms'     => array($date_completed)
            )
          );
        }
      }
    }   
  }

  public function add_custom_filter()
  {
    //execute only on the 'post' content type
    global $post_type;

    if( $post_type == $this->cpt_name ){

      foreach ($this->cpt_custom_columns_filters as $filter) {

        //if we have a post format already selected, ensure that its value is set to be selected
        if( isset( $_GET[ $filter['name'] ] ) ){
            $filter['selected'] = sanitize_text_field( $_GET[ $filter['name'] ] );
        }
  
        wp_dropdown_categories($filter);
      }
    }
  }
}