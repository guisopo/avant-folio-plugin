<?php
$post_type = get_post_type( $post );
$meta_value = get_post_meta( $post->ID, '_avant_folio_work_info_key', true );

$gallery = $meta_value['gallery'];
$images = explode(",", $gallery );
?>

<div id="af-gallery" class="af-gallery">
	
	<!-- Creating a dynamic ID using the metabox ID for JavaScript-->
	<ul id="af-gallery__list" class="af-gallery__list">
		<?php 
		
			// If there is any ID, create the image for it
			if( count( $images ) > 0 && $images[0] != '' ) {
				foreach ( $images as $attachment_id ) {
						
					// Create a LI elememnt
					$output = '<li aria-label="' . get_the_title( $attachment_id ) . '" data-id="' . $attachment_id . '" class="af-gallery__item">';
						// Create a container for the image. (Copied from the WP Media Library Modal to use the same styling)
						$output .= '<div class="af-gallery__image-container">';
									// Get the URL to that image thumbnail
									$output .= '<img class="af-gallery__image" src="'  . wp_get_attachment_thumb_url( $attachment_id ) . '" draggable="false" alt="">';
							$output .= '</div>';
						// Add the button to remove this image if wanted (we set the data-gallery to target the correct gallery if there are more than one)
						$output .= '<button type="button" data-gallery="#' . $this->id . '_avant_folio_gallery" class="js-af-button-remove_image af-gallery__list-button af-gallery__list-button--remove" tabindex="0"><span class="dashicons dashicons-no-alt af-button-icon"></span><span class="screen-reader-text">Deselect</span></button>';
						// Add the button to remove this image if wanted (we set the data-gallery to target the correct gallery if there are more than one)
						$output .= '<button type="button" class="js-af-button-set_featured_image af-gallery__list-button af-gallery__list-button--featured" tabindex="0"><span class="dashicons dashicons-star-filled af-button-icon"></span><span class="screen-reader-text">Select Featured</span></button>';
					$output .= '</li>';
					echo $output;
				}         
			}   

		?>

		<!-- Button used to open the WordPress Media Library Modal -->
		<li class="af-gallery__item af-gallery__item--upload-image af-unsortable">

			<div class="af-gallery__button-container">
				
					<div class="add-image__icon">
						<svg width="30" height="30" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false">
							<path fill="none" d="M0 0h24v24H0V0z"></path><g><path d="M20 4v12H8V4h12m0-2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 9.67l1.69 2.26 2.48-3.1L19 15H9zM2 6v14c0 1.1.9 2 2 2h14v-2H4V6H2z"></path></g>
						</svg>
					</div>

					<button id="js-af-button-add_image" type="button" class="add-image__button">
						<svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-upload" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
							<path d="M8 14V8H5l5-6 5 6h-3v6H8zm-2 2v-6H4v8h12.01v-8H14v6H6z"></path>
						</svg>
						Upload
					</button>
					
			</div>

		</li>
	</ul>

	<div class="af-gallery__save-button">
		<?php submit_button(); ?>
	</div>
</div>

<!-- Button used to update Selection -->
