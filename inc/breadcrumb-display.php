<?php
/**
 * Breadcrumb Display Control Functions
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if current page needs breadcrumb component
 *
 * This is the comprehensive function that handles all template contexts.
 */
function mia_needs_breadcrumb(): bool {
	// Check for ACF availability.
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	// Check individual post override first (takes priority).
	$override = get_field( 'breadcrumb_display_override' );
	if ( ! empty( $override ) && 'default' !== $override ) {
		return 'show' === $override;
	}

	// For now, let's enable breadcrumbs on all pages by default until ACF is configured
	// This will show breadcrumbs everywhere so you can see them working
	return true;
}

/**
 * Renders breadcrumbs using Yoast SEO
 *
 * This function handles the actual breadcrumb display logic.
 * The HTML output is delegated to the breadcrumb component.
 */
function mia_aesthetics_breadcrumbs(): void {
	if ( ! function_exists( 'yoast_breadcrumb' ) ) {
		return;
	}

	// Check breadcrumb display logic.
	if ( ! mia_needs_breadcrumb() ) {
		return;
	}

	// Yoast outputs breadcrumb trail as string.
	$breadcrumbs = yoast_breadcrumb( '', '', false );

	if ( '' === $breadcrumbs ) {
		return;
	}

	// Pass breadcrumb data to component.
	$args = array(
		'breadcrumbs' => $breadcrumbs,
	);

	get_template_part( 'components/breadcrumb', null, $args );
}
