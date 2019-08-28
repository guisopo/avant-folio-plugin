<?php
class Avant_Folio_Theme {
  protected $post_formats;
  protected $html5;
  protected $supports;

  public function __construct() {

    $this->post_formats = array( 
      'gallery', 
      'image', 
      'video'
    );

    $this->html5 = array( 
      'search-form', 
      'comment-form', 
      'comment-list', 
      'gallery', 
      'caption' 
    );

    $this->supports = array( 
      'title-tag' => '', 
      'post-thumbnails' => '',
      'post-formats' => $this->post_formats,
      'html5' => $this->html5
    );
  }

  public function set_theme_support() {
    
    foreach ($this->supports as $key => $value) {
      
      !empty($value) ?
        add_theme_support( $key, $value )
      :
        add_theme_support( $key );
    }
  }

  public function set_image_sizes() {

    add_image_size( 'admin-list-thumb', 80, 80, true);
  }
}