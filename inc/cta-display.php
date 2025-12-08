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
 * @return bool Whether to show the CTA
 */
function should_show_consultation_cta() {
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
	if ( null === $cta_defaults || false === $cta_defaults ) {
		return ! is_singular( 'special' );
	}

	// Check based on current page type.

	// Special Pages.
	if ( is_front_page() && isset( $cta_defaults['show_on_front_page'] ) ) {
		return $cta_defaults['show_on_front_page'];
	}

	if ( is_search() && isset( $cta_defaults['show_on_search'] ) ) {
		return $cta_defaults['show_on_search'];
	}

	if ( is_404() && isset( $cta_defaults['show_on_404'] ) ) {
		return $cta_defaults['show_on_404'];
	}

	if ( is_category() && isset( $cta_defaults['show_on_category'] ) ) {
		return $cta_defaults['show_on_category'];
	}

	if ( is_tag() && isset( $cta_defaults['show_on_tag'] ) ) {
		return $cta_defaults['show_on_tag'];
	}

	// Archive Pages.
	if ( is_post_type_archive( 'procedure' ) && isset( $cta_defaults['show_on_procedure_archive'] ) ) {
		return $cta_defaults['show_on_procedure_archive'];
	}

	if ( is_post_type_archive( 'surgeon' ) && isset( $cta_defaults['show_on_surgeon_archive'] ) ) {
		return $cta_defaults['show_on_surgeon_archive'];
	}

	if ( is_post_type_archive( 'location' ) && isset( $cta_defaults['show_on_location_archive'] ) ) {
		return $cta_defaults['show_on_location_archive'];
	}

	if ( is_post_type_archive( 'case' ) && isset( $cta_defaults['show_on_case_archive'] ) ) {
		return $cta_defaults['show_on_case_archive'];
	}

	if ( is_post_type_archive( 'special' ) && isset( $cta_defaults['show_on_special_archive'] ) ) {
		return $cta_defaults['show_on_special_archive'];
	}

	if ( is_post_type_archive( 'condition' ) && isset( $cta_defaults['show_on_condition_archive'] ) ) {
		return $cta_defaults['show_on_condition_archive'];
	}

	if ( is_post_type_archive( 'non-surgical' ) && isset( $cta_defaults['show_on_non_surgical_archive'] ) ) {
		return $cta_defaults['show_on_non_surgical_archive'];
	}

	if ( is_post_type_archive( 'fat-transfer' ) && isset( $cta_defaults['show_on_fat_transfer_archive'] ) ) {
		return $cta_defaults['show_on_fat_transfer_archive'];
	}

	if ( is_home() || ( is_archive() && ! is_post_type_archive() ) ) {
		if ( isset( $cta_defaults['show_on_blog_archive'] ) ) {
			return $cta_defaults['show_on_blog_archive'];
		}
	}

	// Single Posts/Pages.
	if ( is_singular( 'special' ) && isset( $cta_defaults['show_on_special'] ) ) {
		return $cta_defaults['show_on_special'];
	}

	if ( is_singular( 'procedure' ) && isset( $cta_defaults['show_on_procedure'] ) ) {
		return $cta_defaults['show_on_procedure'];
	}

	if ( is_singular( 'surgeon' ) && isset( $cta_defaults['show_on_surgeon'] ) ) {
		return $cta_defaults['show_on_surgeon'];
	}

	if ( is_singular( 'location' ) && isset( $cta_defaults['show_on_location'] ) ) {
		return $cta_defaults['show_on_location'];
	}

	if ( is_singular( 'case' ) && isset( $cta_defaults['show_on_case'] ) ) {
		return $cta_defaults['show_on_case'];
	}

	if ( is_singular( 'condition' ) && isset( $cta_defaults['show_on_condition'] ) ) {
		return $cta_defaults['show_on_condition'];
	}

	if ( is_singular( 'non-surgical' ) && isset( $cta_defaults['show_on_non_surgical'] ) ) {
		return $cta_defaults['show_on_non_surgical'];
	}

	if ( is_singular( 'fat-transfer' ) && isset( $cta_defaults['show_on_fat_transfer'] ) ) {
		return $cta_defaults['show_on_fat_transfer'];
	}

	if ( is_singular( 'post' ) && isset( $cta_defaults['show_on_posts'] ) ) {
		return $cta_defaults['show_on_posts'];
	}

	if ( is_page() && isset( $cta_defaults['show_on_pages'] ) ) {
		return $cta_defaults['show_on_pages'];
	}

	// Default to showing if we can't determine the page type.
	return true;
}
