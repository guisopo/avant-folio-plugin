<?php

class Avant_Folio_Custom_Fields {

  protected $metaboxes;
  protected $metabox_nonce;
  protected $metabox_id;

  public function __construct( $metabox ) {

    $this->metabox       = $metabox;
    $this->metabox_nonce = $this->metabox['meta-key'] . '_nonce';
  }

  public function create_meta_boxes() {

    add_meta_box(
      $this->metabox['id'],
      $this->metabox['title'],
      array( $this, $this->metabox['callback'] ),
      $this->metabox['screen'],  
      isset( $this->metabox['context'] ) ?: 'normal', 
      isset( $this->metabox['priority'] ) ?: 'core'   
    );
  }

  public function render_cf($post) {

    wp_nonce_field( basename( __FILE__ ), $this->metabox_nonce );

    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-cf-' . $this->metabox['id'] . '.php';
  }

  public function setPostFormat( $work_type, $gallery ) {
    // $gallery_work_type = [ 'Painting', 'Installation', 'Ceramic', 'Drawing', 'Photography' ];
    
    if ( $work_type !== 'video' && !$gallery ) {
      set_post_format($post_id, 'image');
    } else if ( $work_type !== 'video' && $gallery ) {
      set_post_format($post_id, 'gallery');
    } else if ( $work_type == 'Video' ) {
      set_post_format($post_id, 'video');
    }  else {
      set_post_format($post_id, 'standard');
    }
  }

  public function save_post_work_meta( $post_id, $post ) {

    // Checks save status
    $is_autosave    = wp_is_post_autosave( $post_id );
    $is_revision    = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ $this->metabox_nonce ] ) && wp_verify_nonce( $_POST[ $this->metabox_nonce ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    $user_can_edit  = current_user_can( 'edit_post', $post_id );
  
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce || !$user_can_edit ) {
      return;
    }
    
    /* Get the posted data and sanitize it */
    if ( isset( $_POST[ $this->metabox['meta-key'] ] ) ) {
      $new_meta_value = $this->sanitize_fields( $_POST[ $this->metabox['meta-key'] ] );
    } else {
      return;
    }

    // $new_meta_value = isset( $_POST[ $this->metabox['meta-key'] ] ) ?  $this->sanitize_fields( $_POST[ $this->metabox['meta-key'] ] ) : '';
    $new_meta_value['title'] = sanitize_text_field( $_POST[ 'post_title' ] );
    $new_meta_value = array_filter($new_meta_value);

    /* Get the meta key. */
    $meta_key =  '_' . $this->metabox['meta-key'] . '_key';

    $work_type_meta_key      = '_avant_folio_work_type_key';
    $date_completed_meta_key = '_avant_folio_date_completed_key';
  
    /* Get the meta value of the custom field key. */
    $meta_value = get_post_meta( $post_id, $meta_key, true );

    /* If the new meta value does not match the old value, update it. */
    if ( $new_meta_value && $new_meta_value != $meta_value ) {
      
      $work_type      = $new_meta_value['work_type'];
      $date_completed = $new_meta_value['date_completed'];
      
      wp_set_post_terms( $post_id, $work_type, 'work_type' );
      wp_set_post_terms( $post_id, $date_completed, 'date_completed' );

      update_post_meta( $post_id, $work_type_meta_key, $work_type );
      update_post_meta( $post_id, $date_completed_meta_key, $date_completed );

      update_post_meta( $post_id, $meta_key, $new_meta_value );

      $this->setPostFormat( $new_meta_value['work_type'], $new_meta_value['gallery'] );
      
      
      set_post_thumbnail( $post_id, $new_meta_value['featured_image']);
    }
  
    /* If there is no new meta value but an old value exists, delete it. */
    elseif ( $new_meta_value === '' && $meta_value )
      delete_post_meta( $post_id, $meta_key, $meta_value );
  }
  
  public function sanitize_fields( $input ) {

    foreach ($input as $key => $value) {

      if ( $key == 'credits' ) {
        $input[$key] = sanitize_textarea_field( $value );
      }

      else {
        $input[$key] = sanitize_text_field( $value );
      }
    }

    return $input;
  }

}