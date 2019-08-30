<?php
class Avant_Folio_CPT {

  protected $cpt_name;
  protected $cpt_supports;
  protected $cpt_labels;
  protected $cpt_args;
  protected $cpt_icon;
  protected $cpt_tax_args;

  public function __construct( $cpt_args ) {
    
    $this->cpt_name     = $cpt_args['cpt_name'];
    $this->cpt_supports = $cpt_args['cpt_supports'];
    $this->cpt_icon     = $cpt_args['cpt_icon'];
    $this->cpt_tax_args = $cpt_args['cpt_taxonomies'];

    $this->set_taxonomies( $this->cpt_tax_args );
  }

  public function set_taxonomies( $taxonomies_args ) {
    
    $this->cpt_taxonomies = array();

    foreach ( $taxonomies_args as $taxonomy_arg ) {

      $taxonomy = array(
        'id'            => $taxonomy_arg['id'],
        'plural_name'   => $taxonomy_arg['plural_name'], 
        'singular_name' => $taxonomy_arg['singular_name'],
        'terms'         => $taxonomy_arg['terms']
      );

      $cpt_taxonomy = new Avant_Folio_Taxonomies( $this->cpt_name, $taxonomy );
      
      $this->cpt_taxonomies[] = $cpt_taxonomy;
    }
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
    
    $this->register_cpt_taxonomies();
  }

  public function register_cpt_taxonomies() {

    foreach ( $this->cpt_taxonomies as $cpt_taxonomy ) {

      $cpt_taxonomy->register_taxonomy();
    }
  }

  public function set_custom_enter_title( $input ) {

    $cpt_singular = rtrim($this->cpt,'s');
    return 'Add title of the new ' . $cpt_singular . '';
  }
}