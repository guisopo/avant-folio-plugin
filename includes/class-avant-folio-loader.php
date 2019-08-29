<?php
class Avant_Folio_Loader {
  
  protected $actions;
  protected $filters;

  public function __construct() {
    $this->actions = $actions;
    $this->filters = $filters;
  }

  public function add_action( $hook, $component, $callback, $priority = null, $arguments = null) {
    !$priority || !$arguments ?
      $this->actions = $this->add( $this->actions, $hook, $component, $callback)
    :
      $this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $arguments);
  }

  public function add_filter( $filter, $component, $callback ) {
    $this->filters = $this->add( $this->filters, $filter, $component, $callback);
  }
  
  private function add( $hooks, $hook, $component, $callback, $priority = null, $arguments = null) {
    $hooks[] = array(
      'hook' => $hook,
      'component' => $component,
      'callback' => $callback,
      'priority' => $priority,
      'arguments' => $arguments
    );

    return $hooks;
  }

  public function run() {
    foreach ( $this->filters as $hook ) {
      add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ) );
    }

    foreach ( $this->actions as $hook ) {
      !$hook['priority'] ?
        add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ) )
      :
        add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['arguments'] );
    }
  }
}