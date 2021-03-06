<?php

class Avant_Folio_Custom_Columns {

  public function __construct( $cpt_name, $cpt_columns, $cpt_custom_columns ) {

    $this->cpt_name            = $cpt_name;
    $this->cpt_columns         = $cpt_columns;
    $this->cpt_custom_columns  = $cpt_custom_columns;
  }

  public function add_custom_columns($columns) {
    
    $columns = $this->cpt_columns;

    return $columns;
  }

  public function manage_custom_columns( $column, $post_id ) {

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

  public function set_sortable_columns( $columns ) {

    foreach ($this->cpt_custom_columns as $key => $value) {
      $columns[$key] = $value['sort_id'];
    };

    return $columns;
  }

  public function set_posts_orderby( $query ) {

    if( ! is_admin() || ! $query->is_main_query() ) {
      return;
    }

    foreach ( $this->cpt_custom_columns as $key => $value ) {

      if ( $value['sort_id'] === $query->get( 'orderby' ) ) {
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'meta_key', '_avant_folio_' . $key . '_key' );
      }
    }
  }
}