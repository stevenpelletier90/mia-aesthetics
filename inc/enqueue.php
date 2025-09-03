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

/**
 * Debug array to track asset loading status
 *
 * @var array<string, array<string, mixed>>
 */
global $mia_debug_assets;
$mia_debug_assets = array();

/**
 * Add asset debug information
 *
 * @param string $handle Asset handle.
 * @param string $type   Asset type (style|script).
 * @param string $path   Requested path.
 * @param string $status Status (found|fallback|missing).
 * @param string $final_path Final path used.
 * @return void
 */
function mia_debug_add_asset( $handle, $type, $path, $status, $final_path = '' ): void {
	if ( ! WP_DEBUG ) {
		return;
	}

	global $mia_debug_assets;
	$mia_debug_assets[ $handle ] = array(
		'type'       => $type,
		'requested'  => $path,
		'status'     => $status,
		'final_path' => $final_path,
		'timestamp'  => microtime( true ),
	);
}

/**
 * Display debug information for assets
 *
 * @return void
 */
function mia_debug_display_assets(): void {
	if ( ! WP_DEBUG || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $mia_debug_assets;
	if ( empty( $mia_debug_assets ) ) {
		return;
	}

	$template_slug = get_page_template_slug();
	$context       = is_front_page() ? 'front-page' : (string) get_post_type();

	// Show the intended template, not the fallback WordPress uses.
	if ( 'front-page' === $context ) {
		$template = 'front-page.php';
	} else {
		$template = '' !== $template_slug ? $template_slug : basename( get_page_template() );
	}

	echo '<div id="mia-asset-debug" style="position: fixed; top: 32px; right: 10px; background: #23282d; color: #fff; padding: 15px; border-radius: 5px; max-width: 400px; max-height: 70vh; overflow-y: auto; z-index: 999999; font-family: monospace; font-size: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">';
	echo '<h4 style="margin: 0 0 10px; color: #00a0d2; font-size: 14px;">🔍 Mia Assets Debug</h4>';
	echo '<p style="margin: 5px 0; font-size: 11px;"><strong>Template:</strong> ' . esc_html( (string) $template ) . '</p>';
	echo '<p style="margin: 5px 0; font-size: 11px;"><strong>Context:</strong> ' . esc_html( $context ) . '</p>';
	echo '<hr style="border-color: #444; margin: 10px 0;">';

	$missing_count  = 0;
	$fallback_count = 0;

	foreach ( $mia_debug_assets as $handle => $info ) {
		$color = '#00d084'; // Green for found.
		$icon  = '✓';

		if ( 'missing' === $info['status'] ) {
			$color = '#dc3232'; // Red for missing.
			$icon  = '✗';
			++$missing_count;
		} elseif ( 'fallback' === $info['status'] ) {
			$color = '#ffb900'; // Yellow for fallback.
			$icon  = '⚠';
			++$fallback_count;
		}

		echo '<div style="margin: 8px 0; padding: 5px; background: rgba(255,255,255,0.05); border-radius: 3px;">';
		echo '<span style="color: ' . esc_attr( $color ) . '; font-weight: bold;">' . esc_html( $icon ) . '</span> ';
		echo '<strong style="color: #00a0d2;">' . esc_html( $handle ) . '</strong> ';
		echo '<span style="color: #ccc;">(' . esc_html( $info['type'] ) . ')</span><br>';
		echo '<span style="font-size: 10px; color: #999;">Requested: </span>';
		echo '<span style="font-size: 10px;">' . esc_html( $info['requested'] ) . '</span><br>';

		if ( ! empty( $info['final_path'] ) ) {
			echo '<span style="font-size: 10px; color: #999;">Final: </span>';
			echo '<span style="font-size: 10px; color: ' . esc_attr( $color ) . ';">' . esc_html( $info['final_path'] ) . '</span>';
		}
		echo '</div>';
	}

	if ( $missing_count > 0 || $fallback_count > 0 ) {
		echo '<hr style="border-color: #444; margin: 10px 0;">';
		echo '<p style="margin: 5px 0; font-size: 11px;">';
		if ( $missing_count > 0 ) {
			echo '<span style="color: #dc3232;">⚠ ' . absint( $missing_count ) . ' missing assets</span><br>';
		}
		if ( $fallback_count > 0 ) {
			echo '<span style="color: #ffb900;">⚠ ' . absint( $fallback_count ) . ' using fallbacks</span>';
		}
		echo '</p>';
	}

	echo '<div style="margin-top: 10px; text-align: right;">';
	echo '<button onclick="document.getElementById(\'mia-asset-debug\').style.display=\'none\'" style="background: #dc3232; color: white; border: none; padding: 3px 8px; border-radius: 3px; cursor: pointer; font-size: 10px;">Close</button>';
	echo '</div>';
	echo '</div>';
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

	$original_path = $path;
	$status        = 'found';

	// In production mode, try to use minified version if available.
	if ( ! WP_DEBUG && ! str_contains( $path, '.min.' ) ) {
		$extension     = 'style' === $type ? '.css' : '.js';
		$min_extension = 'style' === $type ? '.min.css' : '.min.js';
		$min_path      = str_replace( $extension, $min_extension, $path );
		$min_file      = wp_normalize_path( $base_dir . $min_path );

		// Use minified version if it exists.
		if ( file_exists( $min_file ) ) {
			$path   = $min_path;
			$status = 'minified';
		}
	}

	$src  = $base_uri . $path;
	$file = wp_normalize_path( $base_dir . $path );

	// If the requested asset doesn't exist (common when bundling only minified files),
	// gracefully fall back to the .min version even in debug mode.
	if ( ! file_exists( $file ) ) {
		$extension     = 'style' === $type ? '.css' : '.js';
		$min_extension = 'style' === $type ? '.min.css' : '.min.js';
		$min_path      = str_contains( $path, '.min.' ) ? $path : str_replace( $extension, $min_extension, $path );
		$min_file      = wp_normalize_path( $base_dir . $min_path );
		if ( file_exists( $min_file ) ) {
			$path   = $min_path;
			$src    = $base_uri . $path;
			$file   = $min_file;
			$status = 'fallback';
		} else {
			$status = 'missing';
		}
	}

	// Add debug information.
	mia_debug_add_asset( $handle, $type, $original_path, $status, $path );

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
 * Register component assets based on current template
 *
 * @param string $template_key Current template context.
 * @return void
 */
function mia_register_template_components( $template_key ): void {
	$component_mapping = mia_get_template_component_mapping();
	$components        = $component_mapping[ $template_key ] ?? array();

	// Component asset definitions.
	$component_assets = array(
		'careers-cta'             => array(
			'css' => '/css/components/careers-cta.css',
		),
		'case-card'               => array(
			'css' => '/css/components/case-card.css',
		),
		'consultation-form'       => array(
			'css' => '/css/components/consultation-form.css',
		),
		'consultation-cta'        => array(
			'css' => '/css/components/consultation-cta.css',
		),
		'faq'                     => array(
			'css' => '/css/components/faq.css',
		),
		'location-search'         => array(
			'js' => '/js/utilities/location-search.js',
		),
		'location-search-careers' => array(
			'js' => '/js/utilities/location-search-careers.js',
		),
	);

	// Register only the components needed by this template.
	foreach ( $components as $component ) {
		if ( isset( $component_assets[ $component ] ) ) {
			$assets = $component_assets[ $component ];

			// Register CSS if defined.
			if ( isset( $assets['css'] ) ) {
				mia_register_asset( 'style', 'mia-' . $component, $assets['css'], array( 'mia-theme' ) );
			}

			// Register JS if defined.
			if ( isset( $assets['js'] ) ) {
				$js_deps = array( 'mia-bootstrap' );
				// Location search scripts need special dependencies.
				if ( 'location-search-careers' === $component ) {
					$js_deps[] = 'mia-location-search';
				}
				mia_register_asset( 'script', 'mia-' . $component, $assets['js'], $js_deps );
			}
		}
	}
}

/**
 * Register Glide.js assets with CDN fallback
 *
 * @return void
 */
function mia_register_glide_assets(): void {
	$base_uri = trailingslashit( get_template_directory_uri() ) . 'assets';
	$base_dir = trailingslashit( get_template_directory() ) . 'assets';

	// Check for local Glide.js files.
	$glide_css_file = wp_normalize_path( $base_dir . '/glide/css/glide.core.min.css' );
	$glide_js_file  = wp_normalize_path( $base_dir . '/glide/js/glide.min.js' );

	// Register CSS (local or CDN fallback).
	if ( file_exists( $glide_css_file ) ) {
		// Use local file with versioning.
		$css_ver = (string) filemtime( $glide_css_file );
		$css_src = $base_uri . '/glide/css/glide.core.min.css';
	} else {
		// Fallback to CDN.
		$css_ver = '3.6.0';
		$css_src = 'https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/css/glide.core.min.css';
	}
	wp_register_style( 'mia-glide', $css_src, array( 'mia-theme' ), $css_ver );

	// Register JS (local or CDN fallback).
	if ( file_exists( $glide_js_file ) ) {
		// Use local file with versioning.
		$js_ver = (string) filemtime( $glide_js_file );
		$js_src = $base_uri . '/glide/js/glide.min.js';
	} else {
		// Fallback to CDN.
		$js_ver = '3.6.0';
		$js_src = 'https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.6.0/glide.min.js';
	}
	wp_register_script( 'mia-glide', $js_src, array(), $js_ver, true );
	wp_script_add_data( 'mia-glide', 'strategy', 'defer' );
}

/**
 * ---------------------------------------------------------------------------
 * Context mappings (single source of truth for CSS/JS filenames)
 * ---------------------------------------------------------------------------
 */
/**
 * Get template-component mapping for modular asset loading
 *
 * Maps each template to the components it actually uses.
 * This ensures only necessary component assets are loaded.
 *
 * @return array<int|string, array<string>>
 */
function mia_get_template_component_mapping(): array {
	return array(
		// Pages with no components.
		'front-page'             => array(),
		'page'                   => array(),
		'404'                    => array(),
		'search'                 => array(),
		'index'                  => array(),
		'home'                   => array(),
		'category'               => array(),
		'case-category'          => array(),

		// Pages with specific components.
		'page-careers'           => array( 'careers-cta' ),
		'page-careers-locations' => array( 'careers-cta', 'location-search', 'location-search-careers' ),
		'page-case-category'     => array( 'case-card' ),
		'page-treatment-layout'  => array( 'consultation-form', 'faq' ),
		'page-condition-layout'  => array( 'consultation-form', 'faq' ),

		// Archives.
		'archive-case'           => array( 'case-card' ),
		'archive-condition'      => array(),
		'archive-location'       => array(),
		'archive-non-surgical'   => array(),
		'archive-procedure'      => array(),
		'archive-special'        => array(),
		'archive-surgeon'        => array(),
		'archive'                => array(),
		'archive-fat-transfer'   => array( 'faq' ),

		// Singles.
		'single-case'            => array( 'case-card', 'faq' ), // Related cases + FAQ.
		'single-location'        => array(),
		'single-post'            => array(),
		'single-special'         => array( 'consultation-form' ),
		'single-surgeon'         => array( 'case-card' ), // Surgeon's cases.
		'single-condition'       => array( 'consultation-form', 'faq' ),
		'single-fat-transfer'    => array( 'consultation-form', 'faq' ),
	);
}

/**
 * Get template mappings for CSS enqueuing
 *
 * Note: Keys include '404' which PHP treats as integer key at runtime,
 * so allow int|string for top-level keys.
 *
 * @return array<int|string, array<string, string>>
 */
function mia_get_template_mappings(): array {
	// Template mappings array.
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
			'css' => 'templates/pages/page-hero-canvas.css', // Shared CSS with page-hero-canvas.
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
		'single-fat-transfer'         => array(
			'css' => 'templates/pages/page-condition-layout.css',
			'js'  => 'templates/pages/page-condition-layout.js',
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
 * Priority tuned to match WP template hierarchy for front page.
 *
 * @return string Template key for asset mapping.
 */
function mia_detect_template_key(): string {
	// 1. Front page should take precedence to match WP's template hierarchy.
	if ( is_front_page() ) {
		return 'front-page';
	}

	// 2. Check for user-selected template (next priority for singular pages).
	if ( is_singular() || is_page() ) {
		$selected_template = get_page_template_slug();
		if ( '' !== $selected_template ) {
			$template_key = str_replace( '.php', '', (string) $selected_template );
			if ( array_key_exists( $template_key, mia_get_template_mappings() ) ) {
				return $template_key;
			}
		}
	}

	// 3. WordPress core pages

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

	// 4. Check for blog/posts page BEFORE generic archive check
	// is_home() is true for the posts page when set in Settings > Reading.
	// This condition specifically handles when posts page is separate from front page.
	if ( is_home() ) {
		// @phpstan-ignore-next-line booleanNot.alwaysTrue (WordPress context: is_front_page() can be true when is_home() is true)
		if ( ! is_front_page() ) {
			// This is the blog posts page (separate from front page) - use archive template.
			return 'archive';
		}
	}

	// 5. Archive pages
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

	// 6. Single posts/pages
	if ( is_singular( 'post' ) ) {
		return 'single-post';
	}

	if ( is_page() ) {
		return 'page';
	}

	// 7. Custom post type singles (fallback to default templates)
	if ( is_singular() ) {
		$post_type       = get_post_type();
		$single_template = 'single-' . $post_type;
		if ( array_key_exists( $single_template, mia_get_template_mappings() ) ) {
			return $single_template;
		}
	}

	// 8. Final fallback
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
    // ------------------------ Core Assets (Always Load) -----------------------.
    // These assets are needed on every page
    mia_register_asset( 'style', 'mia-theme', '/css/theme.css' );
    mia_register_asset( 'style', 'mia-fontawesome', '/fontawesome/css/all.min.css', array( 'mia-theme' ) );
	mia_register_asset( 'script', 'mia-bootstrap', '/bootstrap/js/bootstrap.bundle.min.js' );
	mia_register_asset( 'script', 'mia-header', '/js/layout/header.js', array( 'mia-bootstrap' ) );
	mia_register_asset( 'script', 'mia-footer', '/js/layout/footer.js', array( 'mia-bootstrap' ) );

	// ------------------------ Template Detection -----------------------.
	$template_key = mia_detect_template_key();

    // ------------------------ Component Assets (Template-Specific) -----------------------.
    // Register only the components needed by this specific template
    mia_register_template_components( $template_key );

    // Register Consultation CTA styles only when the CTA will be displayed,
    // and only if it hasn't been registered by template components already.
    if ( function_exists( 'should_show_consultation_cta' ) && should_show_consultation_cta() ) {
        if ( ! wp_style_is( 'mia-consultation-cta', 'registered' ) ) {
            mia_register_asset( 'style', 'mia-consultation-cta', '/css/components/consultation-cta.css', array( 'mia-theme' ) );
        }
    }

	// ------------------------ Layout Assets (Template-Specific) -----------------------.
	// Add hero section for front page only
	if ( 'front-page' === $template_key ) {
		mia_register_asset( 'style', 'mia-hero-section', '/css/layout/hero-section.css', array( 'mia-theme' ) );
		// Add Glide.js for video carousel with CDN fallback.
		mia_register_glide_assets();
	}

	// ------------------------ Template-Specific Assets -----------------------.
	$templates = mia_get_template_mappings();
	if ( '' !== $template_key && isset( $templates[ $template_key ] ) ) {
		$template = $templates[ $template_key ];

		// Register template CSS with proper dependencies.
		if ( isset( $template['css'] ) && '' !== $template['css'] ) {
			$css_deps = array( 'mia-theme' );

			// Add layout dependencies for specific templates.
			if ( 'front-page' === $template_key ) {
				$css_deps[] = 'mia-hero-section';
				$css_deps[] = 'mia-glide';
			}

			mia_register_asset( 'style', 'mia-' . $template_key, '/css/' . $template['css'], $css_deps );
		}

		// Register template JS.
		if ( isset( $template['js'] ) && '' !== $template['js'] ) {
			mia_register_asset( 'script', 'mia-' . $template_key, '/js/' . $template['js'], array( 'mia-bootstrap' ) );
		}
	}

	// ------------------------ Enqueue All Registered Assets -----------------------.
	// Clean, simple approach: enqueue everything that was registered
	foreach ( wp_styles()->registered as $handle => $_ ) {
		if ( str_starts_with( $handle, 'mia-' ) ) {
			wp_enqueue_style( $handle );
		}
	}

	foreach ( wp_scripts()->registered as $handle => $_ ) {
		if ( str_starts_with( $handle, 'mia-' ) ) {
			wp_enqueue_script( $handle );
		}
	}

	// Attach runtime configuration.
	mia_attach_config();
}

add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );

// Add debug display in wp_footer for logged-in admins in debug mode.
if ( WP_DEBUG ) {
	add_action( 'wp_footer', 'mia_debug_display_assets', 999 );
}

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
		$hints[] = '//www.google-analytics.com';
		// Additional hint for CDN if Font Awesome is served externally.
	}

	return $hints;
}

add_filter( 'wp_resource_hints', 'mia_resource_hints', 10, 2 );
