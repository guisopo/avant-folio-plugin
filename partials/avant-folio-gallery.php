<?php
$post_type = get_post_type( $post );
$meta_value = get_post_meta( $post->ID, '_avant_folio_work_info_key', true );
$gallery = $meta_value['gallery'];
$images = explode(",", $gallery );
?>

<p>Here you can add the images of the work.</p>
<table id="avant_folio_gallery" class="table">
	<tr>
		<td>
			<!-- Creating a dynamic ID using the metabox ID for JavaScript-->
			<ul id="avant_folio_gallery_list" class="avant_folio_gallery_list">
				<?php 
				
				// If there is any ID, create the image for it
				if( count( $images ) > 0 && $images[0] != '' ) {
					foreach ( $images as $attachment_id ) {
							
						// Create a LI elememnt
						$output = '<li tabindex="0" role="checkbox" aria-label="' . get_the_title( $attachment_id ) . '" aria-checked="true" data-id="' . $attachment_id . '" class="attachment save-ready selected details">';
						// Create a container for the image. (Copied from the WP Media Library Modal to use the same styling)
						$output .= '<div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">';
							$output .= '<div class="thumbnail">';
								$output .= '<div class="centered">';
									// Get the URL to that image thumbnail
									$output .= '<img src="'  . wp_get_attachment_thumb_url( $attachment_id ) . '" draggable="false" alt="">';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
						// Add the button to remove this image if wanted (we set the data-gallery to target the correct gallery if there are more than one)
						$output .= '<button type="button" data-gallery="#' . $this->id . '_avant_folio_gallery" class="button-link check remove-avant-folio-gallery-image" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>';
						$output .= '</li>';
						echo $output;
					}         
				}               
				?>
			</ul>
			<!-- Button used to open the WordPress Media Library Modal -->
			<button id="avant_folio_gallery_add_images" type="button" class="button button-primary" data-gallery="#<?php echo $this->id; ?>_sortable_wordpress_gallery"><?php _e( 'Add Images', 'your_text_domain' ); ?></button>
		</td>
	</tr>
</table>