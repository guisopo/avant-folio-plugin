<?php

class Avant_Folio_Gallery {

  protected $name;
  protected $version;

  protected $id;
  protected $title;
  protected $cpt;
  protected $context;
  protected $priority;

  public function __construct( $gallery_args ) {
    $this->id = $gallery_args['id'];

    if( $gallery_args['title'] == '' ) {
      $this->title = ucfirst($this->id);
    } else {
      $this->title = $gallery_args['title'];
    } 

    $this->cpt = $gallery_args['cpt'];

    $available_context = array(
      'advanced',
      'side',
      'normal'
    );

    if( in_array( $gallery_args['context'], $available_context ) ) {
      $this->context = $gallery_args['context'];
    }

    $available_priority = array(
      'default',
      'high',
      'normal'
    );

    if( in_array( $gallery_args['priority'], $available_priority ) ) {
      $this->priority = $gallery_args['priority'];
    }

    $this->version = '0.1.0';
  }

  public function add_metabox() { 
    
    add_meta_box(
      $this->id,
      $this->title,
      array( $this, 'render' ),
      $this->cpt,
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

  public function render($post) {
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/avant-folio-gallery.php';
  }
}