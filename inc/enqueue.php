<?php
/**
 * Simple Asset Enqueue for Mia Aesthetics Theme
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue all theme assets - simple version
 */
function mia_enqueue_assets(): void {
	$theme_version = '3.0.0';

	// Core assets - load on every page
	wp_enqueue_style( 'mia-fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), $theme_version );
	wp_enqueue_style( 'mia-bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/css/bootstrap.min.css', array( 'mia-fonts' ), '5.3.8' );
	wp_enqueue_style( 'mia-base', get_template_directory_uri() . '/assets/css/base.css', array( 'mia-bootstrap' ), $theme_version );
	wp_enqueue_style( 'mia-fontawesome', get_template_directory_uri() . '/assets/vendor/fontawesome/css/all.min.css', array(), '7.0.1' );
	wp_enqueue_style( 'mia-header', get_template_directory_uri() . '/assets/css/header.css', array( 'mia-base' ), $theme_version );
	wp_enqueue_style( 'mia-footer', get_template_directory_uri() . '/assets/css/footer.css', array( 'mia-base' ), $theme_version );

	wp_enqueue_script( 'mia-bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array(), '5.3.8', true );
	wp_enqueue_script( 'mia-header', get_template_directory_uri() . '/assets/js/header.js', array( 'mia-bootstrap' ), $theme_version, true );
	wp_enqueue_script( 'mia-footer', get_template_directory_uri() . '/assets/js/footer.js', array(), $theme_version, true );

	// Components - load only when needed
	mia_maybe_load_components();

	// Load template-specific CSS/JS for current template
	mia_load_template_assets();
}

/**
 * Load components only when they're actually used
 */
function mia_maybe_load_components(): void {
	$theme_version = '3.0.0';

	// Check page content for component usage
	$post_content = '';
	if ( is_singular() && have_posts() ) {
		global $post;
		$post_content = $post->post_content;
	}

	// Load consultation form if page has forms or specific templates
	if ( strpos( $post_content, 'consultation-form' ) !== false ||
		strpos( $post_content, 'gform' ) !== false ||
		is_page_template( 'page-condition-layout.php' ) ||
		is_page_template( 'page-treatment-layout.php' ) ||
		is_singular( 'special' )
	) {
		wp_enqueue_style( 'mia-consultation-form', get_template_directory_uri() . '/assets/css/components/consultation-form.css', array( 'mia-base' ), $theme_version );
	}

	// Load consultation CTA (not on careers pages)
	if ( ! mia_is_careers_page() ) {
		wp_enqueue_style( 'mia-consultation-cta', get_template_directory_uri() . '/assets/css/components/consultation-cta.css', array( 'mia-base' ), $theme_version );
	}

	// Load careers CTA (only on careers pages)
	if ( mia_is_careers_page() ) {
		wp_enqueue_style( 'mia-careers-cta', get_template_directory_uri() . '/assets/css/components/careers-cta.css', array( 'mia-base' ), $theme_version );
	}

	// Load case card component if page shows cases
	if ( strpos( $post_content, 'case-card' ) !== false ||
		is_post_type_archive( 'case' ) ||
		is_singular( 'case' ) ||
		is_front_page()
	) {
		wp_enqueue_style( 'mia-case-card', get_template_directory_uri() . '/assets/css/components/case-card.css', array( 'mia-base' ), $theme_version );
	}

	// Load FAQ component if page has FAQs (check ACF field and templates)
	$has_faq_content = false;

	// Check ACF FAQ section field if on singular post/page
	if ( is_singular() && function_exists( 'get_field' ) ) {
		$faq_section     = get_field( 'faq_section' );
		$has_faq_content = ! empty( $faq_section );
	}

	// Check post content for FAQ/accordion strings
	$has_faq_in_content = strpos( $post_content, 'faq' ) !== false || strpos( $post_content, 'accordion' ) !== false;

	// Check specific templates that have FAQ sections
	$is_faq_template = is_post_type_archive( 'fat-transfer' ) ||
						is_singular( 'case' ) ||
						is_singular( 'surgeon' ) ||
						is_page_template( 'page-treatment-layout.php' );

	if ( $has_faq_content || $has_faq_in_content || $is_faq_template ) {
		wp_enqueue_style( 'mia-faq', get_template_directory_uri() . '/assets/css/components/faq.css', array( 'mia-base' ), $theme_version );
	}

	// Load location search components when needed
	if ( strpos( $post_content, 'location-search' ) !== false ||
		is_post_type_archive( 'location' ) ||
		is_page_template( 'page-careers.php' )
	) {
		wp_enqueue_style( 'mia-location-search', get_template_directory_uri() . '/assets/css/components/location-search.css', array( 'mia-base' ), $theme_version );
		wp_enqueue_script( 'mia-location-search', get_template_directory_uri() . '/assets/js/components/location-search.js', array(), $theme_version, true );
	}

	// Load careers location search specifically
	if ( is_page_template( 'page-careers.php' ) || is_page_template( 'page-careers-locations.php' ) ) {
		wp_enqueue_style( 'mia-location-search-careers', get_template_directory_uri() . '/assets/css/components/location-search-careers.css', array( 'mia-base' ), $theme_version );
		wp_enqueue_script( 'mia-location-search-careers', get_template_directory_uri() . '/assets/js/components/location-search-careers.js', array(), $theme_version, true );
	}
}

/**
 * Helper to check if current page is careers-related
 */
function mia_is_careers_page(): bool {
	return (
		is_page_template( 'page-careers.php' ) ||
		is_page_template( 'page-careers-locations.php' ) ||
		is_page( 'careers' ) ||
		is_page( 'careers-locations' )
	);
}

/**
 * Get current template identifier for debug
 */
function mia_get_current_template_key(): string {

	// Check for page templates first (on any post type, not just pages)
	$template = get_page_template_slug();
	if ( $template ) {
		$template_name = basename( $template, '.php' );
		return $template_name;
	}

	// Check for page-specific conditions
	if ( is_page() ) {
		if ( is_front_page() ) {
			return 'front-page';
		}

		return 'page';
	}

	// Archives
	if ( is_home() ) {
		return 'home';
	}
	if ( is_404() ) {
		return '404';
	}
	if ( is_search() ) {
		return 'search';
	}
	if ( is_category() ) {
		return 'category';
	}

	// Custom post type archives
	if ( is_post_type_archive( 'case' ) ) {
		return 'archive-case';
	}
	if ( is_post_type_archive( 'condition' ) ) {
		return 'archive-condition';
	}
	if ( is_post_type_archive( 'location' ) ) {
		return 'archive-location';
	}
	if ( is_post_type_archive( 'non-surgical' ) ) {
		return 'archive-non-surgical';
	}
	if ( is_post_type_archive( 'procedure' ) ) {
		return 'archive-procedure';
	}
	if ( is_post_type_archive( 'special' ) ) {
		return 'archive-special';
	}
	if ( is_post_type_archive( 'surgeon' ) ) {
		return 'archive-surgeon';
	}
	if ( is_post_type_archive( 'fat-transfer' ) ) {
		return 'archive-fat-transfer';
	}

	// Taxonomy archives
	if ( is_tax( 'case_category' ) ) {
		return 'case-category';
	}

	// Singles
	if ( is_singular( 'case' ) ) {
		return 'single-case';
	}
	if ( is_singular( 'condition' ) ) {
		return 'single-condition';
	}
	if ( is_singular( 'location' ) ) {
		return 'single-location';
	}
	if ( is_singular( 'non-surgical' ) ) {
		return 'single-non-surgical';
	}
	if ( is_singular( 'procedure' ) ) {
		return 'single-procedure';
	}
	if ( is_singular( 'special' ) ) {
		return 'single-special';
	}
	if ( is_singular( 'surgeon' ) ) {
		return 'single-surgeon';
	}
	if ( is_singular( 'fat-transfer' ) ) {
		return 'single-fat-transfer';
	}
	if ( is_singular( 'post' ) ) {
		return 'single-post';
	}

	// Generic fallbacks
	if ( is_archive() ) {
		return 'archive';
	}
	if ( is_single() ) {
		return 'single';
	}

	return 'index';
}

/**
 * Load template-specific CSS/JS based on current template
 */
function mia_load_template_assets(): void {
	$theme_version = '3.0.0';
	$template_key  = mia_get_current_template_key();

	// Special handling for front page (hero section + glide)
	if ( is_front_page() ) {
		wp_enqueue_style( 'mia-hero-section', get_template_directory_uri() . '/assets/css/hero-section.css', array( 'mia-base' ), $theme_version );
		wp_enqueue_script( 'mia-glide', get_template_directory_uri() . '/assets/vendor/glide/js/glide.min.js', array(), '3.7.1', true );
	}

	// Map template keys to CSS/JS file paths
	$asset_map = array(
		// Pages
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
		'page'                        => array(
			'css' => 'pages/page.css',
			'js'  => 'pages/page.js',
		),

		// Archives
		'404'                         => array(
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

		// Singles
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
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		), // Reuse
		'single-procedure'            => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		), // Reuse
		'single-special'              => array(
			'css' => 'singles/single-special.css',
			'js'  => 'singles/single-special.js',
		),
		'single-surgeon'              => array(
			'css' => 'singles/single-surgeon.css',
			'js'  => 'singles/single-surgeon.js',
		),
		'single-fat-transfer'         => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		), // Reuse
		'single-post'                 => array(
			'css' => 'singles/single-post.css',
			'js'  => 'singles/single-post.js',
		),
	);

	// Load CSS if file exists
	if ( isset( $asset_map[ $template_key ]['css'] ) ) {
		$css_path = get_template_directory() . '/assets/css/' . $asset_map[ $template_key ]['css'];
		if ( file_exists( $css_path ) ) {
			wp_enqueue_style(
				'mia-' . $template_key,
				get_template_directory_uri() . '/assets/css/' . $asset_map[ $template_key ]['css'],
				array( 'mia-base' ),
				$theme_version
			);
		}
	}

	// Load JS if file exists
	if ( isset( $asset_map[ $template_key ]['js'] ) ) {
		$js_path = get_template_directory() . '/assets/js/' . $asset_map[ $template_key ]['js'];
		if ( file_exists( $js_path ) ) {
			wp_enqueue_script(
				'mia-' . $template_key,
				get_template_directory_uri() . '/assets/js/' . $asset_map[ $template_key ]['js'],
				array( 'mia-bootstrap' ),
				$theme_version,
				true
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );
