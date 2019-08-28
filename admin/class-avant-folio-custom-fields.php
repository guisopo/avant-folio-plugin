<?php

class Avant_Folio_Custom_Fields {

  protected $metaboxes;

  public function __construct() {
    $this->metaboxes = array(
      // Work Information
      array(
      'id' => 'folio-work-information',
      'title' => esc_html__( 'Work Details', 'string' ),
      'callback' => array($this, 'render_work_info_cf'),
      'screen' => 'works',
      'context' => 'normal',
      'priority' => 'core',
      ),
      // Work Gallery
      array(
      'id' => 'folio-work-gallery',
      'title' => esc_html__( 'Image Gallery', 'string' ),
      'callback' => array($this, 'render_work_gallery_cf'),
      'screen' => 'works',
      'context' => 'normal',
      'priority' => 'core'
      ),
    );
  }

  public function create_meta_boxes() {
    foreach ($this->metaboxes as $metabox) {
      add_meta_box(
        $metabox['id'],
        $metabox['title'],
        $metabox['callback'],
        $metabox['screen'],  
        $metabox['context'], 
        $metabox['priority']   
      );
    }
  }

  public function render_work_info_cf() {
    wp_nonce_field( basename( __FILE__ ), 'folio_work_info_nonce' );

    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-cf-work-details.php';
  }

  public function render_work_gallery_cf() {
    wp_nonce_field( basename( __FILE__ ), 'folio_work_info_nonce' );
    
    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-cf-work-gallery.php';
  }

  public function save_post_work_meta( $post_id, $post ) {

    // Checks save status
    $is_autosave    = wp_is_post_autosave( $post_id );
    $is_revision    = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'folio_work_info_nonce' ] ) && wp_verify_nonce( $_POST[ 'folio_work_info_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    $user_can_edit  = current_user_can( 'edit_post', $post_id );
  
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce || !$user_can_edit ) {
        return;
    }
  
    /* Get the posted data and sanitize it for use as an HTML class. */
    $new_meta_value = isset( $_POST['folio_work'] ) ? array_map( 'work_fields_sanitize', $_POST['folio_work'] ) : '';
    $cat = $new_meta_value['category'];
    $new_meta_value['title'] = sanitize_text_field($_POST['post_title']);
    $new_meta_value = array_filter($new_meta_value);
  
    /* Get the meta key. */
    $meta_key = '_folio_work_meta_key';
  
    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );
  
    /* If the new meta value does not match the old value, update it. */
    if ( $new_meta_value && $new_meta_value != $meta_value ) {
      wp_set_post_terms( $post_id, $cat, 'work_type' );
      update_post_meta( $post_id, $meta_key, $new_meta_value );
    }
  
    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( $new_meta_value === '' && $meta_value )
      delete_post_meta( $post_id, $meta_key, $meta_value );
  }
  
  function work_fields_sanitize( $input ) {
    
    if ( $input === $_POST['folio_work']['description'] || $input === $_POST['folio_work']['credits'] ) {
      return sanitize_textarea_field( $input );
    } else {
      return sanitize_text_field( $input );
    }
  }
  
}