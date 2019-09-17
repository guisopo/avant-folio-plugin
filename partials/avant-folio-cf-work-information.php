<?php
  $meta_value = get_post_meta( $post->ID, '_avant_folio_work_info_key', true );

  foreach( $meta_value as $key => $value ) {
    ${'work_'.$key} = ( ! empty ( $value ) ) ? $value : '';
  }

  $work_types_taxonomies = get_terms( 'work_type', array( 'get' => 'all' ) );
?>
  <p>Here you can fill the details of this work. Remember to click the <b>Publish</b> button on the right to save the data.</p>
  <!-- Work Type -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_work_type">Category:</label>
    <select id="work_type" name="avant_folio_work_info[work_type]">
      <?php 
        foreach( $work_types_taxonomies as $term ){
          $selected = selected( $work_work_type, $term->name );
          echo '<option value="'. $term->name .'" '. $selected .'>'. $term->name .'</option>';
        }
      ?>
    </select>
  </p>
  <!-- Work Year -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_date_completed">Year:</label>
    <input
      type="text"
      name="avant_folio_work_info[date_completed]"
      placeholder="<?php echo date('Y') ?>"
      maxlength="4"
      pattern="[0-9]{4,4}"
      size="4"
      value="<?php echo esc_attr( $work_date_completed ); ?>"
    >
  </p>
  <!-- Work Material -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_material">Material:</label>
    <input
      type="text"
      name="avant_folio_work_info[material]"
      placeholder="Material used"
      size="20"
      value="<?php echo esc_attr( $work_material ); ?>"
    >
  </p>
  <!-- Work Technique -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_technique">Technique:</label>
    <input
      type="text"
      name="avant_folio_work_info[technique]"
      placeholder="Technique used"
      size="20"
      value="<?php echo esc_attr( $work_technique ); ?>"
    >
  </p>
  <!-- Work Dimensions -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_dimensions">Dimensions:</label>
    <input
      type="text"
      name="avant_folio_work_info[dimensions]"
      placeholder="height x width x depth"
      size="20"
      value="<?php echo esc_attr( $work_dimensions ); ?>"
    >
    <select id="work_units" name="avant_folio_work_info[units]">
      <option value="none" <?php selected( $work_units, 'none' ); ?>>none</option>
      <option value="mm" <?php selected( $work_units, 'mm' ); ?>>mm</option>
      <option value="cm" <?php selected( $work_units, 'cm' ); ?>>cm</option>
      <option value="m" <?php selected( $work_units, 'm' ); ?>>m</option>
    </select>
  </p>
  <!-- Work Media -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_media">Media description:</label>
    <input
      type="text"
      name="avant_folio_work_info[media]"
      placeholder="Video (Digital Betacam and DVD)"
      size="20"
      value="<?php echo esc_attr( $work_media ); ?>"
    >
  </p>
  <!-- Work Credits -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_credits">Credits:</label>
    <textarea
      name="avant_folio_work_info[credits]"
      rows="1"
      cols="20"
      placeholder="Director, camera, editing, performance..."
    ><?php 
      echo esc_attr( $work_credits ); 
    ?></textarea>
  </p>
  <!-- Work Duration -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_duration">Duration:</label>
    <input
      type="number"
      name="avant_folio_work_info[duration_hours]"
      placeholder="00"
      min="0"
      value="<?php echo esc_attr( $work_duration_hours ); ?>"
    >
    <span>:</span>
    <input
      type="number"
      name="avant_folio_work_info[duration_minutes]"
      placeholder="00"
      min="0"
      max="59"
      value="<?php echo esc_attr( $work_duration_minutes ); ?>"
    >
    <span>:</span>
    <input
      type="number"
      name="avant_folio_work_info[duration_seconds]"
      placeholder="00"
      min="0"
      max="59"
      value="<?php echo esc_attr( $work_duration_seconds ); ?>"
    >
  </p>
  <!-- Work Description -->
  <p>
    <label class="post-attributes-label" for="avant_folio_work_info_description">Description:</label>
    <textarea
      name="avant_folio_work_info[description]"
      rows="1"
      cols="50"
      placeholder="Description of the work"
    ><?php 
      echo esc_attr( $work_description ); 
    ?></textarea>
  </p>