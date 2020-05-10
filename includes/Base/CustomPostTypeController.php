<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

use Includes\Api\SettingsApi;
use Includes\Base\BaseController;

class CustomPostTypeController extends BaseController
{
	public $custom_post_types = array();

  public function register() 
  {
		$this->storeCpt();

		if( ! empty( $this->custom_post_types ) ) {
			add_action( 'init', array( $this, 'registerCpt' ) );
		}
	}

	public function setCptLabels() {
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
	
	public function storeCpt() 
	{
		$this->custom_post_types =array(
			array(
				'post_type' => 'prueba',
				'name'			=> 'Pruebas',
				'singular_name' => 'Prueba',
				'public' => true,
				'has_archive' => true
			),
			array(
					'post_type' => 'prueba2',
					'name'			=> 'Pruebas2',
					'singular_name' => 'Prueba2',
					'public' => true,
					'has_archive' => true
			)
		);
	}

  public function registerCpt()
	{
		foreach ($this->custom_post_types as $custom_post_type) {
			register_post_type( $custom_post_type['post_type'],
				array(
					'labels' => array(
						'name' => $custom_post_type['name'],
						'singular_name' => $custom_post_type['singular_name']
					),
					'public' => $custom_post_type['public'],
					'has_archive' => $custom_post_type['has_archive'],
				)
			);
		}
	}
}