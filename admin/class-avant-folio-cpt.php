<?php
class Avant_Folio_CPT {
  protected $cpts;
  protected $cpt;
  protected $supports;
  protected $labels;
  protected $args;
  protected $icons;
  protected $icon;

  public function __construct() {
    $this->cpts = array(
      'works' => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'exhibitions' => array( 'title', 'thumbnail', 'revisions', 'post-formats' )
    );
    $this->icons = array(
      'works' => 'dashicons-visibility',
      'exhibitions' => 'dashicons-awards'
    );
  }

  public function set_labels() {

    $cpt_name = ucfirst($this->cpt);
    $cpt_singular = rtrim($cpt_name,'s');

    $this->labels = array(
      'name'               => $cpt_name,
      'singular_name'      => 'Work',
      'add_new'            => 'Add New ' . $cpt_singular . '',
      'add_new_item'       => 'Add New ' . $cpt_singular . '',
      'edit_item'          => 'Edit ' . $cpt_singular . '',
      'new_item'           => 'New ' . $cpt_singular . '',
      'view_item'          => 'View ' . $cpt_singular . '',
      'all_item'           => 'All ' . $cpt_name . '',
      'search_items'       => 'Search ' . $cpt_name . '',
      'not_found'          => 'No ' . $cpt_name . ' found',
      'not_found_in_trash' => 'No ' . $cpt_name . ' found in trash',
      'archives'           => '' . $cpt_name . ' Archives'
    );
  }

  public function set_cpt_arguments() {
    
    $this->args = array(
      'public'        => true,
      'supports'      => $this->supports,
      'labels'        => $this->labels,
      'hierarchical'  => true,
      'has_archive'   => true,
      'menu_position' => 5,
      'show_in_rest'  => true,
      'menu_icon'     => $this->icon
    );
  }
  
  public function register_cpt() {

    foreach ($this->cpts as $key => $value) {
      $this->cpt = $key;
      $this->supports = $value;
      $this->icon = $this->icons[$key];
      
      $this->set_labels();
      $this->set_cpt_arguments();
      
      register_post_type( $key, $this->args);
    }
  }

  public function set_custom_enter_title($input) {
    $cpt_singular = rtrim($this->cpt,'s');
    return 'Add title of the new ' . $cpt_singular . '';
  }
}