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
    $this->define_custom_post();
  }

  private function load_dependencies() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin-settings.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-cpt.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-taxonomies.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-custom-fields.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-custom-columns.php';

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

    $customFields = new Avant_Folio_Custom_Fields();
    $this->loader->add_action( 'add_meta_boxes', $customFields, 'create_meta_boxes' );
    $this->loader->add_action( 'save_post', $customFields, 'save_post_work_meta', 10, 2 );

    
  }

  private function define_custom_post() {

    // Works CPT
    $works_cpt_args = array(
      'cpt_name' => 'works',
      'cpt_supports' => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon' => 'dashicons-visibility',
      'cpt_taxonomies' => array()
    );

    $works_cpt_args['cpt_taxonomies'][] = array(
      'cpt' => $works_cpt_args['cpt_name'],
      'id' => 'work_type',
      'plural_name' => 'Types of Work',
      'singular_name' => 'Type of Work',
      'terms' => [ 'painting', 'drawing', 'sculpture', 'photography', 'video', 'performance', 'installation']
    );

    $works_cpt_args['cpt_taxonomies'][] = array(
      'cpt' => $works_cpt_args['cpt_name'],
      'id' => 'date_completed',
      'plural_name' => 'Dates',
      'singular_name' => 'Date'
    );

    $works_cpt_columns = array(
      'cb'              =>  $columns['cb'],
      'image'           =>  __('Image'),
      'title'           =>  __('Title'),
      'work_type'       =>  __('Work Type'),
      'date_completed'  =>  __('Date Completed'),
      'date'            =>  __('Date Published'),
    );

    $works_cpt_custom_columnst = array(
      'work_type' => array(
        'sort_id'   => 'type'
      ),
      'date_completed' => array(
        'sort_id'   => 'date'
      )
    );

    $works_cpt = new Avant_Folio_CPT( $works_cpt_args, $works_cpt_columns, $works_cpt_custom_columnst );
    $this->loader->add_action( 'init', $works_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $works_cpt, 'set_custom_enter_title' );

    // Exhibition CPT
    $exhibitions_cpt_args = array(
      'cpt_name' => 'exhibitions',
      'cpt_supports' => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon' => 'dashicons-awards',
      'cpt_taxonomies' => array()
    );

    $exhibitions_cpt = new Avant_Folio_CPT( $exhibitions_cpt_args );
    $this->loader->add_action( 'init', $exhibitions_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $exhibitions_cpt, 'set_custom_enter_title' );
  }

  public function run() {

    $this->loader->run();
  }

  public function get_version() {

    return $this->version;
  }
}