<?php
/**
 * Page Content Mapping
 *
 * Maps page post IDs to PHP content files.
 * Post IDs are used instead of slugs because they never change
 * (URLs can be modified, but post IDs remain constant).
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get page content file mapping (post ID => filename).
 *
 * @return array<int, string> Map of post IDs to content filenames.
 */
function mia_get_page_content_map(): array {
	return array(
		30199 => 'surgical-journey.php',
	);
}
