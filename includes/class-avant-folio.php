<?php

class Avant_Folio {
  
  protected $loader;
  protected $plugin_slug;
  protected $version;

  public function __construct() {

    $this->plugin_slug = 'avant_folio';
    $this->version = '0.1.0';

    $this->load_dependencies();
    $this->define_admin_hooks();
  }

  private function load_dependencies() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin-settings.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-cpt.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-taxonomies.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-custom-fields.php';

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'theme/class-avant-folio-theme.php';

    require_once plugin_dir_path( __FILE__ ) . 'class-avant-folio-loader.php';
    $this->loader = new Avant_Folio_Loader();
  }

  private function define_admin_hooks() {

    $admin = new Avant_Folio_Admin($this->version);
    $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_menu', $admin, 'add_menu_pages' );

    $settings = new Avant_Folio_Admin_Settings();
    $this->loader->add_action( 'admin_init', $settings, 'register_settings' );

    $theme = new Avant_Folio_Theme();
    $this->loader->add_action( 'after_setup_theme', $theme, 'set_theme_support');
    $this->loader->add_action( 'after_setup_theme', $theme, 'set_image_sizes');

    $cpt = new Avant_Folio_CPT();
    $this->loader->add_action( 'init', $cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $cpt, 'set_custom_enter_title' );
    
    $taxonomies = new Avant_Folio_Taxonomies();
    $this->loader->add_action( 'init', $taxonomies, 'register_taxonomies' );
    $this->loader->add_action( 'init', $taxonomies, 'populate_taxonomies' );

    $customFields = new Avant_Folio_Custom_Fields();
    $this->loader->add_action( 'add_meta_boxes', $customFields, 'create_meta_boxes' );
    // $this->loader->add_action( 'save_post', $customFields, 'save_post_work_meta', 10, 2 );
  }

  public function run() {

    $this->loader->run();
  }

  public function get_version() {

    return $this->version;
  }
}