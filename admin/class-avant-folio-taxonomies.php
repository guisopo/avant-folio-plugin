<?php

class Avant_Folio_Taxonomies {

  protected $taxonomies;
  protected $taxonomy;
  protected $taxonomy_terms;
  protected $tax_plural_name;
  protected $tax_singular_name;
  protected $labels;
  protected $arguments;

  public function __construct() {
    
    $this->taxonomies = array(
      'works' => ['work_type', 'Types of Work', 'Type of Work']
    );

    $this->taxonomy_terms = array( 
      'work_type' => array(
        'painting', 
        'sculpture', 
        'drawing', 
        'performance', 
        'photography', 
        'video', 
        'installation' 
      )
    );
  }

  public function set_labels() {

    $this->labels = array(
      'name'          => $this->tax_plural_name,
      'singular_name' => $this->tax_singular_name ,
      'search_items'  => 'Search ' . $this->taxonomy_plural_name . '',
      'popular_items' => 'Popular ' . $this->tax_plural_name . '',
      'all_items'     => 'All ' . $this->tax_plural_name . '',
      'edit_item'     => 'Edit ' . $this->tax_singular_name . '',
      'view_item'     => 'View ' . $this->tax_singular_name . '',
      'update_item'   => 'Update ' . $this->tax_singular_name . '',
      'add_new_item'  => 'Add New ' . $this->tax_singular_name . '',
      'new_item_name' => 'New ' . $this->tax_singular_name . ' Name',
      'not_found'     => 'No ' . $this->tax_singular_name . ' found',
      'no_terms'      => 'No ' . $this->tax_plural_name . '',
      'add_or_remove_items'        => 'Add or remove ' . $this->tax_plural_name . '',
      'choose_from_most_used'      => 'Choose from the most used ' . $this->tax_plural_name . '',
      'separate_items_with_commas' => 'Separate ' . $this->tax_plural_name . ' with commas'
    );
  }

  public function set_arguments() {

    $this->arguments = array(
      'labels'            => $this->labels,
      'query_var'         => true,
      'rewrite'           => true,
      'slug'              => $this->taxonomy,
      'show_ui'           => true,
      'show_admin_column' => true
    );
  }

  public function register_taxonomies() {
    
    foreach ($this->taxonomies as $key => $value) {
      $this->taxonomy = $value[0];
      $this->tax_plural_name = $value[1];
      $this->tax_singular_name = $value[2];
      
      $this->set_labels();
      $this->set_arguments();

      register_taxonomy( $this->taxonomy, $key, $this->arguments);
    }
  }

  public function populate_taxonomies() {

    foreach ($this->taxonomy_terms as $taxonomy => $terms) {

      foreach ($terms as $term) {

        wp_insert_term(
          ucfirst($term),
          $taxonomy,
          array(
            'slug' => $term,
          )
          
        );
      }
    }
  }
}