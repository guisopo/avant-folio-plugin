<?php
/**
 * @package AvantFolio
 */

namespace Includes\Pages;

use \Includes\Base\BaseController;
use \Includes\Api\SettingsApi;
use \Includes\Api\Callbacks\AdminCallbacks;
use \Includes\Api\Callbacks\SanitizationCallbacks;

class CVSubpage extends BaseController
{
  public $callbacks;
  public $subpages = array();

  public function register() 
  {
    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();

    $this->setSubpages();

    $this->settings->addSubpages( $this->subpages )->register();
  }

  public function setSubpages() 
  {
    $this->subpages = array(
      // Portfolio CV
      array(
        'parent_slug' =>  'avant-folio_portfolio',
        'page_title'  =>  'Curriculum Vitae',
        'menu_title'  =>  'CV',
        'capability'  =>  'manage_options',
        'menu_slug'   =>  'avant_folio_cv',
        'callback'    =>  array( $this->callbacks, 'adminCV' )
      )
    );
  }
}