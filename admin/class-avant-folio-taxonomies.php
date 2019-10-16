<?php

class Avant_Folio_Taxonomies {

  protected $taxonomy;
  protected $labels;
  protected $arguments;

  public function __construct( $taxonomy ) {

    $this->taxonomy = $taxonomy;

    $this->set_labels();
    $this->set_arguments();
  }

  public function set_labels() {
    
    $plural_name   = $this->taxonomy['plural_name'];
    $singular_name = $this->taxonomy['singular_name'];

    $this->labels = array(
      'name'          => $plural_name,
      'singular_name' => $singular_name ,
      'search_items'  => 'Search '  . $plural_name . '',
      'popular_items' => 'Popular ' . $plural_name . '',
      'all_items'     => 'All '     . $plural_name . '',
      'edit_item'     => 'Edit '    . $singular_name . '',
      'view_item'     => 'View '    . $singular_name . '',
      'update_item'   => 'Update '  . $singular_name . '',
      'add_new_item'  => 'Add New ' . $singular_name . '',
      'new_item_name' => 'New '     . $singular_name . ' Name',
      'not_found'     => 'No '      . $singular_name . ' found',
      'no_terms'      => 'No '      . $plural_name . '',
      'add_or_remove_items'        => 'Add or remove '             . $plural_name . '',
      'choose_from_most_used'      => 'Choose from the most used ' . $plural_name . '',
      'separate_items_with_commas' => 'Separate '                  . $plural_name . ' with commas'
    );
  }

  public function set_arguments() {

    $this->arguments = array(
      'labels'            => $this->labels,
      'query_var'         => true,
      'rewrite'           => true,
      'slug'              => $this->taxonomy['id'],
      'show_ui'           => $this->taxonomy['show_ui'] ?? true,
      'show_in_rest'      => true
    );
  }

  public function register_taxonomy() {

    register_taxonomy( $this->taxonomy['id'], $this->taxonomy['cpt'], $this->arguments);

    if ( !isset( $this->taxonomy['terms'] ) ) {

      return;
    }

    $this->populate_taxonomies();
  }

  public function populate_taxonomies() {

    foreach ($this->taxonomy['terms'] as $term) {
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