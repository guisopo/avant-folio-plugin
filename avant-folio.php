<?php
/*
Plugin Name: Avant Folio
Description: Create a custom post type to handle a portfolio for artist
Version: 1.0
Author: Guillermo Soler Poquet
Author URI: http://github.com/guisopo
License: GPLv2
*/

// Call function when plugin is activated
register_activation_hook( __FILE__, 'avant_folio_install' );

function avant_folio_install() {
}

add_action( 'admin_menu', 'avant_folio_create_menu' );

function avant_folio_create_menu() {
  add_menu_page(
    'Portfolio Plugin Page', 
    'Portfolio', 
    'manage_options', 
    'avant_folio', 
    'avant_folio_profile_page', 
    'dashicons-admin-customizer',
    '2'
  );

  add_submenu_page(
    'avant_folio',
    'User Profile Page',
    'User Profile',
    'manage_options',
    'avant_folio',
    'avant_folio_profile_page'
  );

  add_submenu_page(
    'avant_folio',
    'Portfolio Settings Page',
    'Portfolio Settings',
    'manage_options',
    'avant_folio_settings', 
    'avant_folio_settings_page'
  );

  add_action( 'admin_init', 'avant_folio_register_settings' );
}

function avant_folio_register_settings() {
  register_setting( 'avant-folio-settings-group', 'avant_folio_options', 'avant_folio_sanitize_options' );
}

function avant_folio_sanitize_options( $input ) {
  $input['option_name'] = sanitize_text_field( $input['option_name'] );
  $input['option_last_name'] = sanitize_text_field( $input['option_last_name'] );
  $input['option_twitter'] = avant_folio_sanitize_at_symbol( $input['option_twitter'] );
  $input['option_instagram'] = avant_folio_sanitize_at_symbol( $input['option_instagram'] );
  $input['option_facebook'] = sanitize_text_field( $input['option_facebook'] );
  
  return $input;
}

function avant_folio_profile_page() {
  require_once( plugin_dir_path( __FILE__ )  . '/includes/templates/avant-folio-admin.php' );
}

function avant_folio_sanitize_at_symbol( $input ){
	$output = sanitize_text_field( $input );
	$output = str_replace('@', '', $output);
	return $output;
}