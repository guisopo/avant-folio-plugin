<div>
  <form method="post" action="options.php">
  
    <?php settings_fields( 'avant-folio-settings-group' ); ?>
    <?php $avant_folio_options = get_option( 'avant_folio_options' ); ?>

    <h2>User Profile</h2>
    <!-- Name -->
    <p>
      <label class="post-attributes-label" for="avant_folio_options[option_name]">Name:</label>
      <input
        type="text" 
        id="avant_folio_options[option_name]"
        name="avant_folio_options[option_name]" 
        placeholder="Name"
        size="20"
        value="<?php echo esc_attr( $avant_folio_options['option_name']); ?>"
      >
    </p>
    <p>
      <label class="post-attributes-label" for="avant_folio_options[option_last_name]">Last Name:</label>
      <input
        type="text" 
        id="avant_folio_options[option_last_name]"
        name="avant_folio_options[option_last_name]" 
        placeholder="Last name"
        size="20"
        value="<?php echo esc_attr( $avant_folio_options['option_last_name']); ?>"
      >
    </p>
  
    <h2>Social Profiles</h2>
    <!-- Twitter -->
    <p>
      <label class="post-attributes-label" for="avant_folio_options[option_twitter]">
      <span class="dashicons dashicons-twitter"></span>
      </label>
      <input
        type="text" 
        id="avant_folio_options[option_twitter]"
        name="avant_folio_options[option_twitter]" 
        placeholder="@user_account"
        size="20"
        value="<?php echo esc_attr( $avant_folio_options['option_twitter']); ?>"
      >
    </p>
    <!-- Instagram -->
    <p>
      <label class="post-attributes-label" for="avant_folio_options[option_instagram]">
        <span class="dashicons dashicons-instagram"></span>
      </label>
      <input
        type="text" 
        id="avant_folio_options[option_instagram]"
        name="avant_folio_options[option_instagram]" 
        placeholder="@user_account"
        size="20"
        value="<?php echo esc_attr( $avant_folio_options['option_instagram']); ?>"
      >
    </p>
    <!-- Facebook -->
    <p>
      <label class="post-attributes-label" for="avant_folio_options[option_facebook]">
      <span class="dashicons dashicons-facebook"></span>
      </label>
      <input
        type="text" 
        id="avant_folio_options[option_facebook]"
        name="avant_folio_options[option_facebook]" 
        placeholder="@user_account"
        size="20"
        value="<?php echo esc_attr( $avant_folio_options['option_facebook']); ?>"
      >
    </p>
    <p>
      <input type="submit" class="button-primary" value="Save Changes">
    </p>
  </form>
</div>