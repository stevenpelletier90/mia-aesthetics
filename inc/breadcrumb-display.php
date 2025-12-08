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
	if ( is_string( $override ) && '' !== $override && 'default' !== $override ) {
		return 'show' === $override;
	}

	// If override is 'default' or not set, check global settings.
	$breadcrumb_defaults = get_field( 'breadcrumb_defaults', 'option' );

	// If no defaults are set yet, default to showing.
	if ( null === $breadcrumb_defaults || false === $breadcrumb_defaults ) {
		return true;
	}

	// Check based on current page type.

	// Special Pages.
	if ( is_front_page() && isset( $breadcrumb_defaults['show_on_front_page'] ) ) {
		return $breadcrumb_defaults['show_on_front_page'];
	}

	if ( is_search() && isset( $breadcrumb_defaults['show_on_search'] ) ) {
		return $breadcrumb_defaults['show_on_search'];
	}

	if ( is_404() && isset( $breadcrumb_defaults['show_on_404'] ) ) {
		return $breadcrumb_defaults['show_on_404'];
	}

	if ( is_category() && isset( $breadcrumb_defaults['show_on_category'] ) ) {
		return $breadcrumb_defaults['show_on_category'];
	}

	if ( is_tag() && isset( $breadcrumb_defaults['show_on_tag'] ) ) {
		return $breadcrumb_defaults['show_on_tag'];
	}

	// Archive Pages.
	if ( is_post_type_archive( 'procedure' ) && isset( $breadcrumb_defaults['show_on_procedure_archive'] ) ) {
		return $breadcrumb_defaults['show_on_procedure_archive'];
	}

	if ( is_post_type_archive( 'surgeon' ) && isset( $breadcrumb_defaults['show_on_surgeon_archive'] ) ) {
		return $breadcrumb_defaults['show_on_surgeon_archive'];
	}

	if ( is_post_type_archive( 'location' ) && isset( $breadcrumb_defaults['show_on_location_archive'] ) ) {
		return $breadcrumb_defaults['show_on_location_archive'];
	}

	if ( is_post_type_archive( 'case' ) && isset( $breadcrumb_defaults['show_on_case_archive'] ) ) {
		return $breadcrumb_defaults['show_on_case_archive'];
	}

	if ( is_post_type_archive( 'special' ) && isset( $breadcrumb_defaults['show_on_special_archive'] ) ) {
		return $breadcrumb_defaults['show_on_special_archive'];
	}

	if ( is_post_type_archive( 'condition' ) && isset( $breadcrumb_defaults['show_on_condition_archive'] ) ) {
		return $breadcrumb_defaults['show_on_condition_archive'];
	}

	if ( is_post_type_archive( 'non-surgical' ) && isset( $breadcrumb_defaults['show_on_non_surgical_archive'] ) ) {
		return $breadcrumb_defaults['show_on_non_surgical_archive'];
	}

	if ( is_post_type_archive( 'fat-transfer' ) && isset( $breadcrumb_defaults['show_on_fat_transfer_archive'] ) ) {
		return $breadcrumb_defaults['show_on_fat_transfer_archive'];
	}

	if ( is_home() || ( is_archive() && ! is_post_type_archive() ) ) {
		if ( isset( $breadcrumb_defaults['show_on_blog_archive'] ) ) {
			return $breadcrumb_defaults['show_on_blog_archive'];
		}
	}

	// Single Posts/Pages.
	if ( is_singular( 'special' ) && isset( $breadcrumb_defaults['show_on_special'] ) ) {
		return $breadcrumb_defaults['show_on_special'];
	}

	if ( is_singular( 'procedure' ) && isset( $breadcrumb_defaults['show_on_procedure'] ) ) {
		return $breadcrumb_defaults['show_on_procedure'];
	}

	if ( is_singular( 'surgeon' ) && isset( $breadcrumb_defaults['show_on_surgeon'] ) ) {
		return $breadcrumb_defaults['show_on_surgeon'];
	}

	if ( is_singular( 'location' ) && isset( $breadcrumb_defaults['show_on_location'] ) ) {
		return $breadcrumb_defaults['show_on_location'];
	}

	if ( is_singular( 'case' ) && isset( $breadcrumb_defaults['show_on_case'] ) ) {
		return $breadcrumb_defaults['show_on_case'];
	}

	if ( is_singular( 'condition' ) && isset( $breadcrumb_defaults['show_on_condition'] ) ) {
		return $breadcrumb_defaults['show_on_condition'];
	}

	if ( is_singular( 'non-surgical' ) && isset( $breadcrumb_defaults['show_on_non_surgical'] ) ) {
		return $breadcrumb_defaults['show_on_non_surgical'];
	}

	if ( is_singular( 'fat-transfer' ) && isset( $breadcrumb_defaults['show_on_fat_transfer'] ) ) {
		return $breadcrumb_defaults['show_on_fat_transfer'];
	}

	if ( is_singular( 'post' ) && isset( $breadcrumb_defaults['show_on_posts'] ) ) {
		return $breadcrumb_defaults['show_on_posts'];
	}

	if ( is_page() && isset( $breadcrumb_defaults['show_on_pages'] ) ) {
		return $breadcrumb_defaults['show_on_pages'];
	}

	// Default to showing if we can't determine the page type.
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
