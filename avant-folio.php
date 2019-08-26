<?php
/*
Plugin Name: Avant Folio
Description: Create a custom post type to handle a portfolio for artist
Version: 1.0
Author: Guillermo Soler Poquet
Author URI: http://github.com/guisopo
License: GPLv2
*/

// If something else external from the website is accesing those files ABSPATH is not defined
defined ( 'ABSPATH' ) or die('You cannot acces this file!');

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ){
  die;
}

/**
 * Include core class for executing the plugin
 */

require_once( plugin_dir_path(__FILE__) . 'includes/admin/class-avant-folio.php' );

/**
 * Begin excution of plugin
 */
function run_avant_folio() {
  $plugin = new Avant_Folio();
  $plugin->run();
}
run_avant_folio();

function avant_folio_activation() {
  // flush rewrite rules: tells WP something is happenning in the DB and needs to refresh in order to read the new information
  flush_rewrite_rules();
}

// Call function when plugin is activated
register_activation_hook( __FILE__, 'run_avant_folio' );
