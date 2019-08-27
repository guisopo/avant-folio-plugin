<?php
class Avant_Folio_Settings {

  public function __construct() {
  }

  public function register_settings() {
    register_setting( 
      'avant-folio-settings-group', 
      'avant_folio_options', 
      array($this, 'sanitize_settings')
    );
  }

  public function sanitize_settings( $input ) {
    $input['option_name']      = sanitize_text_field( $input['option_name'] );
    $input['option_last_name'] = sanitize_text_field( $input['option_last_name'] );
    $input['option_facebook']  = sanitize_text_field( $input['option_facebook'] );
    $input['option_twitter']   = $this->sanitize_at_symbol( $input['option_twitter'] );
    $input['option_instagram'] = $this->sanitize_at_symbol( $input['option_instagram'] );
    
    return $input;
  }

  public function sanitize_at_symbol( $input ){
    $output = sanitize_text_field( $input );
    $output = str_replace( '@', '', $output );
    return $output;
  }
}