<?php
/**
 * Theme setup & Gutenberg enhancements.
 *
 * @package Mia_Aesthetics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'mia_setup' );
/**
 * Register theme supports, menus, and sizes.
 *
 * @return void
 */
function mia_setup(): void {

	/* ---- Core --------------------------------------------------------- */
	add_theme_support( 'title-tag' );          // SEO‑friendly <title>.
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	/* ---- Branding ----------------------------------------------------- */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 100,
			'width'       => 300,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	/* ---- Block Editor ------------------------------------------------- */
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-units' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'border' );
	add_theme_support( 'link-color' );

	/* ---- Yoast SEO ---------------------------------------------------- */
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}

	/* ---- Navigation --------------------------------------------------- */
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'mia-aesthetics' ),
			'footer'  => __( 'Footer Navigation', 'mia-aesthetics' ),
			'social'  => __( 'Social Links', 'mia-aesthetics' ),
		)
	);

	/* ---- Images ------------------------------------------------------- */
	add_image_size( 'hero-mobile', 640, 400, true );
	add_image_size( 'hero-tablet', 1024, 600, true );
	add_image_size( 'hero-desktop', 1920, 800, true );
	add_image_size( 'card-thumb', 600, 400, true );
	add_image_size( 'profile', 600, 600, true );
	add_image_size( 'gallery-small', 300, 225, true );
	add_image_size( 'gallery-medium', 450, 338, true );
	add_image_size( 'gallery-large', 600, 450, true );

	/* ---- Content width (classic embeds) ------------------------------ */
	$GLOBALS['content_width'] = 1200;
}

/**
 * Load editor‑only styles.
 */
add_action( 'after_setup_theme', 'mia_editor_styles' );

/**
 * Load editor-only styles for Gutenberg.
 *
 * @return void
 */
function mia_editor_styles(): void {
	add_editor_style(
		array(
			'assets/css/fonts.css',
			'assets/css/editor-style.css',
		)
	);
}

/**
 * Add View Source button to admin bar
 */
add_action( 'admin_bar_menu', 'mia_admin_bar_view_source', 100 );

/**
 * Add View Source button to admin bar for comparing staging to production.
 *
 * @param WP_Admin_Bar $wp_admin_bar The WP_Admin_Bar instance.
 * @return void
 */
function mia_admin_bar_view_source( $wp_admin_bar ): void {
	if ( ! is_admin() && ! is_user_logged_in() ) {
		return;
	}

	// Get current URL and replace domain.
	$http_host   = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $http_host . $request_uri;
	$source_url  = str_replace( $http_host, 'miaaesthetics.com', $current_url );

	$wp_admin_bar->add_node(
		array(
			'id'    => 'view-source',
			'title' => 'View Source',
			'href'  => $source_url,
			'meta'  => array(
				'target' => '_blank',
				'rel'    => 'noopener noreferrer',
			),
		)
	);
}

/**
 * Enable Google Maps API for ACF Pro
 */
add_action( 'acf/init', 'mia_acf_init' );

/**
 * Initialize ACF Pro settings including Google Maps API key.
 *
 * @return void
 */
function mia_acf_init(): void {
	$api_key = get_field( 'google_maps_api_key', 'option' );
	if ( is_string( $api_key ) && '' !== $api_key ) {
		acf_update_setting( 'google_api_key', $api_key );
	}
}
