<?php
/**
 * @package AvantFolio
 */

/*
Plugin Name: Avant Folio
Description: Create a custom post type to handle a portfolio for artist
Version: 1.0
Author: Guillermo Soler Poquet
Author URI: http://github.com/guisopo
License: GPLv2 or later
*/

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

// If something else external from the website is accesing those files ABSPATH is not defined
defined ( 'ABSPATH' ) or die('You cannot acces this file!');

// Require once the Composer Autoload
if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
  require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ){
  die;
}

if( ! function_exists( 'add_action' )) {
  echo 'You cannot acces this file!';
  die;
}

/**
 * Include core class for executing the plugin
 */
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-avant-folio.php' );

/**
 * Code that runs during plugin activation
 */
function activate_avant_folio() {
  Includes\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_avant_folio');

/**
 * Code that runs during plugin deactivation
 */
function deactivate_avant_folio() {
  Includes\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_avant_folio');

/**
 * Initialize all the core classes of the plugin
 */
if( class_exists( 'Includes\\Init' ) ) {
  Includes\Init::register_services();
}

// $plugin = new Avant_Folio();
// $plugin->run();
