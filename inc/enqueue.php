<?php
/**
 * Optimized Asset Management for Mia Aesthetics Theme
 *
 * Handles all script and style enqueueing with caching‑friendly filename‑hash
 * versioning, conditional loading, and performance optimizations tailored for
 * WP Engine + WP Rocket.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ---------------------------------------------------------------------------
 * Constants
 * ---------------------------------------------------------------------------
 */
if ( ! defined( 'MIA_ASSET_HASH_LEN' ) ) {
	// Length of the MD5 hash used for cache‑busting (e.g. main.1a2b3c4d.js).
	define( 'MIA_ASSET_HASH_LEN', 8 );
}

/**
 * Register a style or script with automatic file‑hash versioning.
 *
 * @param string             $type   Either 'style' or 'script'.
 * @param string             $handle WordPress handle.
 * @param string             $path   File path relative to /assets (leading slash allowed).
 * @param array<int, string> $deps   Optional dependencies.
 * @param bool               $footer Load script in footer (scripts only).
 * @return void
 */
function mia_register_asset( $type, $handle, $path, $deps = array(), $footer = true ): void {
	$base_uri = trailingslashit( get_template_directory_uri() ) . 'assets';
	$base_dir = trailingslashit( get_template_directory() ) . 'assets';

	$src  = $base_uri . $path;
	$file = wp_normalize_path( $base_dir . $path );

	// Fail silently if file doesn't exist in production.

	// Use file modification time for versioning—lighter than computing an MD5 hash on every request.
	$ver = file_exists( $file ) ? (string) filemtime( $file ) : null;

	if ( 'style' === $type ) {
		wp_register_style( $handle, $src, $deps, $ver );
	} else {
		wp_register_script( $handle, $src, $deps, $ver, $footer );
		wp_script_add_data( $handle, 'strategy', 'defer' ); // Non‑critical JS → defer.
	}
}

/**
 * ---------------------------------------------------------------------------
 * Context mappings (single source of truth for CSS/JS filenames)
 * ---------------------------------------------------------------------------
 */
/**
 * Get template mappings for CSS enqueuing
 *
 * @return array<string, array<string, string>>
 */
