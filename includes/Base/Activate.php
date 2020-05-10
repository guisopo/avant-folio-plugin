<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

class Activate
{
  public static function activate() {
    // Refresh DB in order to read the new information
    flush_rewrite_rules();
  }
}