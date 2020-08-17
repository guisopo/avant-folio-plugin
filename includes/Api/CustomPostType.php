<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api;

use Includes\Api\BaseController;

class CustomPostType extends BaseController
{
	public $custom_post_types = array();

  public function register() 
  {
		if( ! empty( $this->custom_post_types ) ) {
			add_action( 'init', array( $this, 'register_cpt' ) );
			add_filter( 'enter_title_here', array( $this, 'set_custom_enter_title' ) );
		}
	}
	
	public function store_cpt( $cptData ) 
	{
		$this->custom_post_types = $cptData;

		return $this;
	}
	
	public function set_cpt_labels( string $cpt_name ) 
	{
    $cpt_singular = rtrim( $cpt_name,'s' );

    $cpt_labels = array(
      'name'               => __( $cpt_name, 'avant-folio-plugin' ),
      'singular_name'      => __( $cpt_singular, 'avant-folio-plugin' ),
      'add_new'            => __( 'Add New ' . $cpt_singular . '', 'avant-folio-plugin' ),
      'add_new_item'       => __( 'Add New ' . $cpt_singular . '', 'avant-folio-plugin' ),
      'edit_item'          => __( 'Edit ' . $cpt_singular . '', 'avant-folio-plugin' ),
      'new_item'           => __( 'New ' . $cpt_singular . '', 'avant-folio-plugin' ),
      'view_item'          => __( 'View ' . $cpt_singular . '', 'avant-folio-plugin' ),
      'all_item'           => __( 'All ' . $cpt_name . '', 'avant-folio-plugin' ),
      'search_items'       => __( 'Search ' . $cpt_name . '', 'avant-folio-plugin' ),
      'not_found'          => __( 'No ' . $cpt_name . ' found', 'avant-folio-plugin' ),
      'not_found_in_trash' => __( 'No ' . $cpt_name . ' found in trash', 'avant-folio-plugin' ),
      'archives'           => __( '' . $cpt_name . ' Archives', 'avant-folio-plugin' )
		);
		
		return $cpt_labels;
	}

	public function set_cpt_arguments( array $cpt_supports, array $cpt_labels, string $cpt_icon ) 
	{
		$cpt_arguments = array(
      'public'        => true,
      'supports'      => $cpt_supports,
      'labels'        => $cpt_labels,
      'hierarchical'  => true,
      'has_archive'   => true,
      'menu_position' => 5,
      'show_in_rest'  => true,
      'menu_icon'     => $cpt_icon
		);
		
		return $cpt_arguments;
	}

  public function register_cpt()
	{
		foreach ( $this->custom_post_types as $custom_post_type ) {

			$this->custom_post_types['cpt_labels']			=	$this->set_cpt_labels( $this->custom_post_types['cpt_name'] );
			$this->custom_post_types['cpt_arguments']	=	$this->set_cpt_arguments( $this->custom_post_types['cpt_supports'], $this->custom_post_types['cpt_labels'], $this->custom_post_types['cpt_icon'] );

			register_post_type( 
				$this->custom_post_types['cpt_name'], 
				$this->custom_post_types['cpt_arguments']
			);
		}
	}

	public function set_custom_enter_title() 
	{
		$screen	= get_current_screen();
		$title	= rtrim( $screen->post_type,'s' );
		
    return 'Add title of the new ' . $title . '';
	}
}