<?php

class Avant_Folio_Admin {
  protected $version;

  public function __construct($version) {
    $this->version = $version;
  }

  public function enqueue_styles() {
    wp_enqueue_style(
      'avant-folio-admin', 
      plugin_dir_url(__FILE__) . 'css/avant-folio-admin.css', 
      array(), $this->version, 
      false
    );
  }

  public function add_menu_pages() {
    add_menu_page(
      'Avant Folio Page',                    // $page_title
      'Portfolio',                           // $menu_title
      'manage_options',                      // $capability
      'avant-folio-admin',                   // $menu_slug
      array( $this, 'render_profile_page' ), // $callback
      'dashicons-admin-customizer',          // $icon_url
      '2'                                    // $position
    );

    $this->addSubMenuPages();
  }

  public function addSubMenuPages() {

    $subMenuPages = array(
      // User Profiles
      array(
        'parent_slug' => 'avant-folio-admin',
        'page_title'  => 'User Profile Page',
        'menu_title'  => 'User Profile',
        'capability'  => 'manage_options',
        'menu_slug'   => 'avant-folio-admin',
        'callback'    => array( $this, 'render_profile_page' ) 
      ),
      // Portfolio Settings
      array(
        'parent_slug' => 'avant-folio-admin',
        'page_title'  => 'Portfolio Settings Page',
        'menu_title'  => 'Portfolio Settings',
        'capability'  => 'manage_options',
        'menu_slug'   => 'avant_folio_settings',
        'callback'    => array( $this, 'render_settings_page' )
      )
    );

    foreach ( $subMenuPages as $subpage) {
      add_submenu_page(
        $subpage['parent_slug'],
        $subpage['page_title'],
        $subpage['menu_title'],
        $subpage['capability'],
        $subpage['menu_slug'],
        $subpage['callback']
      );
    }
  }

  public function render_profile_page() {
    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-admin.php';
  }

  public function render_settings_page() {
    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-settings.php';
  }
}