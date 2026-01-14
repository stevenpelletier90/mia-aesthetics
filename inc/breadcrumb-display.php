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
 * Uses shared visibility helper from display-control-helpers.php.
 */
function mia_needs_breadcrumb(): bool {
	// Check for ACF availability.
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	// Check individual post override first (takes priority).
	$override = get_field( 'breadcrumb_display_override' );
	if ( is_string( $override ) && '' !== $override && 'default' !== $override ) {
		return 'show' === $override;
	}

	// If override is 'default' or not set, check global settings.
	$breadcrumb_defaults = get_field( 'breadcrumb_defaults', 'option' );

	// If no defaults are set yet, default to showing.
	if ( ! is_array( $breadcrumb_defaults ) ) {
		return true;
	}

	// Use shared visibility checker.
	return mia_check_display_visibility( $breadcrumb_defaults, true );
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

/**
 * Shortcode for breadcrumbs
 *
 * Use [mia_breadcrumbs] in content to display breadcrumbs inline.
 * Supports optional class attribute for styling variations.
 *
 * @param array<string, string> $atts Shortcode attributes.
 * @return string Breadcrumb HTML output.
 */
function mia_aesthetics_breadcrumbs_shortcode( $atts ): string {
	if ( ! function_exists( 'yoast_breadcrumb' ) ) {
		return '';
	}

	// Check breadcrumb display logic.
	if ( ! mia_needs_breadcrumb() ) {
		return '';
	}

	// Yoast outputs breadcrumb trail as string.
	$breadcrumbs = yoast_breadcrumb( '', '', false );

	if ( '' === $breadcrumbs ) {
		return '';
	}

	// Parse shortcode attributes.
	$atts = shortcode_atts(
		array(
			'class' => '',
		),
		$atts,
		'mia_breadcrumbs'
	);

	// Build wrapper class.
	$wrapper_class = 'breadcrumb-inline';
	if ( is_string( $atts['class'] ) && '' !== $atts['class'] ) {
		$wrapper_class .= ' ' . sanitize_html_class( $atts['class'] );
	}

	// Return breadcrumb HTML without container (for inline use).
	$output  = '<nav aria-label="' . esc_attr__( 'Breadcrumb', 'mia-aesthetics' ) . '" class="' . esc_attr( $wrapper_class ) . '">';
	$output .= '<span class="visually-hidden">' . esc_html__( 'You are here:', 'mia-aesthetics' ) . '</span>';
	$output .= wp_kses_post( $breadcrumbs );
	$output .= '</nav>';

	return $output;
}
add_shortcode( 'mia_breadcrumbs', 'mia_aesthetics_breadcrumbs_shortcode' );