function mia_get_template_mappings(): array {
	/**
	 * Template mappings array.
	 *
	 * @var array<string, array<string, string>>
	 */
	return array(
		// Page Templates (available for selection).
		'page-blank-canvas'           => array(
			'css' => 'templates/pages/page-blank-canvas.css',
			'js'  => 'templates/pages/page-blank-canvas.js',
		),
		'page-hero-canvas'            => array(
			'css' => 'templates/pages/page-hero-canvas.css',
			'js'  => 'templates/pages/page-hero-canvas.js',
		),
		'page-hero-canvas-no-bc'      => array(
			'css' => 'templates/pages/page-hero-canvas-no-bc.css',
			'js'  => 'templates/pages/page-hero-canvas-no-bc.js',
		),
		'page-no-bc'                  => array(
			'css' => 'templates/pages/page-no-bc.css',
			'js'  => 'templates/pages/page-no-bc.js',
		),
		'page-before-after-by-doctor' => array(
			'css' => 'templates/pages/page-before-after-by-doctor.css',
			'js'  => 'templates/pages/page-before-after-by-doctor.js',
		),
		'page-case-category'          => array(
			'css' => 'templates/pages/page-case-category.css',
			'js'  => 'templates/pages/page-case-category.js',
		),
		'page-treatment-layout'       => array(
			'css' => 'templates/pages/page-treatment-layout.css',
			'js'  => 'templates/pages/page-treatment-layout.js',
		),
		'page-condition-layout'       => array(
			'css' => 'templates/pages/page-condition-layout.css',
			'js'  => 'templates/pages/page-condition-layout.js',
		),
		'page-careers'                => array(
			'css' => 'templates/pages/page-careers.css',
			'js'  => 'templates/pages/page-careers.js',
		),
		'page-careers-locations'      => array(
			'css' => 'utilities/careers-locations.css',
			'js'  => 'utilities/careers-locations.js',
		),
		'page-procedures-listing'     => array(
			'css' => 'templates/pages/page-procedures-listing.css',
			'js'  => 'templates/pages/page-procedures-listing.js',
		),
		'page-procedures-manual'      => array(
			'css' => 'templates/pages/page-procedures-manual.css',
			'js'  => 'templates/pages/page-procedures-manual.js',
		),

		// Core WordPress Templates.
		'front-page'                  => array(
			'css' => 'templates/pages/front-page.css',
			'js'  => 'templates/pages/front-page.js',
		),
		'404'                         => array(
			'css' => 'templates/misc/404.css',
			'js'  => 'templates/misc/404.js',
		),
		'search'                      => array(
			'css' => 'templates/misc/search.css',
			'js'  => 'templates/misc/search.js',
		),
		'case-category'               => array(
			'css' => 'templates/misc/case-category.css',
			'js'  => 'templates/misc/case-category.js',
		),
		'category'                    => array(
			'css' => 'templates/misc/category.css',
			'js'  => 'templates/misc/category.js',
		),
		'archive'                     => array(
			'css' => 'templates/archives/archive.css',
			'js'  => 'templates/archives/archive.js',
		),
		'single-post'                 => array(
			'css' => 'templates/singles/single-post.css',
			'js'  => 'templates/singles/single-post.js',
		),
		'page'                        => array(
			'css' => 'templates/pages/page.css',
			'js'  => 'templates/pages/page.js',
		),

		// Default Single Templates (fallbacks for fixed post types).
		'single-case'                 => array(
			'css' => 'templates/singles/single-case.css',
			'js'  => 'templates/singles/single-case.js',
		),
		'single-condition'            => array(
			'css' => 'templates/pages/page-condition-layout.css',
			'js'  => 'templates/pages/page-condition-layout.js',
		),
		'single-location'             => array(
			'css' => 'templates/singles/single-location.css',
			'js'  => 'templates/singles/single-location.js',
		),
		'single-surgeon'              => array(
			'css' => 'templates/singles/single-surgeon.css',
			'js'  => 'templates/singles/single-surgeon.js',
		),
		'single-special'              => array(
			'css' => 'templates/singles/single-special.css',
			'js'  => 'templates/singles/single-special.js',
		),

		// Archive Templates.
		'archive-case'                => array(
			'css' => 'templates/archives/archive-case.css',
			'js'  => 'templates/archives/archive-case.js',
		),
		'archive-condition'           => array(
			'css' => 'templates/archives/archive-condition.css',
			'js'  => 'templates/archives/archive-condition.js',
		),
		'archive-fat-transfer'        => array(
			'css' => 'templates/pages/page-condition-layout.css',
			'js'  => 'templates/pages/page-condition-layout.js',
		),
		'archive-location'            => array(
			'css' => 'templates/archives/archive-location.css',
			'js'  => 'templates/archives/archive-location.js',
		),
		'archive-non-surgical'        => array(
			'css' => 'templates/archives/archive-non-surgical.css',
			'js'  => 'templates/archives/archive-non-surgical.js',
		),
		'archive-procedure'           => array(
			'css' => 'templates/archives/archive-procedure.css',
			'js'  => 'templates/archives/archive-procedure.js',
		),
		'archive-special'             => array(
			'css' => 'templates/archives/archive-special.css',
			'js'  => 'templates/archives/archive-special.js',
		),
		'archive-surgeon'             => array(
			'css' => 'templates/archives/archive-surgeon.css',
			'js'  => 'templates/archives/archive-surgeon.js',
		),

		// Home & Index.
		'home'                        => array(
			'css' => 'templates/misc/home.css',
			'js'  => 'templates/misc/home.js',
		),
		'index'                       => array(
			'css' => 'templates/misc/index.css',
			'js'  => 'templates/misc/index.js',
		),
	);
}

/**
 * Detect the current template for asset loading.
 * Priority: Selected Template > Default Template > Fallback
 *
 * @return string Template key for asset mapping.
 */
