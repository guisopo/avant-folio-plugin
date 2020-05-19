<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api\Callbacks;

use \Includes\Api\BaseController;

class AdminCallbacks extends BaseController
{
  public function adminPortfolio() 
  {
    return require_once( $this->plugin_path . 'templates/user-profile.php' );
  }

  public function adminCV() 
  {
    return require_once( $this->plugin_path . 'templates/avant-folio-cv.php' );
  }

  public function adminSection()
  {
    echo 'Here you can add your profile information.';
  }


  public function inputTextField($args) 
  {
    $optionName  = $args['option_name'];
    $label       = $args['label_for'];
    $placeholder = $args['placeholder'];
    $classes     = $args['class'];

    $name        = ''.$optionName . '[' . $label .']';

    $data = get_option( $optionName );
    $value = esc_attr( $data[$label] );

    echo '<p>
            <input
              type="text" 
              class="regular-text"
              id="'. $label .'"
              name="'. $name .'" 
              placeholder="'. $placeholder .'"
              size="20"
              value="'. $value .'"
            >
          </p>';
  }

}