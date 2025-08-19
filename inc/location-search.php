<?php
/**
 * Location Search Component (Simplified)
 *
 * Reusable search widget for finding Mia Aesthetics locations
 * Include using: include get_template_directory() . '/inc/location-search.php';
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="location-search-widget">
	<div class="search-input-wrapper">
	<input
		type="text"
		id="location-search"
		role="combobox"
		aria-autocomplete="list"
		aria-controls="search-dropdown"
		aria-expanded="false"
		aria-label="Search for Mia Aesthetics locations by city, state, or zip code"
		placeholder="Search by city, state, or zip code..."
		autocomplete="off"
	/>
	<div class="search-icon"><i class="fas fa-search"></i></div>
	<div id="loading-spinner" class="loading-spinner" style="display: none">
		<div class="spinner"></div>
	</div>
	</div>

	<div id="search-dropdown" class="search-dropdown" role="listbox" aria-label="Search results">
	<!-- Results will appear here -->
	</div>
</div>

<?php
// Enqueue location search assets (they are already registered in inc/enqueue.php).
wp_enqueue_style( 'mia-location-search' );
wp_enqueue_script( 'mia-location-search' );

// Load Google Maps API - make sure it loads after our JS.
if ( ! wp_script_is( 'google-maps', 'registered' ) ) {
	$api_key = get_field( 'google_maps_api_key', 'option' );
	if ( $api_key ) {
		wp_register_script(
			'google-maps',
			'https://maps.googleapis.com/maps/api/js?key=' . esc_attr( $api_key ) . '&libraries=places&callback=initGoogleMaps',
			array( 'mia-location-search' ), // Depends on our location search script.
			'3.60.0',
			true
		);
	}
}
if ( ! wp_script_is( 'google-maps', 'enqueued' ) ) {
	wp_enqueue_script( 'google-maps' );
}
?>