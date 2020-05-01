<?php

/**
 * Trigger this file on Plugin uninstall
 * 
 * @package AvantFolio
 */

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  die;
}


// Access the DB via SQL
global $wpdb;

// Clear Database Store Data
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'works'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );
$wpdb->query( "DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)" );

