<?php

class Avant_Folio_Custom_Columns {

  public function __construct() {

  }

  public function add_custom_columns($columns) {
    $columns = array(
      'cb'              =>  $columns['cb'],
      'image'           =>  __('Image'),
      'title'           =>  __('Title'),
      'work_type'       =>  __('Work Type'),
      'date_completed'  =>  __('Date Completed'),
      'date'            =>  __('Date Published'),
    );

    return $columns;
  }

  public function manage_custom_columns( $column, $post_id ) {
    
    // Image Column
    if ( 'image' === $column ) {

      $thumbnail = get_the_post_thumbnail( $post_id, array(80, 80) );
      echo (
        '<a href="'. get_site_url() .'/wp-admin/post.php?post='. $post_id .'&action=edit">' 
          . $thumbnail . 
        '</a>'
      );
    }

    // Date Column
    if ( 'date_completed' === $column ) {

      $date = get_post_meta( $post_id, '_avant_folio_date_meta_key', true );

      if ( !$date ) {
        _e( 'n/a' );  
      } else {
        echo (
          '<a href="'. get_site_url() .'/wp-admin/edit.php?post_type=works&date_completed='. $date .'">' 
            . $date . 
          '</a>'
        );
      }
    }

    // Work Type Column
    if ( 'work_type' === $column ) {

      $work_type = get_post_meta( $post_id, '_avant_folio_type_meta_key', true );

      if ( !$work_type ) {
        _e( 'n/a' );  
      } else {
        echo (
          '<a href="'. get_site_url() .'/wp-admin/edit.php?post_type=works&work_type=' . $work_type . '">' 
            . $work_type . 
          '</a>'
        );
      }
    }
  }

  public function set_sortable_columns( $columns ) {

    $columns['date_completed'] = 'date';
    $columns['work_type'] = 'type';

    return $columns;
  }

  public function set_posts_orderby( $query ) {
    if( ! is_admin() || ! $query->is_main_query() ) {
      return;
    }

    if ( 'date' === $query->get( 'orderby') ) {
      $query->set( 'orderby', 'meta_value' );
      $query->set( 'meta_key', '_avant_folio_date_meta_key' );
      $query->set( 'meta_type', 'numeric' );
    }

    if ( 'type' === $query->get( 'orderby') ) {
      $query->set( 'orderby', 'meta_value' );
      $query->set( 'meta_key', '_avant_folio_type_meta_key' );
    }
  }
}