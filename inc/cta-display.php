<?php
/**
 * CTA Display Control Functions
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Determine if the consultation CTA should be displayed
 *
 * Uses shared visibility helper from display-control-helpers.php.
 *
 * @return bool Whether to show the CTA
 */
function should_show_consultation_cta(): bool {
	// Always exclude careers pages (maintain existing behavior).
	if ( is_page_template( 'page-careers.php' ) ||
		is_page_template( 'page-careers-locations.php' ) ||
		is_page( 'careers' ) ||
		is_page( 'careers-locations' ) ) {
		return false;
	}

	// Check for individual page/post override.
	$override = get_field( 'cta_display_override' );

	if ( 'show' === $override ) {
		return true;
	}

	if ( 'hide' === $override ) {
		return false;
	}

	// If override is 'default' or not set, check global settings.
	$cta_defaults = get_field( 'cta_defaults', 'option' );

	// If no defaults are set yet, default to showing (except for specials).
	if ( ! is_array( $cta_defaults ) ) {
		return ! is_singular( 'special' );
	}

	// Use shared visibility checker.
	return mia_check_display_visibility( $cta_defaults, true );
}
