<?php

class Avant_Folio_Gallery {

  function construct() {

    $this->name = 'Image Gallery';
    $this->version = '0.1.0';
  }

  public function add_metabox() {
    
    add_meta_box(
      $this->name,
      'Footer Featured Image',
      array( $this, 'render' ),
      'works'
    );
  }

  public function enqueue_scripts() {
 
    wp_enqueue_media();

    wp_enqueue_script(
        $this->name,
        plugin_dir_url( __FILE__ ) . 'js/gallery.js',
        array( 'jquery' ),
        $this->version,
        'all'
    );

}

  public function render() {

    require_once plugin_dir_path( dirname( __FILE__ )) . 'partials/avant-folio-gallery.php';
  }
}