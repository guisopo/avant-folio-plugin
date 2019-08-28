<?php
  $meta_value = get_post_meta( $post->ID, '_folio_work_meta_key', true );
  
  foreach( $meta_value as $key => $value ) {
    ${'work_'.$key} = ( ! empty ( $value ) ) ? $value : '';
  }

  $taxterms = get_terms( 'work_type', array( 'get' => 'all' ) );
?>
  <p>Here you can fill the details of this work. Remember to click the <b>Publish</b> button on the right to save the data.</p>
  <!-- Work Category -->
  <p>
    <label class="post-attributes-label" for="folio_work_category">Category:</label>
    <select id="work_category" name="folio_work[category]">
      <?php 
        foreach( $taxterms as $term ){
          $selected = selected( $work_category, $term->name );
          echo '<option value="'. $term->name .'" '. $selected .'>'. $term->name .'</option>';
        }
      ?>
    </select>
  </p>
  <!-- Work Year -->
  <p>
    <label class="post-attributes-label" for="folio_work_year">Year:</label>
    <input
      type="text"
      name="folio_work[year]"
      placeholder="<?php echo date('Y') ?>"
      maxlength="4"
      pattern="[0-9]{4,4}"
      size="4"
      value="<?php echo esc_attr( $work_year ); ?>"
    >
  </p>
  <!-- Work Material -->
  <p>
    <label class="post-attributes-label" for="folio_work_material">Material:</label>
    <input
      type="text"
      name="folio_work[material]"
      placeholder="Material used"
      size="20"
      value="<?php echo esc_attr( $work_material ); ?>"
    >
  </p>
  <!-- Work Material -->
  <p>
    <label class="post-attributes-label" for="folio_work_technique">Technique:</label>
    <input
      type="text"
      name="folio_work[technique]"
      placeholder="Technique used"
      size="20"
      value="<?php echo esc_attr( $work_technique ); ?>"
    >
  </p>
  <!-- Work Dimensions -->
  <p>
    <label class="post-attributes-label" for="folio_work_dimensions">Dimensions:</label>
    <input
      type="text"
      name="folio_work[dimensions]"
      placeholder="height x width x depth"
      size="20"
      value="<?php echo esc_attr( $work_dimensions ); ?>"
    >
    <select id="work_units" name="folio_work[units]">
      <option value="none" <?php selected( $work_units, 'none' ); ?>>none</option>
      <option value="mm" <?php selected( $work_units, 'mm' ); ?>>mm</option>
      <option value="cm" <?php selected( $work_units, 'cm' ); ?>>cm</option>
      <option value="m" <?php selected( $work_units, 'm' ); ?>>m</option>
    </select>
  </p>
  <!-- Work Media -->
  <p>
    <label class="post-attributes-label" for="folio_work_media">Media description:</label>
    <input
      type="text"
      name="folio_work[media]"
      placeholder="Video (Digital Betacam and DVD)"
      size="20"
      value="<?php echo esc_attr( $work_media ); ?>"
    >
  </p>
  <!-- Work Credits -->
  <p>
    <label class="post-attributes-label" for="folio_work_credits">Credits:</label>
    <textarea
      name="folio_work[credits]"
      rows="1"
      cols="20"
      placeholder="Director, camera, editing, performance..."
    ><?php 
      echo esc_attr( $work_credits ); 
    ?></textarea>
  </p>
  <!-- Work Duration -->
  <p>
    <label class="post-attributes-label" for="folio_work_duration">Duration:</label>
    <input
      type="text"
      name="folio_work[duration]"
      placeholder="00"
      maxlength="2"
      pattern="[0-9]{2,2}"
      size="2"
      value="<?php echo esc_attr( $work_duration ); ?>"
    >
    <span>:</span>
    <input
      type="text"
      name="folio_work[duration]"
      placeholder="00"
      maxlength="2"
      pattern="[0-60]{2,2}"
      size="2"
      value="<?php echo esc_attr( $work_duration ); ?>"
    >
    <span>:</span>
    <input
      type="text"
      name="folio_work[duration]"
      placeholder="00"
      maxlength="2"
      pattern="[0-9]{2,2}"
      size="2"
      value="<?php echo esc_attr( $work_duration ); ?>"
    >
  </p>
  <!-- Work Description -->
  <p>
    <label class="post-attributes-label" for="folio_work_description">Description:</label>
    <textarea
      name="folio_work[description]"
      rows="1"
      cols="50"
      placeholder="Description of the work"
    ><?php 
      echo esc_attr( $work_description ); 
    ?></textarea>
  </p>