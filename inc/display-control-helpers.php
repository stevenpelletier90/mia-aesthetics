<?php
/**
 * Display Control Helper Functions
 *
 * Shared visibility checking logic for breadcrumbs, CTAs, and other display components.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check visibility based on current page context and defaults array
 *
 * This function centralizes the page-type checking logic used by breadcrumbs,
 * CTAs, and other components that need conditional display per template.
 *
 * @param array<string, bool> $defaults The defaults array from ACF options (e.g., cta_defaults, breadcrumb_defaults).
 * @param bool                $fallback Default value to return if page type not found in defaults.
 * @return bool Whether to show the component.
 */
function mia_check_display_visibility( array $defaults, bool $fallback = true ): bool {
	// Define visibility rules: condition callable => defaults key.
	$rules = array(
		// Special pages.
		array(
			'check' => static fn(): bool => is_front_page(),
			'key'   => 'show_on_front_page',
		),
		array(
			'check' => static fn(): bool => is_search(),
			'key'   => 'show_on_search',
		),
		array(
			'check' => static fn(): bool => is_404(),
			'key'   => 'show_on_404',
		),
		array(
			'check' => static fn(): bool => is_category(),
			'key'   => 'show_on_category',
		),
		array(
			'check' => static fn(): bool => is_tag(),
			'key'   => 'show_on_tag',
		),
		// Archive pages.
		array(
			'check' => static fn(): bool => is_post_type_archive( 'procedure' ),
			'key'   => 'show_on_procedure_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'surgeon' ),
			'key'   => 'show_on_surgeon_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'location' ),
			'key'   => 'show_on_location_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'case' ),
			'key'   => 'show_on_case_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'special' ),
			'key'   => 'show_on_special_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'condition' ),
			'key'   => 'show_on_condition_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'non-surgical' ),
			'key'   => 'show_on_non_surgical_archive',
		),
		array(
			'check' => static fn(): bool => is_post_type_archive( 'fat-transfer' ),
			'key'   => 'show_on_fat_transfer_archive',
		),
		array(
			'check' => static fn(): bool => is_home() || ( is_archive() && ! is_post_type_archive() ),
			'key'   => 'show_on_blog_archive',
		),
		// Single posts/pages.
		array(
			'check' => static fn(): bool => is_singular( 'special' ),
			'key'   => 'show_on_special',
		),
		array(
			'check' => static fn(): bool => is_singular( 'procedure' ),
			'key'   => 'show_on_procedure',
		),
		array(
			'check' => static fn(): bool => is_singular( 'surgeon' ),
			'key'   => 'show_on_surgeon',
		),
		array(
			'check' => static fn(): bool => is_singular( 'location' ),
			'key'   => 'show_on_location',
		),
		array(
			'check' => static fn(): bool => is_singular( 'case' ),
			'key'   => 'show_on_case',
		),
		array(
			'check' => static fn(): bool => is_singular( 'condition' ),
			'key'   => 'show_on_condition',
		),
		array(
			'check' => static fn(): bool => is_singular( 'non-surgical' ),
			'key'   => 'show_on_non_surgical',
		),
		array(
			'check' => static fn(): bool => is_singular( 'fat-transfer' ),
			'key'   => 'show_on_fat_transfer',
		),
		array(
			'check' => static fn(): bool => is_singular( 'post' ),
			'key'   => 'show_on_posts',
		),
		array(
			'check' => static fn(): bool => is_page(),
			'key'   => 'show_on_pages',
		),
	);

	// Check each rule in order.
	foreach ( $rules as $rule ) {
		if ( $rule['check']() && isset( $defaults[ $rule['key'] ] ) ) {
			return (bool) $defaults[ $rule['key'] ];
		}
	}

	// Default fallback if no rule matched.
	return $fallback;
}
