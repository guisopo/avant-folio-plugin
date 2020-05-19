<?php
/**
 * @package AvantFolio
 */

namespace Includes;

final class Init
{
  /**
   *  Store all the classes inside an array
   *  @return array full list of classes
   */
  public static function get_services() 
  { 
    return [ 
      Pages\PortfolioPage::class,
      Pages\CVSubpage::class,
      Base\Enqueue::class,
      Base\WorkCpt::class
    ];
  }

  /**
   *  Loop through the classes, initialize them 
   *  and call regitster method if it exists
   */
  public static function register_services() 
  {
    foreach (self::get_services() as $class) {
      $service = self::instantiate( $class );
      if ( method_exists( $service, 'register' ) ) {
        $service->register();
      }
    }
  }

  /**
   *  Initialize the class
   *  @param class class from services array
   *  @return instance new instance of the classe
   */
  private static function instantiate( $class ) 
  {
    $service = new $class();
    return $service;
  }
}


// // Activation
// register_activation_hook( __FILE__, array($plugin, 'activate') );
// // Deactivation
// register_deactivation_hook( __FILE__, array($plugin, 'deactivate') );