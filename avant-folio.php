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
 * Begin execution of plugin
 */

$plugin = new Avant_Folio();
$plugin->run();

// Activation
register_activation_hook( __FILE__, array($plugin, 'activate') );
// Deactivation
register_deactivation_hook( __FILE__, array($plugin, 'deactivate') );