function mia_detect_template_key(): string {
	// 1. Check for user-selected template (highest priority)
	if ( is_singular() || is_page() ) {
		$selected_template = get_page_template_slug();
		if ( '' !== $selected_template ) {
			$template_key = str_replace( '.php', '', (string) $selected_template );
			if ( array_key_exists( $template_key, mia_get_template_mappings() ) ) {
				return $template_key;
			}
		}
	}

	// 2. WordPress core pages
	if ( is_front_page() ) {
		return 'front-page';
	}

	if ( is_404() ) {
		return '404';
	}

	if ( is_search() ) {
		return 'search';
	}

	if ( is_tax( 'case-category' ) ) {
		return 'case-category';
	}

	if ( is_category() ) {
		return 'category';
	}

	// 3. Check for blog/posts page BEFORE generic archive check
	// is_home() is true for the posts page when set in Settings > Reading.
	// This condition specifically handles when posts page is separate from front page.
	if ( is_home() ) {
		// @phpstan-ignore-next-line booleanNot.alwaysTrue (WordPress context: is_front_page() can be true when is_home() is true)
		if ( ! is_front_page() ) {
			// This is the blog posts page (separate from front page) - use archive template.
			return 'archive';
		}
	}

	// 4. Archive pages
	if ( is_archive() && get_post_type() === 'post' ) {
		return 'archive';
	}

	if ( is_post_type_archive() ) {
		$post_type = get_post_type();
		if ( false === $post_type ) {
			$post_type = get_query_var( 'post_type' );
		}
		$archive_template = 'archive-' . $post_type;
		if ( array_key_exists( $archive_template, mia_get_template_mappings() ) ) {
			return $archive_template;
		}

		return 'archive'; // Fallback to generic archive.
	}

	// 5. Single posts/pages
	if ( is_singular( 'post' ) ) {
		return 'single-post';
	}

	if ( is_page() ) {
		return 'page';
	}

	// 6. Custom post type singles (fallback to default templates)
	if ( is_singular() ) {
		$post_type       = get_post_type();
		$single_template = 'single-' . $post_type;
		if ( array_key_exists( $single_template, mia_get_template_mappings() ) ) {
			return $single_template;
		}
	}

	// 7. Final fallback
	return 'index';
}

/**
 * ---------------------------------------------------------------------------
 * Main enqueue callback
 * ---------------------------------------------------------------------------
 *
 * @return void
 */
function mia_enqueue_assets(): void {
	// ------------------------ Critical/global assets -----------------------.
	mia_register_asset( 'style', 'mia-fonts', '/css/fonts.css' );
	mia_register_asset( 'style', 'mia-bootstrap', '/bootstrap/css/bootstrap.min.css', array( 'mia-fonts' ) );
	mia_register_asset( 'style', 'mia-base', '/css/base.css', array( 'mia-bootstrap' ) );

	mia_register_asset( 'style', 'mia-fontawesome', '/fontawesome/css/all.min.css', array( 'mia-base' ) );
	mia_register_asset( 'style', 'mia-header', '/css/layout/header.css', array( 'mia-base', 'mia-bootstrap' ) );
	mia_register_asset( 'style', 'mia-footer', '/css/layout/footer.css', array( 'mia-base', 'mia-bootstrap' ) );

	// Register location search assets (loaded on demand).
	mia_register_asset( 'style', 'mia-location-search', '/css/utilities/location-search.css', array( 'mia-base' ) );
	mia_register_asset( 'script', 'mia-location-search', '/js/utilities/location-search.js', array() );
	mia_register_asset( 'style', 'mia-location-search-careers', '/css/utilities/location-search-careers.css', array( 'mia-base' ) );
	mia_register_asset( 'script', 'mia-location-search-careers', '/js/utilities/location-search-careers.js', array() );

	// Register CTA component assets (loaded conditionally).
	mia_register_asset( 'style', 'mia-consultation-cta', '/css/components/consultation-cta.css', array( 'mia-base' ) );
	mia_register_asset( 'style', 'mia-careers-cta', '/css/components/careers-cta.css', array( 'mia-base' ) );

	// Register case card component (loaded on demand by case-related templates).
	mia_register_asset( 'style', 'mia-case-card', '/css/components/case-card.css', array( 'mia-base' ) );
	

	mia_register_asset( 'script', 'mia-bootstrap', '/bootstrap/js/bootstrap.bundle.min.js' ); // no jQuery.
	mia_register_asset( 'script', 'mia-header', '/js/layout/header.js', array( 'mia-bootstrap' ) );

	// ------------------------ Template-specific assets ---------------------.
	$template_key = mia_detect_template_key();
	$templates    = mia_get_template_mappings();

	if ( '' !== $template_key && isset( $templates[ $template_key ] ) ) {
		$template = $templates[ $template_key ];

		if ( isset( $template['css'] ) && '' !== $template['css'] ) {
			$css_deps = array( 'mia-base', 'mia-header', 'mia-footer' );

			// Add hero section CSS dependency for front page.
			if ( 'front-page' === $template_key ) {
				mia_register_asset( 'style', 'mia-hero-section', '/css/layout/hero-section.css', array( 'mia-base', 'mia-bootstrap' ) );
				$css_deps[] = 'mia-hero-section';

				// Add Glide.js for video carousel (JavaScript only - CSS handled by theme).
				wp_enqueue_script( 'glide-js', 'https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/glide.min.js', array(), '3.6.0', true );
			}

			// Add case card component dependency for templates that actually use the component.
			if ( 'page-case-category' === $template_key ) {
				$css_deps[] = 'mia-case-card';
			}

			mia_register_asset( 'style', 'mia-' . $template_key, '/css/' . $template['css'], $css_deps );
		}

		if ( isset( $template['js'] ) && '' !== $template['js'] ) {
			mia_register_asset( 'script', 'mia-' . $template_key, '/js/' . $template['js'], array( 'mia-bootstrap' ) );
		}
	}

	// ------------------------ CTA Component Loading -------------------------.
	// Load consultation CTA for all pages except careers pages
	if ( ! is_page_template( 'page-careers.php' ) && ! is_page_template( 'page-careers-locations.php' ) &&
		! is_page( 'careers' ) && ! is_page( 'careers-locations' ) ) {
		wp_enqueue_style( 'mia-consultation-cta' );
	}

	// Load careers CTA for careers pages only.
	if ( is_page_template( 'page-careers.php' ) || is_page_template( 'page-careers-locations.php' ) ||
		is_page( 'careers' ) || is_page( 'careers-locations' ) ) {
		wp_enqueue_style( 'mia-careers-cta' );
	}


	// ------------------------ Enqueue registered ---------------------------.
	foreach ( wp_styles()->registered as $h => $_ ) {
		// Skip assets loaded on-demand by components.
		$skip_assets = array( 'mia-location-search', 'mia-location-search-careers', 'mia-consultation-cta', 'mia-careers-cta', 'mia-case-card' );
		if ( ( str_starts_with( $h, 'mia-' ) || in_array( $h, array( 'mia-fonts', 'mia-bootstrap', 'mia-base', 'mia-fontawesome' ), true ) ) && ! in_array( $h, $skip_assets, true ) ) {
			wp_enqueue_style( $h );
		}
	}

	foreach ( wp_scripts()->registered as $h => $_ ) {
		// Skip location search assets - they are loaded on-demand by the component.
		if ( str_starts_with( $h, 'mia-' ) && ( 'mia-location-search' !== $h && 'mia-location-search-careers' !== $h ) ) {
			wp_enqueue_script( $h );
		}
	}

	// Attach runtime configuration.
	mia_attach_config();
}

