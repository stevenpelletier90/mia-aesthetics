<?php
/**
 * Asset Debug Helper for Mia Aesthetics Theme
 *
 * Displays all enqueued CSS/JS assets and template information
 * for administrators on the frontend for easy debugging.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add debug panel to footer for administrators
 */
function mia_add_debug_panel(): void {
	// Only show for logged-in administrators
	if ( ! current_user_can( 'administrator' ) ) {
		return;
	}

	global $wp_styles, $wp_scripts;

	// Get template information
	$template_key = function_exists( 'mia_get_current_template_key' ) ? mia_get_current_template_key() : 'unknown';
	$template_file = get_page_template_slug() ?: basename( get_page_template() );
	$post_type = get_post_type();
	$post_id = get_the_ID();

	// Get conditional component status
	$conditional_status = mia_get_conditional_component_status();

	// Get enqueued styles and scripts
	$styles = mia_get_enqueued_assets( $wp_styles );
	$scripts = mia_get_enqueued_assets( $wp_scripts );

	?>
	<div id="mia-debug-panel" style="
		position: fixed;
		bottom: 20px;
		right: 20px;
		background: rgba(0, 0, 0, 0.9);
		color: #fff;
		padding: 15px;
		border-radius: 8px;
		font-family: 'Courier New', monospace;
		font-size: 12px;
		max-width: 500px;
		max-height: 80vh;
		overflow-y: auto;
		z-index: 99999;
		box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
		transition: all 0.3s ease;
	">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; cursor: pointer;" onclick="miaToggleDebugPanel()">
			<strong style="color: #c8b273;">🐛 Asset Debug Panel</strong>
			<span id="mia-debug-toggle" style="color: #c8b273; font-size: 14px;">▼</span>
		</div>

		<div id="mia-debug-content">
			<!-- Template Information -->
			<div style="margin-bottom: 15px;">
				<strong style="color: #4CAF50;">📄 Template Info:</strong><br>
				<span style="color: #FFD700;">Key:</span> <?php echo esc_html( $template_key ); ?><br>
				<span style="color: #FFD700;">File:</span> <?php echo esc_html( $template_file ); ?><br>
				<?php if ( $post_type ): ?>
					<span style="color: #FFD700;">Post Type:</span> <?php echo esc_html( $post_type ); ?><br>
				<?php endif; ?>
				<?php if ( $post_id ): ?>
					<span style="color: #FFD700;">Post ID:</span> <?php echo esc_html( $post_id ); ?><br>
				<?php endif; ?>
			</div>

			<!-- Conditional Components Status -->
			<div style="margin-bottom: 15px;">
				<strong style="color: #FF9800;">🔄 Conditional Components:</strong><br>
				<?php foreach ( $conditional_status as $component => $status ): ?>
					<span style="color: <?php echo $status ? '#4CAF50' : '#f44336'; ?>;">
						<?php echo $status ? '✅' : '❌'; ?> <?php echo esc_html( $component ); ?>
					</span><br>
				<?php endforeach; ?>
			</div>

			<!-- CSS Assets -->
			<div style="margin-bottom: 15px;">
				<strong style="color: #2196F3;">🎨 CSS Assets (<?php echo count( $styles ); ?>):</strong><br>
				<?php foreach ( $styles as $style ): ?>
					<div style="margin: 5px 0; padding: 5px; background: rgba(255,255,255,0.1); border-radius: 3px;">
						<span style="color: <?php echo mia_get_asset_type_color( $style['handle'] ); ?>;">
							<?php echo esc_html( $style['handle'] ); ?>
						</span><br>
						<small style="color: #ccc;"><?php echo esc_html( $style['src'] ); ?></small>
						<?php if ( ! empty( $style['deps'] ) ): ?>
							<br><small style="color: #FFB74D;">Deps: <?php echo esc_html( implode( ', ', $style['deps'] ) ); ?></small>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- JS Assets -->
			<div>
				<strong style="color: #9C27B0;">⚡ JS Assets (<?php echo count( $scripts ); ?>):</strong><br>
				<?php foreach ( $scripts as $script ): ?>
					<div style="margin: 5px 0; padding: 5px; background: rgba(255,255,255,0.1); border-radius: 3px;">
						<span style="color: <?php echo mia_get_asset_type_color( $script['handle'] ); ?>;">
							<?php echo esc_html( $script['handle'] ); ?>
						</span>
						<?php if ( isset( $script['in_footer'] ) && $script['in_footer'] ): ?>
							<small style="color: #4CAF50;">[Footer]</small>
						<?php else: ?>
							<small style="color: #FF5722;">[Header]</small>
						<?php endif; ?>
						<br>
						<small style="color: #ccc;"><?php echo esc_html( $script['src'] ); ?></small>
						<?php if ( ! empty( $script['deps'] ) ): ?>
							<br><small style="color: #FFB74D;">Deps: <?php echo esc_html( implode( ', ', $script['deps'] ) ); ?></small>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<script>
	function miaToggleDebugPanel() {
		var content = document.getElementById('mia-debug-content');
		var toggle = document.getElementById('mia-debug-toggle');
		if (content.style.display === 'none') {
			content.style.display = 'block';
			toggle.innerHTML = '▼';
		} else {
			content.style.display = 'none';
			toggle.innerHTML = '▲';
		}
	}
	</script>
	<?php
}

