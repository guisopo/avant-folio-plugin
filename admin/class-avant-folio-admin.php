<?php
class Avant_Folio_Admin {
  
  protected $version;
  private $adminPages;
  private $subMenuPages;

  public function __construct($version) {

    $this->version = $version;

    $this->adminPage = array(
      'page_title' => 'Avant Folio Page',
      'menu_title' => 'Portfolio',
      'capability' => 'manage_options',
      'menu_slug'  => 'avant-folio-user-profile',
      'callback'   => array( $this, 'render_user_profile_page' ),
      'icon_url'   => 'dashicons-admin-customizer',
      'position'   => '2'
    );

    $this->subMenuPages = array(
      // User Profile
      array(
        'parent_slug' => 'avant-folio-user-profile',
        'page_title'  => 'User Profile Page',
        'menu_title'  => 'User Profile',
        'capability'  => 'manage_options',
        'menu_slug'   => 'avant-folio-user-profile',
        'callback'    => array( $this, 'render_user_profile_page' ) 
      ),
      // Portfolio CV
      array(
        'parent_slug' => 'avant-folio-user-profile',
        'page_title'  => 'Curriculum Vitae',
        'menu_title'  => 'CV',
        'capability'  => 'manage_options',
        'menu_slug'   => 'avant_folio_cv',
        'callback'    => array( $this, 'render_cv_page' )
      )
    );
  }

  public function enqueue_styles() {

    wp_enqueue_style(
      'avant-folio-admin', 
      plugin_dir_url(__FILE__) . 'css/avant-folio-admin.css', 
      array(), 
      $this->version, 
      false
    );
  }

  public function add_menu_pages() {

    add_menu_page(
      $this->adminPage['page_title'],
      $this->adminPage['menu_title'],
      $this->adminPage['capability'],
      $this->adminPage['menu_slug'],
      $this->adminPage['callback'],
      $this->adminPage['icon_url'],
      $this->adminPage['position']
    );

    $this->addSubMenuPages();
  }

  private function addSubMenuPages() {

    foreach ( $this->subMenuPages as $subpage) {
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

  public function remove_menu_pages() {
    remove_menu_page( 'index.php' );
    remove_menu_page( 'edit.php' );
    remove_menu_page( 'edit-comments.php' );
  }

  public function render_user_profile_page() {
    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-user-profile.php';
  }

  public function render_cv_page() {
    require_once plugin_dir_path( dirname( __FILE__ ) )  . 'partials/avant-folio-cv.php';
  }
}