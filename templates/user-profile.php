<div>
  <?php settings_errors(); ?>
  <form method="post" action="options.php">
  
    <?php 
      settings_fields( 'avant-folio_profile' );
      do_settings_sections( 'avant-folio_portfolio' );
      submit_button();
    ?>
    
  </form>
</div>