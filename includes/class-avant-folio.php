<?php

class AvantFolio {
  protected $loaders;
  protected $plugin_slug;
  protected $version;

  public function __construct() {
    $this->plugin_slug = 'avant_folio';
    $this->version = '0.1.0';
  }

  private function load_dependencies() {

  }

  private function define_admin_hooks() {

  }

  private function run() {

  }

  public function get_version() {
    return $this->version;
  }
}