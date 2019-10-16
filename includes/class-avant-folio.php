<?php

class Avant_Folio {
  
  protected $loader;
  protected $plugin_slug;
  protected $version;

  public function __construct() {

    $this->plugin_slug = 'avant_folio';
    $this->version     = '0.1.0';

    $this->load_dependencies();
    $this->set_admin_hooks();
    $this->set_works_cpt();
    $this->set_sketches_cpt();
    $this->set_texts_cpt();
    $this->set_exhibitions_cpt();
    $this->set_gallery();
  }

  private function load_dependencies() {

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-admin-settings.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-cpt.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-taxonomies.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-custom-fields.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-custom-columns.php';
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-avant-folio-gallery.php';

    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'theme/class-avant-folio-theme.php';

    require_once plugin_dir_path( __FILE__ ) . 'class-avant-folio-loader.php';
    $this->loader = new Avant_Folio_Loader();
  }

  private function set_admin_hooks() {

    $admin = new Avant_Folio_Admin($this->version);
    $this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_menu', $admin, 'add_menu_pages' );
    $this->loader->add_action( 'admin_menu', $admin, 'remove_menu_pages' );

    $settings = new Avant_Folio_Admin_Settings();
    $this->loader->add_action( 'admin_init', $settings, 'register_settings' );

    $theme = new Avant_Folio_Theme();
    $this->loader->add_action( 'after_setup_theme', $theme, 'set_theme_support');
    $this->loader->add_action( 'after_setup_theme', $theme, 'set_image_sizes');
  }

  private function set_works_cpt() {
    // Works CPT
    $works_cpt_args = array(
      'cpt_name'     => 'works',
      'cpt_supports' => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'     => 'dashicons-visibility'
    );

    $works_cpt = new Avant_Folio_CPT( $works_cpt_args );

    $this->loader->add_action( 'init', $works_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $works_cpt, 'set_custom_enter_title' );

    // Works Taxonomies
    $works_cpt_taxonomies = array(
      array(
        'cpt'           => $works_cpt_args['cpt_name'],
        'id'            => 'work_type',
        'plural_name'   => 'Types of Work',
        'singular_name' => 'Type of Work',
        'terms'         => [ 'painting', 'drawing', 'sculpture', 'ceramic', 'photography', 'collage', 'video', 'performance', 'installation', '3D Art']
      ),
      array(
        'cpt'           => $works_cpt_args['cpt_name'],
        'id'            => 'date_completed',
        'plural_name'   => 'Dates',
        'singular_name' => 'Date',
        'show_ui'       => false
      )
    );
    
    $this->set_cpt_taxonomies($works_cpt_taxonomies);

    // Works Columns
    $works_cpt_columns = array(
      'cb'              =>  '',
      'image'           =>  __('Image'),
      'title'           =>  __('Title'),
      'work_type'       =>  __('Work Type'),
      'date_completed'  =>  __('Date Completed'),
      'date'            =>  __('Date Published'),
    );

    $works_cpt_custom_columnst = array(
      'work_type' => array(
        'sort_id' => 'type'
      ),
      'date_completed' => array(
        'sort_id' => 'date'
      )
    );

    $this->set_cpt_columns($works_cpt_args['cpt_name'], $works_cpt_columns, $works_cpt_custom_columnst);

    // Works Metaboxes (Info)
    $work_info_metabox = array(
      'id'       => 'work-information',
      'title'    => esc_html__( 'Work Details', 'string' ),
      'callback' => 'render_cf',
      'screen'   => $works_cpt_args['cpt_name'],
      'meta-key' => 'avant_folio_work_info',
    );

    $this->set_cpt_metaboxes( $work_info_metabox);
  }

  public function set_cpt_taxonomies($cpt_taxonomies) {

    foreach ( $cpt_taxonomies as $cpt_taxonomy ) {
      $taxonomy = new Avant_Folio_Taxonomies( $cpt_taxonomy );
      $this->loader->add_action( 'init', $taxonomy, 'register_taxonomy' );
    }
  }

  public function set_cpt_columns($cpt_name, $cpt_columns, $cpt_custom_columnst) {

    $class_cpt_custom_columns = new Avant_Folio_Custom_Columns( $cpt_name, $cpt_columns, $cpt_custom_columnst );

    $this->loader->add_filter( 'manage_' . $cpt_name . '_posts_columns', $class_cpt_custom_columns, 'add_custom_columns' );
    $this->loader->add_action( 'manage_' . $cpt_name . '_posts_custom_column', $class_cpt_custom_columns, 'manage_custom_columns', 10, 2 );
    $this->loader->add_filter( 'manage_edit-' . $cpt_name . '_sortable_columns', $class_cpt_custom_columns, 'set_sortable_columns');
    $this->loader->add_action( 'pre_get_posts', $class_cpt_custom_columns, 'set_posts_orderby' );
  }

  public function set_cpt_metaboxes( $metaboxes_args ) {

    $cpt_metaboxes = new Avant_Folio_Custom_Fields( $metaboxes_args );

    $this->loader->add_action( 'add_meta_boxes', $cpt_metaboxes, 'create_meta_boxes' );
    $this->loader->add_action( 'save_post', $cpt_metaboxes, 'save_post_work_meta', 10, 2 );
  }

  public function set_exhibitions_cpt() {

    // Exhibition CPT
    $exhibitions_cpt_args = array(
      'cpt_name'       => 'exhibitions',
      'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'       => 'dashicons-awards',
      'cpt_taxonomies' => array()
    );

    $exhibitions_cpt = new Avant_Folio_CPT( $exhibitions_cpt_args );

    $this->loader->add_action( 'init', $exhibitions_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $exhibitions_cpt, 'set_custom_enter_title' );
  }

  public function set_texts_cpt() {

    // Text CPT
    $texts_cpt_args = array(
      'cpt_name'       => 'texts',
      'cpt_supports'   => array( 'title', 'editor', 'excerpts', 'thumbnail', 'revisions' ),
      'cpt_icon'       => 'dashicons-edit',
      'cpt_taxonomies' => array()
    );

    $texts_cpt = new Avant_Folio_CPT( $texts_cpt_args );

    $this->loader->add_action( 'init', $texts_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $texts_cpt, 'set_custom_enter_title' );
  }

  public function set_sketches_cpt() {

    // Sketches CPT
    $sketches_cpt_args = array(
      'cpt_name'       => 'sketches',
      'cpt_supports'   => array( 'title', 'thumbnail', 'revisions', 'post-formats' ),
      'cpt_icon'       => 'dashicons-art',
      'cpt_taxonomies' => array()
    );

    $sketches_cpt = new Avant_Folio_CPT( $sketches_cpt_args );

    $this->loader->add_action( 'init', $sketches_cpt, 'register_cpt' );
    $this->loader->add_filter( 'enter_title_here', $sketches_cpt, 'set_custom_enter_title' );
  }

  public function set_gallery() {
    
    $gallery = new Avant_Folio_Gallery();

    $this->loader->add_action( 'add_meta_boxes', $gallery, 'add_metabox' );
    $this->loader->add_action( 'admin_enqueue_scripts', $gallery, 'enqueue_scripts' );
    // $this->loader->add_action( 'save_post', $gallery, 'save_images' );
  }

  public function run() {

    $this->loader->run();
  }

  public function get_version() {

    return $this->version;
  }
}