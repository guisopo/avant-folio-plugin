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