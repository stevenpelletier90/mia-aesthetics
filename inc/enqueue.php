<?php
/**
 * Professional WordPress Asset Enqueue System
 * Following WordPress Core standards and best practices
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme constants for asset management
 */
if ( ! defined( 'MIA_THEME_VERSION' ) ) {
	define( 'MIA_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
}

/**
 * Main enqueue function - coordinates all asset loading
 */
function mia_enqueue_assets(): void {

	// Load global assets (always needed).
	mia_enqueue_global_assets();

	// Load template-specific assets.
	mia_enqueue_template_assets();

	// Load conditional components (ACF-aware).
	mia_enqueue_conditional_components();
}

/**
 * Enqueue global assets that are needed on every page
 */
function mia_enqueue_global_assets(): void {

	// Global CSS - Foundation styles.
	wp_enqueue_style( 'mia-fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/css/bootstrap.min.css', array( 'mia-fonts' ), '5.3.8' );
	wp_enqueue_style( 'mia-base', get_template_directory_uri() . '/assets/css/base.css', array( 'mia-bootstrap' ), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-button', get_template_directory_uri() . '/assets/css/components/button.css', array( 'mia-base' ), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-social-media', get_template_directory_uri() . '/assets/css/components/social-media.css', array( 'mia-base' ), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-fontawesome', get_template_directory_uri() . '/assets/vendor/fontawesome/css/all.min.css', array(), '7.0.1' );
	wp_enqueue_style( 'mia-header', get_template_directory_uri() . '/assets/css/header.css', array( 'mia-base' ), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-footer', get_template_directory_uri() . '/assets/css/footer.css', array( 'mia-base' ), MIA_THEME_VERSION );

	// Global JS - Foundation scripts.
	wp_enqueue_script( 'mia-bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array(), '5.3.8', true );
	wp_enqueue_script( 'mia-header', get_template_directory_uri() . '/assets/js/header.js', array( 'mia-bootstrap' ), MIA_THEME_VERSION, true );
	wp_enqueue_script( 'mia-footer', get_template_directory_uri() . '/assets/js/footer.js', array(), MIA_THEME_VERSION, true );
}

/**
 * Enqueue conditional components - ACF-aware loading
 */
function mia_enqueue_conditional_components(): void {
	// Consultation CTA - Load only when it will be displayed (ACF-aware).
	if ( should_show_consultation_cta() ) {
		mia_load_component_assets( 'consultation-cta' );
	}

	// Consultation Form - Load only on pages that have consultation forms.
	if ( mia_has_consultation_form() ) {
		mia_load_component_assets( 'consultation-form' );
	}

	// Careers CTA - Load only on careers pages.
	if ( mia_is_careers_page() ) {
		mia_load_component_assets( 'careers-cta' );
	}

	// Case Card - Load only where cases are displayed.
	if ( mia_needs_case_card() ) {
		mia_load_component_assets( 'case-card' );
	}

	// FAQ Component - Load where FAQs are displayed.
	if ( mia_needs_faq() ) {
		mia_load_component_assets( 'faq' );
	}

	// Location Search Careers - Load on careers pages with location search.
	if ( mia_needs_location_search_careers() ) {
		mia_load_component_assets( 'location-search-careers' );
		mia_enqueue_google_maps( 'initGoogleMapsCareers' );
	}

	// Virtual Consultation - Load Google Maps for location auto-selection.
	if ( mia_needs_virtual_consultation_maps() ) {
		mia_enqueue_google_maps( 'initVirtualConsultationGeocoder' );
	}

	// TODO: Add conditional loading for regular location-search component when ready
	// Components exist: location-search.css and location-search.js.

	// Breadcrumb - Load only when breadcrumbs are enabled via ACF.
	if ( mia_needs_breadcrumb() ) {
		mia_load_component_assets( 'breadcrumb' );
	}
}

/**
 * Check if current page needs consultation form assets
 */
function mia_has_consultation_form(): bool {
	// Check for pages with consultation form templates.
	if ( is_page_template( 'page-condition-layout.php' ) ||
		is_page_template( 'page-treatment-layout.php' ) ||
		is_page_template( 'page-non-surgical-layout.php' ) ||
		is_singular( 'special' ) ) {
		return true;
	}

	// Check for ACF field that indicates a form is present.
	if ( function_exists( 'get_field' ) && (bool) get_field( 'show_consultation_form' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if current page needs case card component
 */
function mia_needs_case_card(): bool {
	// Pages that display case listings.
	if ( is_page_template( 'page-case-category.php' ) ||
		is_post_type_archive( 'case' ) ||
		is_tax( 'case_category' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if current page needs FAQ component
 */
function mia_needs_faq(): bool {
	// Check for ACF FAQ field that indicates FAQs are present.
	if ( function_exists( 'get_field' ) ) {
		$faq_section = get_field( 'faq_section' );

		// Check if FAQ section exists and has valid FAQs.
		if ( isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) && count( $faq_section['faqs'] ) > 0 ) {
			// Check if at least one FAQ has both question and answer.
			foreach ( $faq_section['faqs'] as $faq ) {
				if ( isset( $faq['question'] ) && is_string( $faq['question'] ) && '' !== $faq['question'] &&
					isset( $faq['answer'] ) && is_string( $faq['answer'] ) && '' !== $faq['answer'] ) {
					return true;
				}
			}
		}
	}

	return false;
}

/**
 * Check if current page needs location search careers component
 */
function mia_needs_location_search_careers(): bool {
	// Currently only used on careers page.
	if ( is_page_template( 'page-careers.php' ) ) {
		return true;
	}

	return false;
}

/**
 * Check if current page needs Google Maps for virtual consultation
 */
function mia_needs_virtual_consultation_maps(): bool {
	// Load Google Maps on virtual consultation page for location auto-selection.
	if ( is_page_template( 'page-virtual-consultation.php' ) ) {
		return true;
	}

	return false;
}

/**
 * Enqueue Google Maps API with specified callback
 *
 * @param string $callback The JavaScript callback function name.
 */
function mia_enqueue_google_maps( string $callback ): void {
	if ( ! wp_script_is( 'google-maps', 'registered' ) ) {
		$api_key = get_field( 'google_maps_api_key', 'option' );
		if ( is_string( $api_key ) && '' !== $api_key ) {
			wp_register_script(
				'google-maps',
				'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ) . '&libraries=places&callback=' . esc_attr( $callback ),
				array(), // Dependencies will be handled by individual components.
				'3.60.0',
				true
			);
		}
	}

	if ( ! wp_script_is( 'google-maps', 'enqueued' ) ) {
		wp_enqueue_script( 'google-maps' );
	}
}

/**
 * Load component assets (CSS always, JS conditionally)
 *
 * @param string $component The component name to load assets for.
 */
function mia_load_component_assets( string $component ): void {
	// Always load CSS (required for all components).
	wp_enqueue_style(
		"mia-{$component}",
		get_template_directory_uri() . "/assets/css/components/{$component}.css",
		array( 'mia-base' ),
		MIA_THEME_VERSION
	);

	// Load JS only if file exists (prevents 404 errors for CSS-only components).
	$js_file = get_template_directory() . "/assets/js/components/{$component}.js";
	if ( file_exists( $js_file ) ) {
		wp_enqueue_script(
			"mia-{$component}",
			get_template_directory_uri() . "/assets/js/components/{$component}.js",
			array(),
			MIA_THEME_VERSION,
			true
		);
	}
}

/**
 * Helper to check if current page is careers-related
 */
function mia_is_careers_page(): bool {
	return (
		is_page_template( 'page-careers.php' ) ||
		is_page_template( 'page-careers-locations.php' )
	);
}

/**
 * Get current template identifier for debug
 */
function mia_get_current_template_key(): string {
	// Check for post type archives FIRST (before page templates).
	// This prevents page templates from interfering with archive detection.
	$archive_types = array( 'case', 'condition', 'location', 'non-surgical', 'procedure', 'special', 'surgeon', 'fat-transfer' );
	foreach ( $archive_types as $type ) {
		if ( is_post_type_archive( $type ) ) {
			return "archive-{$type}";
		}
	}

	// Check for page templates (on any post type, not just pages).
	$template = get_page_template_slug();
	if ( is_string( $template ) && '' !== $template ) {
		return basename( $template, '.php' );
	}

	// Check for front page first (special case).
	if ( is_front_page() ) {
		return 'front-page';
	}

	// Simple conditional checks.
	$simple_checks = array(
		'is_home'     => 'home',
		'is_404'      => 'error-404',
		'is_search'   => 'search',
		'is_category' => 'category',
		'is_page'     => 'page',
	);

	foreach ( $simple_checks as $function => $template_key ) {
		if ( call_user_func( $function ) ) {
			return $template_key;
		}
	}

	// Taxonomy archives.
	if ( is_tax( 'case_category' ) ) {
		return 'case-category';
	}

	// Singles.
	$single_types = array( 'case', 'condition', 'location', 'non-surgical', 'procedure', 'special', 'surgeon', 'fat-transfer', 'post' );
	foreach ( $single_types as $type ) {
		if ( is_singular( $type ) ) {
			return "single-{$type}";
		}
	}

	// Generic fallbacks.
	if ( is_archive() ) {
		return 'archive';
	}
	if ( is_single() ) {
		return 'single';
	}

	return 'index';
}

/**
 * Enqueue template-specific assets based on current page/template
 */
function mia_enqueue_template_assets(): void {
	$template_key = mia_get_current_template_key();

	// Load template-specific CSS/JS files.
	mia_load_template_files( $template_key );
}

/**
 * Load template-specific CSS/JS files
 *
 * @param string $template_key The template identifier for asset lookup.
 */
function mia_load_template_files( string $template_key ): void {
	// Get asset map for current template.
	$asset_map = mia_get_template_asset_map();

	// Load CSS for this template (always load CSS).
	if ( isset( $asset_map[ $template_key ]['css'] ) ) {
		$css_path = get_template_directory_uri() . '/assets/css/' . $asset_map[ $template_key ]['css'];

		wp_enqueue_style(
			'mia-' . $template_key,
			$css_path,
			array( 'mia-base' ),
			MIA_THEME_VERSION
		);
	}

	// Load JS for this template.
	if ( isset( $asset_map[ $template_key ]['js'] ) ) {
		$js_path = get_template_directory_uri() . '/assets/js/' . $asset_map[ $template_key ]['js'];

		wp_enqueue_script(
			'mia-' . $template_key,
			$js_path,
			array( 'mia-bootstrap' ),
			MIA_THEME_VERSION,
			true
		);
	}
}

/**
 * Get template-to-asset mapping (clean, organized by category)
 *
 * @return array<string, array<string, string>> Template-to-asset mapping with CSS/JS paths.
 */
function mia_get_template_asset_map(): array {
	return array(
		// Page Templates.
		'front-page'                  => array(
			'css' => 'pages/front-page.css',
			'js'  => 'pages/front-page.js',
		),
		'page-before-after-by-doctor' => array(
			'css' => 'pages/page-before-after-by-doctor.css',
			'js'  => 'pages/page-before-after-by-doctor.js',
		),
		'page-blank-canvas'           => array(
			'css' => 'pages/page-blank-canvas.css',
			'js'  => 'pages/page-blank-canvas.js',
		),
		'page-careers'                => array(
			'css' => 'pages/page-careers.css',
			'js'  => 'pages/page-careers.js',
		),
		'page-careers-locations'      => array(
			'css' => 'pages/page-careers-locations.css',
			'js'  => 'pages/page-careers-locations.js',
		),
		'page-case-category'          => array(
			'css' => 'pages/page-case-category.css',
			'js'  => 'pages/page-case-category.js',
		),
		'page-condition-layout'       => array(
			'css' => 'pages/page-condition-layout.css',
			'js'  => 'pages/page-condition-layout.js',
		),
		'page-hero-canvas'            => array(
			'css' => 'pages/page-hero-canvas.css',
			'js'  => 'pages/page-hero-canvas.js',
		),
		'page-procedures-listing'     => array(
			'css' => 'pages/page-procedures-listing.css',
			'js'  => 'pages/page-procedures-listing.js',
		),
		'page-procedures-manual'      => array(
			'css' => 'pages/page-procedures-manual.css',
			'js'  => 'pages/page-procedures-manual.js',
		),
		'page-treatment-layout'       => array(
			'css' => 'pages/page-treatment-layout.css',
			'js'  => 'pages/page-treatment-layout.js',
		),
		'page-non-surgical-layout'    => array(
			'css' => 'pages/page-non-surgical-layout.css',
			'js'  => 'pages/page-non-surgical-layout.js',
		),
		'page-virtual-consultation'   => array(
			'css' => 'pages/page-virtual-consultation.css',
			'js'  => 'pages/page-virtual-consultation.js',
		),
		'page'                        => array(
			'css' => 'pages/page.css',
			'js'  => 'pages/page.js',
		),

		// Archive Pages.
		'error-404'                   => array(
			'css' => 'archives/404.css',
			'js'  => 'archives/404.js',
		),
		'archive-case'                => array(
			'css' => 'archives/archive-case.css',
			'js'  => 'archives/archive-case.js',
		),
		'archive-condition'           => array(
			'css' => 'archives/archive-condition.css',
			'js'  => 'archives/archive-condition.js',
		),
		'archive-location'            => array(
			'css' => 'archives/archive-location.css',
			'js'  => 'archives/archive-location.js',
		),
		'archive-non-surgical'        => array(
			'css' => 'archives/archive-non-surgical.css',
			'js'  => 'archives/archive-non-surgical.js',
		),
		'archive-procedure'           => array(
			'css' => 'archives/archive-procedure.css',
			'js'  => 'archives/archive-procedure.js',
		),
		'archive-special'             => array(
			'css' => 'archives/archive-special.css',
			'js'  => 'archives/archive-special.js',
		),
		'archive-surgeon'             => array(
			'css' => 'archives/archive-surgeon.css',
			'js'  => 'archives/archive-surgeon.js',
		),
		'archive-fat-transfer'        => array(
			'css' => 'archives/archive-fat-transfer.css',
			'js'  => 'archives/archive-fat-transfer.js',
		),
		'archive'                     => array(
			'css' => 'archives/archive.css',
			'js'  => 'archives/archive.js',
		),
		'case-category'               => array(
			'css' => 'archives/case-category.css',
			'js'  => 'archives/case-category.js',
		),
		'category'                    => array(
			'css' => 'archives/category.css',
			'js'  => 'archives/category.js',
		),
		'home'                        => array(
			'css' => 'archives/home.css',
			'js'  => 'archives/home.js',
		),
		'search'                      => array(
			'css' => 'archives/search.css',
			'js'  => 'archives/search.js',
		),
		'index'                       => array(
			'css' => 'archives/index.css',
			'js'  => 'archives/index.js',
		),

		// Single Post Types.
		'single-case'                 => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		),
		'single-condition'            => array(
			'css' => 'singles/single-condition.css',
			'js'  => 'singles/single-condition.js',
		),
		'single-location'             => array(
			'css' => 'singles/single-location.css',
			'js'  => 'singles/single-location.js',
		),
		'single-non-surgical'         => array(
			'css' => 'singles/single-non-surgical.css',
			'js'  => 'singles/single-non-surgical.js',
		),
		'single-procedure'            => array(
			'css' => 'singles/single-procedure.css',
			'js'  => 'singles/single-procedure.js',
		),
		'single-special'              => array(
			'css' => 'singles/single-special.css',
			'js'  => 'singles/single-special.js',
		),
		'single-surgeon'              => array(
			'css' => 'singles/single-surgeon.css',
			'js'  => 'singles/single-surgeon.js',
		),
		'single-fat-transfer'         => array(
			'css' => 'singles/single-fat-transfer.css',
			'js'  => 'singles/single-fat-transfer.js',
		),
		'single-post'                 => array(
			'css' => 'singles/single-post.css',
			'js'  => 'singles/single-post.js',
		),
	);
}

add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );

/**
 * Enqueue third-party scripts (tracking, accessibility)
 * Using wp_enqueue_script for better Asset CleanUp Pro control
 */
function mia_enqueue_third_party_scripts(): void {
	// Attributer - Marketing attribution tracking.
	// External CDN script - version managed by provider.
	wp_enqueue_script(
		'mia-attributer',
		'https://d1b3llzbo1rqxo.cloudfront.net/attributer.js',
		array(),
		'1.0.0',
		false // Load in head for early attribution.
	);

	// Add Attributer config before the script.
	wp_add_inline_script(
		'mia-attributer',
		'window.attributersettings = { cookieLife: 30 };',
		'before'
	);

	// UserWay - Accessibility widget.
	// External CDN script - version managed by provider.
	wp_enqueue_script(
		'mia-userway',
		'https://cdn.userway.org/widget.js',
		array(),
		'1.0.0',
		false // Load in head for early accessibility.
	);
}
add_action( 'wp_enqueue_scripts', 'mia_enqueue_third_party_scripts', 5 );

/**
 * Add data attributes to third-party scripts
 *
 * @param string $tag    The script tag HTML.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function mia_add_script_attributes( string $tag, string $handle ): string {
	if ( 'mia-userway' === $handle ) {
		$tag = str_replace( ' src=', ' data-account="o4cLhj3rDh" src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'mia_add_script_attributes', 10, 2 );
