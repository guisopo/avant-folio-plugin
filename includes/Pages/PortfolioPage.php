<?php
/**
 * @package AvantFolio
 */

namespace Includes\Pages;

use \Includes\Base\BaseController;
use \Includes\Api\SettingsApi;
use \Includes\Api\Callbacks\AdminCallbacks;
use \Includes\Api\Callbacks\SanitizationCallbacks;

class PortfolioPage extends BaseController
{

  public $settings;
  public $callbacks;
  public $callbacks_sanitization;
  public $adminPages = array();

  public function register() 
  {
    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();
    $this->callbacks_sanitization = new SanitizationCallbacks();

    $this->setPages();
        
    $this->setSettings();
    $this->setSections();
    $this->setFields();

    $this->settings
         ->addPages( $this->adminPages )
         ->withSubPage()
         ->register();
  }

  public function setPages() 
  {
    $this->adminPages = array(
      // User Profile
      array(
        'page_title' => 'Avant Folio Page',
        'menu_title' => 'Portfolio',
        'capability' => 'manage_options',
        'menu_slug'  => 'avant-folio_portfolio',
        'callback'   => array( $this->callbacks, 'adminPortfolio' ),
        'icon_url'   => 'dashicons-admin-customizer',
        'position'   => 2
      )
    );
  }

  public function setSettings()
  {
    $args = array(
      array(
        'option_group' => 'avant-folio_profile',
        'option_name'  => 'avant_folio_options_profile',
        'callback'     => array( $this->callbacks_sanitization, 'textFieldSanitize' )
      )
    );

    $this->settings->setSettings( $args );
  }

  public function setSections()
  {
    $args = array(
      array(
        'id'        =>  'avant-folio_artist-profile',
        'title'     =>  'Artist Profile',
        'callback'  =>  array( $this->callbacks, 'adminSection' ),
        'page'      =>  'avant-folio_portfolio'
      )
    );

    $this->settings->setSections( $args );
  }

  public function setFields()
  {
    $args = array(
      array(
        'id'        =>  'first_name',
        'title'     =>  'Name:',
        'callback'  =>  array( $this->callbacks, 'inputTextField' ),
        'page'      =>  'avant-folio_portfolio',
        'section'   =>  'avant-folio_artist-profile',
        'args'      =>  array(
          'label_for' => 'first_name',
          'option_name' => 'avant_folio_options_profile',
          'class'     => 'post-attributes-label',
          'placeholder' => 'Name'
        )
      ),
      array(
        'id'        =>  'last_name',
        'title'     =>  'Last Name:',
        'callback'  =>  array( $this->callbacks, 'inputTextField' ),
        'page'      =>  'avant-folio_portfolio',
        'section'   =>  'avant-folio_artist-profile',
        'args'      =>  array(
          'label_for' => 'last_name',
          'option_name' => 'avant_folio_options_profile',
          'class'     => 'post-attributes-label',
          'placeholder' => 'Last Name'
        )
      ),
      array(
        'id'        =>  'facebook',
        'title'     =>  '<span class="dashicons dashicons-facebook"></span>',
        'callback'  =>  array( $this->callbacks, 'inputTextField' ),
        'page'      =>  'avant-folio_portfolio',
        'section'   =>  'avant-folio_artist-profile',
        'args'      =>  array(
          'label_for' => 'facebook',
          'option_name' => 'avant_folio_options_profile',
          'class'     => 'post-attributes-label',
          'placeholder' => '@facebook_user'
        )
      ),
      array(
        'id'        =>  'twitter',
        'title'     =>  '<span class="dashicons dashicons-twitter"></span>',
        'callback'  =>  array( $this->callbacks, 'inputTextField' ),
        'page'      =>  'avant-folio_portfolio',
        'section'   =>  'avant-folio_artist-profile',
        'args'      =>  array(
          'label_for' => 'twitter',
          'option_name' => 'avant_folio_options_profile',
          'class'     => 'post-attributes-label',
          'placeholder' => '@twitter_user'
        )
      ),
      array(
        'id'        =>  'instagram',
        'title'     =>  '<span class="dashicons dashicons-instagram"></span>',
        'callback'  =>  array( $this->callbacks, 'inputTextField' ),
        'page'      =>  'avant-folio_portfolio',
        'section'   =>  'avant-folio_artist-profile',
        'args'      =>  array(
          'label_for' => 'instagram',
          'option_name' => 'avant_folio_options_profile',
          'class'     => 'post-attributes-label',
          'placeholder' => '@instagram_user'
        )
      )
    );

    $this->settings->setFields( $args );
  }

  public function remove_menu_pages() 
  {
    remove_menu_page( 'index.php' );
    remove_menu_page( 'edit.php' );
    remove_menu_page( 'edit-comments.php' );
  }
}