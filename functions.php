<?php
/**
 * Theme bootstrap for Mia Aesthetics.
 *
 * Loads all core theme features and helper modules.
 * Each include is responsible for a specific area of theme functionality.
 *
 * @package Mia_Aesthetics
 */

// Core foundation.
require_once get_template_directory() . '/inc/theme-support.php';
require_once get_template_directory() . '/inc/state-abbreviations.php';

// Utilities.
require_once get_template_directory() . '/inc/media-helpers.php';
require_once get_template_directory() . '/inc/cache-helpers.php';

// Template utilities.
require_once get_template_directory() . '/inc/template-helpers.php';
require_once get_template_directory() . '/inc/breadcrumb-display.php';
require_once get_template_directory() . '/inc/cta-display.php';
require_once get_template_directory() . '/inc/social-media-helpers.php';

// Feature modules.
require_once get_template_directory() . '/inc/featured-image-column.php';

// Query modifications.
require_once get_template_directory() . '/inc/queries.php';

// Navigation.
require_once get_template_directory() . '/inc/menu-helpers.php';
require_once get_template_directory() . '/inc/menus.php';

// SEO and metadata.
require_once get_template_directory() . '/inc/schema.php';

// Asset management (load last).
require_once get_template_directory() . '/inc/enqueue.php';

/**
 * Allow SVG uploads in WordPress Media Library.
 *
 * This adds SVG support to the allowed file types for upload.
 * SVGs are useful for logos, icons, and scalable graphics.
 *
 * Security Note: SVGs can contain JavaScript, so only allow uploads
 * from trusted users (administrators/editors).
 *
 * @param array<string, string> $mimes Array of allowed mime types.
 * @return array<string, string> Modified mime types with SVG support.
 */
function mia_aesthetics_allow_svg_uploads( $mimes ) {
	// Only allow SVG uploads for administrators for security.
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}

add_filter( 'upload_mimes', 'mia_aesthetics_allow_svg_uploads' );

/**
 * Fix SVG display in WordPress Media Library.
 *
 * WordPress doesn't generate thumbnails for SVGs by default.
 * This ensures SVGs display properly in the admin media library.
 *
 * @param array<string, mixed> $response   Array of prepared attachment data.
 * @param object               $attachment Attachment object.
 * @param array<string, mixed> $meta       Array of attachment meta data.
 * @return array<string, mixed> Modified response array with SVG display data.
 */
function mia_aesthetics_fix_svg_display( $response, $attachment, $meta ) {
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	unset( $meta );
	if ( 'image' === $response['type'] && 'svg+xml' === $response['subtype'] && class_exists( 'SimpleXMLElement' ) ) {
		if ( is_object( $attachment ) && property_exists( $attachment, 'ID' ) ) {
			$path = get_attached_file( $attachment->ID );
			if ( false !== $path && file_exists( $path ) ) {
				// Use WP_Filesystem API for better security and compatibility.
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once wp_normalize_path( ABSPATH . 'wp-admin/includes/file.php' );
				}
				WP_Filesystem();
				$svg = $GLOBALS['wp_filesystem']->get_contents( $path );
				if ( false !== $svg ) {
					$xml = simplexml_load_string( $svg );
					if ( false !== $xml ) {
						$src    = $response['url'];
						$width  = intval( $xml['width'] );
						$height = intval( $xml['height'] );

						// If width/height not in XML attributes, try viewBox.
						if ( 0 === $width || 0 === $height ) {
							$viewbox = explode( ' ', (string) ( $xml['viewBox'] ?? '' ) );
							if ( count( $viewbox ) === 4 ) {
								$width  = intval( $viewbox[2] );
								$height = intval( $viewbox[3] );
							}
						}

						// Fallback dimensions if still not found.
						if ( 0 === $width ) {
							$width = 150;
						}

						if ( 0 === $height ) {
							$height = 150;
						}

						$response['image']              = array(
							'src'    => $src,
							'width'  => $width,
							'height' => $height,
						);
						$response['thumb']              = array(
							'src'    => $src,
							'width'  => $width,
							'height' => $height,
						);
						$response['sizes']['thumbnail'] = array(
							'height'      => $height,
							'width'       => $width,
							'url'         => $src,
							'orientation' => $height > $width ? 'portrait' : 'landscape',
						);
					}
				}
			}
		}
	}

	return $response;
}

add_filter( 'wp_prepare_attachment_for_js', 'mia_aesthetics_fix_svg_display', 10, 3 );

/**
 * Add SVG to allowed file types check.
 *
 * This ensures WordPress recognizes SVG files as valid images
 * for security and validation purposes.
 *
 * @param array<string, mixed>  $data     Array of file data.
 * @param string                $file     File path.
 * @param string                $filename Original filename.
 * @param array<string, string> $mimes    Array of allowed mime types.
 * @return array<string, mixed> Modified file data array.
 */
function mia_aesthetics_check_svg_filetype( $data, $file, $filename, $mimes ) {
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	unset( $file );
	if ( isset( $data['ext'] ) && '' !== $data['ext'] && isset( $data['type'] ) && '' !== $data['type'] ) {
		return $data;
	}

	$filetype = wp_check_filetype( $filename, $mimes );
	if ( 'svg' === $filetype['ext'] ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}

add_filter( 'wp_check_filetype_and_ext', 'mia_aesthetics_check_svg_filetype', 10, 4 );


/**
 * Disable Gravity Forms confirmation anchor scrolling.
 *
 * Prevents Gravity Forms from automatically scrolling the page to the
 * confirmation message or next page after form submission. This allows
 * for better control over the user experience after form submission.
 *
 * @return bool False to disable the anchor scroll behavior.
 */
add_filter( 'gform_confirmation_anchor', '__return_false' );
