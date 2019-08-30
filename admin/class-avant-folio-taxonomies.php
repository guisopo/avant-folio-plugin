<?php

class Avant_Folio_Taxonomies {

  protected $cpt_name;
  protected $taxonomy;
  protected $taxonomy_terms;
  protected $labels;
  protected $arguments;

  public function __construct( $cpt_name, $taxonomy, $taxonomy_terms = NULL) {
    
    $this->cpt_name = $cpt_name;
    $this->taxonomy = $taxonomy;
    $this->taxonomy_terms = $taxonomy_terms;
  }

  public function set_labels() {
    
    $plural_name = $this->taxonomy['plural_name'];
    $singular_name = $this->taxonomy['singular_name'];

    $this->labels = array(
      'name'          => $plural_name,
      'singular_name' => $singular_name ,
      'search_items'  => 'Search ' . $plural_name . '',
      'popular_items' => 'Popular ' . $plural_name . '',
      'all_items'     => 'All ' . $plural_name . '',
      'edit_item'     => 'Edit ' . $singular_name . '',
      'view_item'     => 'View ' . $singular_name . '',
      'update_item'   => 'Update ' . $singular_name . '',
      'add_new_item'  => 'Add New ' . $singular_name . '',
      'new_item_name' => 'New ' . $singular_name . ' Name',
      'not_found'     => 'No ' . $singular_name . ' found',
      'no_terms'      => 'No ' . $plural_name . '',
      'add_or_remove_items'        => 'Add or remove ' . $plural_name . '',
      'choose_from_most_used'      => 'Choose from the most used ' . $plural_name . '',
      'separate_items_with_commas' => 'Separate ' . $plural_name . ' with commas'
    );
  }

  public function set_arguments() {

    $this->arguments = array(
      'labels'            => $this->labels,
      'query_var'         => true,
      'rewrite'           => true,
      'slug'              => $this->taxonomy['id'],
      'show_ui'           => true,
      'show_admin_column' => true
    );
  }

  public function register_taxonomies() {
    $this->set_labels();
    $this->set_arguments();

    register_taxonomy( $this->taxonomy['id'], $this->cpt_name, $this->arguments);
    
    if ( $this->taxonomy_terms === NULL ) {
      return;
    }

    $this->populate_taxonomies();
  }

  public function populate_taxonomies() {
    if ( !post_type_exists( $this->cpt_name ) ) {

      foreach ($this->taxonomy_terms as $term) {
        wp_insert_term(
          ucfirst($term),
          $this->taxonomy['id'],
          array(
            'slug' => $term,
          )
  
        );
      }
    }
  }

}