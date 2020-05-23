<?php
  $meta_value = ( get_post_meta( $post->ID, '_avant_folio_work_info_key', true ) ) ?: array();
  
  foreach( $meta_value as $key => $value ) {
    ${'work_'.$key} = $value ?: '';
  }

  $work_types_taxonomies = get_terms( 'work_type', array( 'get' => 'all' ) );

  settings_errors();
?>

<input  
  type="hidden" 
  name="avant_folio_work_info[title]" 
  id="work_title_key"
>
<input  
  type="hidden" 
  name="avant_folio_work_type" 
  id="work_type_key"
>
<input  
  type="hidden" 
  name="avant_folio_date_completed" 
  id="date_completed_key"
>

<div class="cf-section">
    
  <div class="cf-column">

    <div class="cf-wrapper">

      <!-- Work Type -->
      <div class="cf-box cf-box--xl">
        <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_work_type">Category:</label>
        <select required id="work_type_select" class="cf-box__select" name="avant_folio_work_info[work_type]" >
          <option value="" disabled selected>Select work type</option>
          <?php 
            foreach( $work_types_taxonomies as $term ){
              $selected = isset($work_work_type) ? selected( $work_work_type, $term->name ) : '';;
              echo '<option value="'. $term->name .'" '. $selected .'>'. $term->name .'</option>';
            }
          ?>
        </select>
      </div>
      <!-- Work Year -->
      <div class="cf-box cf-box--s">
        <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_date_completed">Year:</label>
        <input
          id="date_completed"
          class="cf-box__input"
          type="text"
          required
          name="avant_folio_work_info[date_completed]"
          placeholder="<?php echo date('Y') ?>"
          maxlength="4"
          pattern="[0-9]{4,4}"
          size="4"
          value="<?php echo isset($work_date_completed) ? esc_attr( $work_date_completed ): ''; ?>"
        >
      </div>
      
    </div>

    <!-- Work Material -->
    <div class="cf-box">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_material">Material:</label>
      <input
        class="cf-box__input"
        type="text"
        name="avant_folio_work_info[material]"
        placeholder="Material used"
        size="20"
        value="<?php  echo isset($work_material) ? esc_attr( $work_material ): ''; ?>"
      >
    </div>

    <!-- Work Technique -->
    <div class="cf-box">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_technique">Technique:</label>
      <input
        class="cf-box__input"
        type="text"
        name="avant_folio_work_info[technique]"
        placeholder="Technique used"
        size="20"
        value="<?php  echo isset($work_technique) ? esc_attr( $work_technique ): ''; ?>"
      >
    </div>

    <!-- Work Dimensions -->
    <div class="cf-box">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_dimensions">Dimensions:</label>
      <div class="inputs-wrapper">
        <input
          class="cf-box__input cf-box__input--xl"
          type="text"
          name="avant_folio_work_info[dimensions]"
          placeholder="height x width x depth"
          size="20"
          value="<?php echo isset($work_dimensions) ? esc_attr( $work_dimensions ): ''; ?>"
        >
        <select id="work_units" class="cf-box__select cf-box__select--s" name="avant_folio_work_info[units]">
          <option value="" disabled selected>Units</option>
          <?php
          $units = array( 'mm', 'cm', 'm', 'none' );

          foreach ( $units as $unit ) {
            ?>
              <option value=<?php echo($unit) ?> <?php isset($work_units) ? selected( $work_units, $unit) : ''; ?>><?php echo($unit) ?></option>
            <?php
          }
          ?>
          
        </select>
      </div>
    </div>
    <!-- Work Media -->

    <div class="cf-box">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_media">Media description:</label>
      <input
        class="cf-box__input"
        type="text"
        name="avant_folio_work_info[media]"
        placeholder="Video (Digital Betacam and DVD)"
        size="20"
        value="<?php echo isset($work_media) ? esc_attr( $work_media ): ''; ?>"
      >
    </div>
    
    <div class="cf-wrapper">

      <!-- Work Video URL -->
      <div class="cf-box cf-box--xl">
        <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_url">Video url:</label>
        <input
          class="cf-box__input cf-box__input--xl"
          type="text"
          name="avant_folio_work_info[url]"
          placeholder="https://www.vimeo.com/video"
          size="20"
          value="<?php echo isset($work_url) ? esc_attr( $work_url ): ''; ?>"
        >
      </div>
      
      <!-- Work Duration -->
      <div class="cf-box cf-box--s">
        <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_duration">Duration:</label>
        <div class="inputs-wrapper">
          <input
            class="cf-box__input"
            type="text"
            name="avant_folio_work_info[duration_hours]"
            placeholder="00"
            minlength="2"
            maxlength="2"
            size="1"
            value="<?php echo isset($work_duration_hours) ? esc_attr( $work_duration_hours ): ''; ?>"
          >
          <span><b>:</b></span>
          <input
            class="cf-box__input"
            type="text"
            name="avant_folio_work_info[duration_minutes]"
            placeholder="00"
            minlength="2"
            maxlength="2"
            size="1"
            value="<?php echo isset($work_duration_minutes) ? esc_attr( $work_duration_minutes ): ''; ?>"
          >
          <span><b>:</b></span>
          <input
            class="cf-box__input"
            type="text"
            name="avant_folio_work_info[duration_seconds]"
            placeholder="00"
            minlength="2"
            maxlength="2"
            size="1"
            value="<?php echo isset($work_duration_seconds) ? esc_attr( $work_duration_seconds ): ''; ?>"
          >
        </div>
      </div>

    </div>

  </div>

  <div class="cf-column">

    <!-- Work Description -->
    <div class="cf-box cf-box--flex">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_description">Description:</label>
      <textarea
        class="cf-box__textarea"
        name="avant_folio_work_info[description]"
        placeholder="Description of the work"
      ><?php 
        echo isset($work_description) ? esc_attr( $work_description ): '';
      ?></textarea>
    </div>

    <!-- Work Credits -->
    <div class="cf-box cf-box--flex">
      <label class="post-attributes-label cf-box__label" for="avant_folio_work_info_credits">Credits:</label>
      <textarea
        class="cf-box__textarea"
        name="avant_folio_work_info[credits]"
        placeholder="Director, camera, editing, performance..."
      ><?php
        echo isset($work_credits) ? esc_attr( $work_credits ): '';
      ?></textarea>
    </div>

  </div>

</div>