add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );

/**
 * Localise runtime configuration to the primary script.
 *
 * @return void
 */
function mia_attach_config(): void {
	$primary = mia_get_primary_script_handle();
	if ( '' === $primary || ! wp_script_is( $primary, 'registered' ) ) {
		return;
	}

	wp_localize_script(
		$primary,
		'mia_config',
		array(
			'theme_url' => get_template_directory_uri(),
			'site_url'  => home_url(),
			'is_mobile' => wp_is_mobile(),
			'debug'     => WP_DEBUG,
			'ajax_url'  => admin_url( 'admin-ajax.php' ),
			'nonce'     => wp_create_nonce( 'mia_ajax' ),
		)
	);
}

/**
 * Determine the primary bundle for localisation.
 *
 * @return string Handle of the primary script.
 */
function mia_get_primary_script_handle() {
	$template_key = mia_detect_template_key();

	if ( '' !== $template_key && wp_script_is( 'mia-' . $template_key, 'registered' ) ) {
		return 'mia-' . $template_key;
	}

	if ( wp_script_is( 'mia-header', 'registered' ) ) {
		return 'mia-header';
	}

	return 'mia-bootstrap';
}

/**
 * Add dns‑prefetch resource hints for external domains.
 *
 * @param array<int, string> $hints         Array of resource hints.
 * @param string             $relation_type The relation type of the resource hint.
 * @return array<int, string> Modified array of resource hints.
 */
function mia_resource_hints( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$hints[] = '//fonts.googleapis.com';
		$hints[] = '//www.google-analytics.com';
		// Additional hint for CDN if Font Awesome is served externally.
	}

	return $hints;
}

add_filter( 'wp_resource_hints', 'mia_resource_hints', 10, 2 );
