<?php
class Avant_Folio_CPT {

  protected $cpt_name;
  protected $cpt_supports;
  protected $cpt_labels;
  protected $cpt_args;
  protected $cpt_icon;

  public function __construct( $cpt_args ) {

    $this->cpt_name     = $cpt_args['cpt_name'];
    $this->cpt_supports = $cpt_args['cpt_supports'];
    $this->cpt_icon     = $cpt_args['cpt_icon'];
  }

  public function set_labels() {

    $cpt_name     = ucfirst($this->cpt_name);
    $cpt_singular = rtrim($cpt_name,'s');

    $this->cpt_labels = array(
      'name'               => $cpt_name,
      'singular_name'      => $cpt_singular,
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
    
    $this->cpt_arguments = array(
      'public'        => true,
      'supports'      => $this->cpt_supports,
      'labels'        => $this->cpt_labels,
      'hierarchical'  => true,
      'has_archive'   => true,
      'menu_position' => 5,
      'show_in_rest'  => true,
      'menu_icon'     => $this->cpt_icon
    );
  }
  
  public function register_cpt() {
    
    $this->set_labels();
    $this->set_cpt_arguments();
    
    register_post_type( $this->cpt_name, $this->cpt_arguments );
  }

  public function set_custom_enter_title() {
    $screen = get_current_screen();

    if ( !$this->cpt_name == $screen->post_type ) {
      return false;
    }
    
    $title = rtrim($screen->post_type,'s');
    return 'Add title of the new ' . $title . '';
  }
}


