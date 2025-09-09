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
	$start_time = microtime( true );

	if ( mia_is_debug_mode() ) {
		mia_debug_log( '🎨 === MIA AESTHETICS ASSET LOADING START ===' );
		mia_debug_log( '📄 Template: ' . mia_get_current_template_key() );
	}

	// Load global assets (always needed).
	mia_enqueue_global_assets();

	// Load template-specific assets.
	mia_enqueue_template_assets();

	// Load conditional components (ACF-aware).
	mia_enqueue_conditional_components();

	if ( mia_is_debug_mode() ) {
		$end_time  = microtime( true );
		$load_time = round( ( $end_time - $start_time ) * 1000, 2 );
		mia_debug_log( "⏱️ Asset loading completed in {$load_time}ms" );
		mia_debug_log( '🎨 === MIA AESTHETICS ASSET LOADING END ===' );

		// Hook to show debug info in footer for logged-in users.
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'wp_footer', 'mia_debug_output_assets', 999 );
		}
	}
}

/**
 * Enqueue global assets that are needed on every page
 */
function mia_enqueue_global_assets(): void {
	// Global CSS - Foundation styles.
	wp_enqueue_style( 'mia-fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), MIA_THEME_VERSION );
	wp_enqueue_style( 'mia-bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/css/bootstrap.min.css', array( 'mia-fonts' ), '5.3.8' );
	wp_enqueue_style( 'mia-base', get_template_directory_uri() . '/assets/css/base.css', array( 'mia-bootstrap' ), MIA_THEME_VERSION );
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

	// Location Search Careers - Load on careers pages with location search.
	if ( mia_needs_location_search_careers() ) {
		mia_load_component_assets( 'location-search-careers' );
		mia_enqueue_google_maps( 'initGoogleMapsCareers' );
	}
}

/**
 * Check if current page needs consultation form assets
 */
function mia_has_consultation_form(): bool {
	// Check for pages with consultation form templates.
	if ( is_page_template( 'page-condition-layout.php' ) ||
		is_page_template( 'page-treatment-layout.php' ) ||
		is_singular( 'special' ) ) {
		return true;
	}

	// Check for ACF field that indicates a form is present.
	if ( function_exists( 'get_field' ) && get_field( 'show_consultation_form' ) ) {
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
 * Enqueue Google Maps API with specified callback
 *
 * @param string $callback The JavaScript callback function name.
 */
function mia_enqueue_google_maps( string $callback ): void {
	if ( ! wp_script_is( 'google-maps', 'registered' ) ) {
		$api_key = get_field( 'google_maps_api_key', 'option' );
		if ( $api_key ) {
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

	// Load JS (will fail gracefully if file doesn't exist).
	wp_enqueue_script(
		"mia-{$component}",
		get_template_directory_uri() . "/assets/js/components/{$component}.js",
		array(),
		MIA_THEME_VERSION,
		true
	);
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
	// Check for page templates first (on any post type, not just pages).
	$template = get_page_template_slug();
	if ( $template ) {
		return basename( $template, '.php' );
	}

	// Check for front page first (special case).
	if ( is_front_page() ) {
		return 'front-page';
	}

	// Simple conditional checks.
	$simple_checks = array(
		'is_home'     => 'home',
		'is_404'      => '404',
		'is_search'   => 'search',
		'is_category' => 'category',
		'is_page'     => 'page',
	);

	foreach ( $simple_checks as $function => $template ) {
		if ( call_user_func( $function ) ) {
			return $template;
		}
	}

	// Custom post type archives.
	$archive_types = array( 'case', 'condition', 'location', 'non-surgical', 'procedure', 'special', 'surgeon', 'fat-transfer' );
	foreach ( $archive_types as $type ) {
		if ( is_post_type_archive( $type ) ) {
			return "archive-{$type}";
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

	// Front page needs hero section styles and Glide.js.
	if ( is_front_page() ) {
		wp_enqueue_style(
			'mia-hero-section',
			get_template_directory_uri() . '/assets/css/hero-section.css',
			array( 'mia-base' ),
			MIA_THEME_VERSION
		);
		wp_enqueue_script(
			'mia-glide',
			get_template_directory_uri() . '/assets/vendor/glide/js/glide.min.js',
			array(),
			'3.7.1',
			true
		);
	}

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
		wp_enqueue_style(
			'mia-' . $template_key,
			get_template_directory_uri() . '/assets/' . $asset_map[ $template_key ]['css'],
			array( 'mia-base' ),
			MIA_THEME_VERSION
		);
	}

	// Load JS for this template.
	if ( isset( $asset_map[ $template_key ]['js'] ) ) {
		wp_enqueue_script(
			'mia-' . $template_key,
			get_template_directory_uri() . '/assets/' . $asset_map[ $template_key ]['js'],
			array( 'mia-bootstrap' ),
			MIA_THEME_VERSION,
			true
		);
	}
}

/**
 * Get template-to-asset mapping (clean, organized by category)
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
		'page'                        => array(
			'css' => 'pages/page.css',
			'js'  => 'pages/page.js',
		),

		// Archive Pages.
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

		// Single Post Types.
		'single-case'                 => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		),
		'single-condition'            => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		),
		'single-location'             => array(
			'css' => 'singles/single-location.css',
			'js'  => 'singles/single-location.js',
		),
		'single-non-surgical'         => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		),
		'single-procedure'            => array(
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
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
			'css' => 'singles/single-case.css',
			'js'  => 'singles/single-case.js',
		),
		'single-post'                 => array(
			'css' => 'singles/single-post.css',
			'js'  => 'singles/single-post.js',
		),
	);
}

/**
 * Debug Functions
 * =============================================================================
 */

/**
 * Check if debug mode is enabled
 *
 * @return bool True if debug mode is enabled.
 */
function mia_is_debug_mode(): bool {
	return ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
}

/**
 * Log debug message to error log
 *
 * @param string $message The message to log.
 */
function mia_debug_log( string $message ): void {
	if ( mia_is_debug_mode() ) {
		error_log( '[MIA THEME] ' . $message );
	}
}

/**
 * Output debug asset information in footer for admins
 */
function mia_debug_output_assets(): void {
	global $wp_styles, $wp_scripts;

	$mia_styles     = array();
	$mia_scripts    = array();
	$total_css_size = 0;
	$total_js_size  = 0;

	// Collect MIA theme assets.
	foreach ( $wp_styles->done as $handle ) {
		if ( strpos( $handle, 'mia-' ) === 0 ) {
			$src             = $wp_styles->registered[ $handle ]->src ?? '';
			$size            = mia_get_asset_size( $src );
			$mia_styles[]    = array(
				'handle' => $handle,
				'src'    => $src,
				'size'   => $size,
			);
			$total_css_size += $size;
		}
	}

	foreach ( $wp_scripts->done as $handle ) {
		if ( strpos( $handle, 'mia-' ) === 0 ) {
			$src            = $wp_scripts->registered[ $handle ]->src ?? '';
			$size           = mia_get_asset_size( $src );
			$mia_scripts[]  = array(
				'handle' => $handle,
				'src'    => $src,
				'size'   => $size,
			);
			$total_js_size += $size;
		}
	}

	?>
	<div id="mia-debug-assets" style="position: fixed; bottom: 10px; right: 10px; background: #1e1e1e; color: #fff; padding: 15px; border-radius: 8px; font-family: monospace; font-size: 12px; max-width: 400px; max-height: 300px; overflow-y: auto; z-index: 9999; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
		<div style="font-weight: bold; margin-bottom: 10px; color: #00d4aa;">🎨 MIA Theme Assets Debug</div>
		<div style="margin-bottom: 8px;">📄 <strong>Template:</strong> <?php echo esc_html( mia_get_current_template_key() ); ?></div>
		
		<div style="margin-bottom: 8px;">
			<strong>📊 CSS Files (<?php echo count( $mia_styles ); ?>):</strong> 
			<span style="color: #00d4aa;"><?php echo esc_html( mia_format_bytes( $total_css_size ) ); ?></span>
		</div>
		<?php foreach ( $mia_styles as $style ) : ?>
			<div style="margin-left: 10px; font-size: 10px; color: #ccc;">
				• <?php echo esc_html( $style['handle'] ); ?> 
				<span style="color: #888;">(<?php echo esc_html( mia_format_bytes( $style['size'] ) ); ?>)</span>
			</div>
		<?php endforeach; ?>
		
		<div style="margin: 8px 0;">
			<strong>⚡ JS Files (<?php echo count( $mia_scripts ); ?>):</strong> 
			<span style="color: #00d4aa;"><?php echo esc_html( mia_format_bytes( $total_js_size ) ); ?></span>
		</div>
		<?php foreach ( $mia_scripts as $script ) : ?>
			<div style="margin-left: 10px; font-size: 10px; color: #ccc;">
				• <?php echo esc_html( $script['handle'] ); ?> 
				<span style="color: #888;">(<?php echo esc_html( mia_format_bytes( $script['size'] ) ); ?>)</span>
			</div>
		<?php endforeach; ?>
		
		<div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #444; font-size: 10px; color: #888;">
			💾 Total: <?php echo esc_html( mia_format_bytes( $total_css_size + $total_js_size ) ); ?> | 
			⏰ <?php echo esc_html( current_time( 'H:i:s' ) ); ?>
		</div>
		
		<button onclick="this.parentElement.style.display='none'" style="position: absolute; top: 5px; right: 8px; background: none; border: none; color: #fff; cursor: pointer; font-size: 14px;">&times;</button>
	</div>
	<?php
}

/**
 * Get file size for an asset URL
 *
 * @param string $url The asset URL.
 * @return int File size in bytes, 0 if not found.
 */
function mia_get_asset_size( string $url ): int {
	if ( empty( $url ) ) {
		return 0;
	}

	// Convert URL to local file path.
	$upload_dir = wp_upload_dir();
	$theme_url  = get_template_directory_uri();

	if ( strpos( $url, $theme_url ) === 0 ) {
		$file_path = str_replace( $theme_url, get_template_directory(), $url );
		if ( file_exists( $file_path ) ) {
			return filesize( $file_path );
		}
	}

	return 0;
}

/**
 * Format bytes into human readable format
 *
 * @param int $bytes File size in bytes.
 * @return string Formatted file size.
 */
function mia_format_bytes( int $bytes ): string {
	if ( 0 === $bytes ) {
		return '0 B';
	}

	$units = array( 'B', 'KB', 'MB', 'GB' );
	$pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
	$pow   = min( $pow, count( $units ) - 1 );

	$bytes /= pow( 1024, $pow );

	return round( $bytes, 1 ) . ' ' . $units[ $pow ];
}

/**
 * Helper Functions
 * =============================================================================
 */


add_action( 'wp_enqueue_scripts', 'mia_enqueue_assets' );
