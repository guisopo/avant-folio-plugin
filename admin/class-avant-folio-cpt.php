<?php
class Avant_Folio_CPT {
  protected $cpts;
  protected $supports;
  protected $labels;
  protected $args;

  public function __construct() {
    $this->cpts = 'works';
    $this->supports = array(
      'title',
      'thumbnail',
      'revisions',
      'post-formats'
    );
  }

  public function set_labels() {
    $cpt_name = ucfirst($this->cpts);
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
      'menu_icon'     => 'dashicons-visibility'
    );
  }
  
  public function register_cpt() {
    $this->set_labels();
    $this->set_cpt_arguments(); 
    register_post_type( $this->cpts, $this->args);
  }

  public function set_custom_enter_title( $input ) {
    if ( 'works' === get_post_type() ) {
        return 'Add title of the new work';
    }
    return $input;
  }
}