<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Base\CustomFieldsController;
use Includes\Base\BaseController;

class CustomPostTypeController extends BaseController
{
	public $custom_post_types = array();
	public $custom_fields;

  public function register() 
  {
		$this->storeCpt();

		if( ! empty( $this->custom_post_types ) ) {
			add_action( 'init', array( $this, 'registerCpt' ) );
			add_filter( 'enter_title_here', array( $this, 'setCustomEnterTitle' ) );
		}
	}
	
	public function setCptLabels( string $cpt_name ) {
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

	public function setCptArguments( array $cpt_supports, array $cpt_labels, string $cpt_icon ) {
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
	
	public function storeCpt() 
	{
		$this->custom_post_types =array(
			array(
				'cpt_name'     => 'works',
				'cpt_supports' => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
				'cpt_icon'     => 'dashicons-visibility',
				'cpt_custom_fields' => array(
					'id'       => 'work-information',
					'title'    => esc_html__( 'Work Details', 'string' ),
					'screen'   => 'works',
					'meta-key' => 'avant_folio_work_info',
				)
			),
			array(
				'cpt_name'       => 'exhibitions',
				'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
				'cpt_icon'       => 'dashicons-awards'
			)
		);
	}

  public function registerCpt()
	{
		foreach ( $this->custom_post_types as $custom_post_type ) {

			$custom_post_type['cpt_labels']			=	$this->setCptLabels( $custom_post_type['cpt_name'] );
			$custom_post_type['cpt_arguments']	=	$this->setCptArguments( $custom_post_type['cpt_supports'], $custom_post_type['cpt_labels'], $custom_post_type['cpt_icon'] );

			register_post_type( 
				$custom_post_type['cpt_name'], 
				$custom_post_type['cpt_arguments']
			);

			array_key_exists ( 'cpt_custom_fields' , $custom_post_type ) ? $this->createCustomFields($custom_post_type['cpt_custom_fields']) : '';
		}
	}

	public function createCustomFields($cpt_custom_fields) 
	{
		$custom_fields = new CustomFieldsController();

		$custom_fields
			->setMetabox($cpt_custom_fields)
			->register();
	}

	public function setCustomEnterTitle() 
	{
		$screen = get_current_screen();
		$title = rtrim( $screen->post_type,'s' );
		
    return 'Add title of the new ' . $title . '';
	}
}