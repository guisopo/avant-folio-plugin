<?php

class Avant_Folio {

  private $name;
  private $version;

  public function __construct() {

    $this->name = 'avant_folio';
    $this->version = '0.1.0';
  }

  public function run() {
    add_action( 'admin_init', array( $this, 'register_settings' ) );
    add_action( 'admin_menu', array( $this, 'addMenuPage' ) );
  }

  public function addMenuPage() {
    add_menu_page(
      $this->name,
      'Portfolio',
      'manage_options',
      $this->name,
      array( $this, 'profile_page' ),
      'dashicons-admin-customizer',
      '2'
    );

    $this->addSubMenuPages();
  }

  public function addSubMenuPages() {

    $subMenuPages = array(
      // User Profiles
      array(
        'parent_slug' => $this->name,
        'page_title'  => 'User Profile Page',
        'menu_title'  => 'User Pofile',
        'capability'  => 'manage_options',
        'menu_slug'   => $this->name,
        'callback'    => array( $this, 'profile_page' ) 
      ),
      // Portfolio Settings
      array(
        'parent_slug' => $this->name,
        'page_title'  => 'Portfolio Settings Page',
        'menu_title'  => 'Portfolio Settings',
        'capability'  => 'manage_options',
        'menu_slug'   => 'avant_folio_settings',
        'callback'    => array( $this, 'settings_page' )
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

  public function profile_page() {
    require_once( plugin_dir_path( __FILE__ )  . '../templates/avant-folio-admin.php' );
  }
  
  public function settings_page() {
    require_once( plugin_dir_path( __FILE__ )  . '../templates/avant-folio-settings.php' );
  }
  
  public function register_settings() {

    register_setting( 
      'avant-folio-settings-group', 
      'avant_folio_options', 
      array($this, 'sanitize_settings')
    );
  }

  public function sanitize_settings( $input ) {
    $input['option_name']      = sanitize_text_field( $input['option_name'] );
    $input['option_last_name'] = sanitize_text_field( $input['option_last_name'] );
    $input['option_facebook']  = sanitize_text_field( $input['option_facebook'] );
    $input['option_twitter']   = $this->sanitize_at_symbol($input['option_twitter']);
    $input['option_instagram'] = $this->sanitize_at_symbol($input['option_instagram']);
    
    return $input;
  }

  public function sanitize_at_symbol( $input ){
    $output = sanitize_text_field( $input );
    $output = str_replace('@', '', $output);
    return $output;
  }
}