<?php
/**
 * Theme bootstrap for Mia Aesthetics.
 *
 * Loads all core theme features and helper modules.
 * Each include is responsible for a specific area of theme functionality.
 *
 * @package Mia_Aesthetics
 */

// Load helper modules (see inc/ for details).
// 1. CORE FOUNDATION (WordPress features, no dependencies).
require_once get_template_directory() . '/inc/theme-support.php';
// WordPress theme support features (MUST be first).
require_once get_template_directory() . '/inc/state-abbreviations.php';
// US state abbreviation lookup and helper (pure utility).

// 2. UTILITIES (minimal dependencies, used by other modules).
require_once get_template_directory() . '/inc/media-helpers.php';
// Video processing, image handling, gallery utilities.
require_once get_template_directory() . '/inc/cache-helpers.php';
// Cache management and clearing (used by queries).

// 3. TEMPLATE UTILITIES (depends on utilities above).
require_once get_template_directory() . '/inc/template-helpers.php';
// Template/UI helpers (uses state abbreviations).
require_once get_template_directory() . '/inc/breadcrumb-display.php';
// Breadcrumb display control and rendering functions.
require_once get_template_directory() . '/inc/cta-display.php';
// CTA display control based on ACF settings.
require_once get_template_directory() . '/inc/social-media-helpers.php';
// Social media URL helpers based on ACF settings.

// 4. FEATURE MODULES (moderate dependencies).
require_once get_template_directory() . '/inc/featured-image-column.php';
// Admin featured image column.

// 5. QUERY MODIFICATIONS (depends on caching utilities).
require_once get_template_directory() . '/inc/queries.php';
// Custom query modifications and filters.

// 6. NAVIGATION (depends on utilities and state functions).
require_once get_template_directory() . '/inc/menu-helpers.php';
// Menu rendering helpers and caching (uses state abbreviations).
require_once get_template_directory() . '/inc/menus.php';
// Menu structure and rendering (uses menu helpers).

// 7. SEO AND METADATA (depends on multiple utility modules).
require_once get_template_directory() . '/inc/schema.php';
// Modular schema system entry point.

// 8. ASSET MANAGEMENT (MUST be last to properly detect template context).
require_once get_template_directory() . '/inc/enqueue.php';
// Asset enqueueing with versioning and conditional loading.

// 9. DEBUG HELPERS (only loads for administrators).
require_once get_template_directory() . '/inc/debug-assets.php';
// Frontend asset debugging panel for administrators.

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
					/**
					 * Load WordPress filesystem API
					 *
					 * @phpstan-ignore-next-line requireOnce.fileNotFound -- WordPress core file always exists
					 */
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
							$viewbox = explode( ' ', $xml['viewBox'] );
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
 * Cache invalidation for specials archive optimization
 *
 * Clears the specials archive transient cache when specials are modified.
 * This ensures the optimized queries always serve fresh content.
 *
 * @param int $post_id The post ID being modified.
 * @return void
 */
function mia_clear_specials_cache( $post_id ): void {
	if ( 'special' === get_post_type( $post_id ) ) {
		delete_transient( 'specials_archive_english' );
		delete_transient( 'specials_archive_spanish' );
	}
}

// Clear cache when specials are updated.
add_action( 'save_post', 'mia_clear_specials_cache' );
add_action( 'delete_post', 'mia_clear_specials_cache' );
add_action( 'wp_trash_post', 'mia_clear_specials_cache' );
add_action( 'untrash_post', 'mia_clear_specials_cache' );
