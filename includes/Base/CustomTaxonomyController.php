<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Base\BaseController;

class CustomTaxonomyController extends BaseController
{
  public $taxonomies = array();
  public $taxonomies_options = array();
  
  public function register() 
  {
    $this->addTaxonomyOptions();
    $this->addTaxonomies();

    if( ! empty($this->taxonomies) ) {
      add_action( 'init', array( $this, 'registerTaxonomies' ));
    }
  }

  public function addTaxonomyOptions() {
    $this->taxonomies_options = array(
      array(
        'cpt'           => 'works',
        'id'            => 'work_type',
        'plural_name'   => 'Types of Work',
        'singular_name' => 'Type of Work',
        'terms'         => [ 'painting', 'drawing', 'sculpture', 'ceramic', 'photography', 'collage', 'video', 'performance', 'installation', '3D Art']
      ),
      array(
        'cpt'           => 'works',
        'id'            => 'date_completed',
        'plural_name'   => 'Dates',
        'singular_name' => 'Date',
        'show_ui'       => false
      )
    );
  }

  public function addTaxonomies() 
  {
    foreach ( $this->taxonomies_options as $option ) {

      $labels = array(
        'name'          => $option['plural_name'],
        'singular_name' => $option['singular_name'] ,
        'search_items'  => 'Search '  . $option['plural_name'] . '',
        'popular_items' => 'Popular ' . $option['plural_name'] . '',
        'all_items'     => 'All '     . $option['plural_name'] . '',
        'edit_item'     => 'Edit '    . $option['singular_name'] . '',
        'view_item'     => 'View '    . $option['singular_name'] . '',
        'update_item'   => 'Update '  . $option['singular_name'] . '',
        'add_new_item'  => 'Add New ' . $option['singular_name'] . '',
        'new_item_name' => 'New '     . $option['singular_name'] . ' Name',
        'not_found'     => 'No '      . $option['singular_name'] . ' found',
        'no_terms'      => 'No '      . $option['plural_name'] . '',
        'add_or_remove_items'        => 'Add or remove '             . $option['plural_name'] . '',
        'choose_from_most_used'      => 'Choose from the most used ' . $option['plural_name'] . '',
        'separate_items_with_commas' => 'Separate '                  . $option['plural_name'] . ' with commas'
      );

      $taxonomy['id']   = $option['id'];
      $taxonomy['cpt']  = $option['cpt'];
      $taxonomy['arguments'] = array(
        'labels'            => $labels,
        'query_var'         => true,
        'rewrite'           => true,
        'slug'              => $option['id'],
        'show_ui'           => $option['show_ui'] ?? true,
        'show_in_rest'      => true
      );

      $taxonomy['terms'] = $option['terms'] ?? null;

      $this->taxonomies[] = $taxonomy;
    }

    return $this;
  }

  public function registerTaxonomies() 
  { 
    foreach ( $this->taxonomies as $taxonomy ) {
      register_taxonomy( 
        $taxonomy['id'],
        $taxonomy['cpt'],
        $taxonomy['arguments'] 
      );

      if ( isset( $taxonomy['terms'] ) ) {
        $this->addTaxonomyTerms( $taxonomy );
      }
    }
  }

  public function addTaxonomyTerms( array $taxonomy ) 
  {
    foreach ($taxonomy['terms'] as $term) {
      wp_insert_term(
        ucfirst($term),
        $taxonomy['id'],
        array( 'slug' => $term ) 
      );
    }
  }
}