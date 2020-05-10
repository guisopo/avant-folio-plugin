<?php
/**
 * @package AvantFolio
 */

namespace Includes\Api\Callbacks;

use \Includes\Base\BaseController;

class SanitizationCallbacks extends BaseController
{
  public function textFieldSanitize( $input ) 
  { 
    if( is_array( $input ) ) {
      $output = array();

      foreach ($input as $key=>$value) {
        $output[$key] = sanitize_text_field($value);
        $output[$key] = trim($output[$key], '@');
      }
    } else {
      $output = sanitize_text_field($input);
      $output = trim($output, '@');
    }

    return $output;
  }
}