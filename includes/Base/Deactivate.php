<?php
/**
 * @package AvantFolio
 */

namespace Includes\Base;

class Deactivate
{
  public static function deactivate() {
    // Refresh DB in order to read the new information
    flush_rewrite_rules();
  }
}