/**
 * Get conditional component status for debugging
 */
function mia_get_conditional_component_status(): array {
	$status = array();

	// Check each conditional component
	if ( function_exists( 'should_show_consultation_cta' ) ) {
		$status['consultation-cta'] = should_show_consultation_cta();
	}

	if ( function_exists( 'mia_has_consultation_form' ) ) {
		$status['consultation-form'] = mia_has_consultation_form();
	}

	if ( function_exists( 'mia_is_careers_page' ) ) {
		$status['careers-cta'] = mia_is_careers_page();
	}

	if ( function_exists( 'mia_needs_case_card' ) ) {
		$status['case-card'] = mia_needs_case_card();
	}

	if ( function_exists( 'mia_needs_faq' ) ) {
		$status['faq'] = mia_needs_faq();
	}

	if ( function_exists( 'mia_needs_location_search_careers' ) ) {
		$status['location-search-careers'] = mia_needs_location_search_careers();
	}

	if ( function_exists( 'mia_needs_breadcrumb' ) ) {
		$status['breadcrumb'] = mia_needs_breadcrumb();
	}

	return $status;
}

/**
 * Get enqueued assets information
 */
function mia_get_enqueued_assets( $wp_assets ): array {
	$assets = array();

	if ( ! $wp_assets || ! isset( $wp_assets->queue ) ) {
		return $assets;
	}

	foreach ( $wp_assets->queue as $handle ) {
		if ( isset( $wp_assets->registered[ $handle ] ) ) {
			$asset = $wp_assets->registered[ $handle ];
			$assets[] = array(
				'handle' => $handle,
				'src' => $asset->src,
				'deps' => $asset->deps,
				'ver' => $asset->ver,
				'in_footer' => isset( $asset->extra['group'] ) ? (bool) $asset->extra['group'] : false,
			);
		}
	}

	// Sort by handle for easier reading
	usort( $assets, function( $a, $b ) {
		return strcmp( $a['handle'], $b['handle'] );
	});

	return $assets;
}

/**
 * Get color coding for asset types
 */
function mia_get_asset_type_color( string $handle ): string {
	// Global assets
	if ( strpos( $handle, 'mia-bootstrap' ) === 0 ||
		 strpos( $handle, 'mia-base' ) === 0 ||
		 strpos( $handle, 'mia-fonts' ) === 0 ||
		 strpos( $handle, 'mia-header' ) === 0 ||
		 strpos( $handle, 'mia-footer' ) === 0 ||
		 strpos( $handle, 'mia-button' ) === 0 ||
		 strpos( $handle, 'mia-card' ) === 0 ||
		 strpos( $handle, 'mia-social-media' ) === 0 ||
		 strpos( $handle, 'mia-fontawesome' ) === 0 ) {
		return '#4CAF50'; // Green for global
	}

	// Template-specific assets
	if ( strpos( $handle, 'mia-archive-' ) === 0 ||
		 strpos( $handle, 'mia-single-' ) === 0 ||
		 strpos( $handle, 'mia-page-' ) === 0 ||
		 strpos( $handle, 'mia-front-page' ) === 0 ||
		 strpos( $handle, 'mia-hero-section' ) === 0 ) {
		return '#2196F3'; // Blue for template-specific
	}

	// Component assets
	if ( strpos( $handle, 'mia-' ) === 0 ) {
		return '#FF9800'; // Orange for components
	}

	// Third-party/WordPress core
	return '#9E9E9E'; // Gray for other
}

/**
 * Hook the debug panel into wp_footer
 */
add_action( 'wp_footer', 'mia_add_debug_panel', 999 );