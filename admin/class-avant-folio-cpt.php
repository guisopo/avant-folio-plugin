<?php
class Avant_Folio_CPT {
  protected $cpts;
  protected $supports;
  protected $labels;
  protected $args;

  public function __construct() {
    $this->cpts = 'exhibitions';
    $this->supports = array(
      'title',
      'thumbnail',
      'revisions',
      'post-formats'
    );
  }

  public function set_labels() {

    $singular = rtrim($this->cpts,'s');

    $this->labels = array(
      'name'               => $this->cpts,
      'singular_name'      => 'Work',
      'add_new'            => 'Add New ' . $singular . '',
      'add_new_item'       => 'Add New ' . $singular . '',
      'edit_item'          => 'Edit ' . $singular . '',
      'new_item'           => 'New ' . $singular . '',
      'view_item'          => 'View ' . $singular . '',
      'all_item'           => 'All ' . $this->cpts . '',
      'search_items'       => 'Search ' . $this->cpts . '',
      'not_found'          => 'No ' . $this->cpts . ' found',
      'not_found_in_trash' => 'No ' . $this->cpts . ' found in trash',
      'archives'           => '' . $this->cpts . ' Archives'
    );
  }

  public function set_cpt_arguments() {
    
    $this->args = array(
      'public'        => true,
      'supports'      => $this->supports,
      'labels'        => $this->labels,
      'hierarchical'  => true,
      'has_archive'   => true,
      'menu_position' => 6,
      'show_in_rest'  => true,
      'menu_icon'     => 'dashicons-admin-customizer'
    );
  }

  public function register_cpt() {
    $this->set_labels();
    $this->set_cpt_arguments(); 
    register_post_type( $this->cpts, $this->args);
  }
}