<?php

class Avant_Folio_Gallery {

  protected $name;
  protected $version;

  protected $id;
  protected $title;
  protected $context;
  protected $priority;

  public function __construct( $id = 'Work Gallery', $title = '', $context = 'advanced', $priority = 'high' ) {
    
    $this->id = $id;

    if( $title == '' ) {
      $this->title = ucfirst($this->id);
    } else {
      $this->title = $title;
    } 

    $available_context = array(
      'advanced',
      'side',
      'normal'
    );

    if( in_array( $context, $available_context ) ) {
      $this->context = $context;
    }

    $available_priority = array(
      'default',
      'high',
      'normal'
    );

    if( in_array( $priority, $available_priority ) ) {
      $this->priority = $priority;
    }

    $this->version = '0.1.0';
  }

  public function add_metabox() { 
    
    add_meta_box(
      $this->id,
      $this->title,
      array( $this, 'render' ),
      'works',
      $this->context,
      $this->priority
    );
  }

  public function enqueue_scripts() {

    wp_enqueue_media();

    
    wp_enqueue_script(
      $this->id,
      plugin_dir_url( __FILE__ ) . 'js/avant-folio-gallery.js',
      array( 'jquery' ),
      $this->version,
      'all'
    );
  }

  public function render( $post ) {
    
    wp_nonce_field( basename( __FILE__ ), $this->metabox_nonce );

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/avant-folio-gallery.php';
  }

  public function save_images() {

  } 
}