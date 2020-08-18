<?php
  $meta_value = ( get_post_meta( $post->ID, '_avant_folio_exhibition_info_key', true ) ) ?: array();
  
  foreach( $meta_value as $key => $value ) {
    ${'exhibition_'.$key} = $value ?: '';
  }

  $exhibition_types_taxonomies = get_terms( 'exhibition_type', array( 'get' => 'all' ) );

  settings_errors();
?>

<input  
  type="hidden" 
  name="avant_folio_exhibition_info[title]" 
  id="exhibition_title_key"
>

<div id="af-exhibition-info-form" class="cf-section">
    
  <div class="cf-column">

    <div class="cf-wrapper">

      <!-- Exhibition Venue -->
      <div class="cf-box cf-box--xl">
        <label class="post-attributes-label cf-box__label" for="avant_folio_exhibition_info_material">Venue:</label>
        <input
          class="cf-box__input"
          type="text"
          name="avant_folio_exhibition_info[venue]"
          placeholder="Gallery, offspace, museum..."
          size="20"
          value="<?php  echo isset($exhibition_venue) ? esc_attr( $exhibition_venue ): ''; ?>"
        >
      </div>
      
      <!-- Exhibition Year -->
      <div class="cf-box cf-box--s">
        <label class="post-attributes-label cf-box__label" for="avant_folio_exhibition_info_date_completed">Year:</label>
        <input
          id="date_completed"
          class="cf-box__input"
          type="text"
          required
          name="avant_folio_exhibition_info[date_completed]"
          placeholder="<?php echo date('Y') ?>"
          maxlength="4"
          pattern="[0-9]{4,4}"
          size="4"
          value="<?php echo isset($exhibition_date_completed) ? esc_attr( $exhibition_date_completed ): ''; ?>"
        >
      </div>
      
    </div>    

    <!-- Exhibition URL -->
    <div class="cf-box">
      <label class="post-attributes-label cf-box__label" for="avant_folio_exhibition_info_url">Exhibition url:</label>
      <input
        class="cf-box__input cf-box__input--xl"
        type="text"
        name="avant_folio_exhibition_info[url]"
        placeholder="https://www.thegallery.com/yourexhibition"
        size="20"
        value="<?php echo isset($exhibition_url) ? esc_attr( $exhibition_url ): ''; ?>"
      >
    </div>


  </div>

  <div class="cf-column">

    <!-- exhibition Description -->
    <div class="cf-box cf-box--flex">
      <label class="post-attributes-label cf-box__label" for="avant_folio_exhibition_info_description">Information:</label>
      <textarea
        class="cf-box__textarea"
        name="avant_folio_exhibition_info[description]"
        placeholder="Solo show, group show, upcoming..."
      ><?php 
        echo isset($exhibition_description) ? esc_attr( $exhibition_description ): '';
      ?></textarea>
    </div>

  </div>

</div>