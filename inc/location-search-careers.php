<?php
/**
 * Location Search Component - Careers Version
 *
 * Shows career location pages with their full geographical names
 * Instead of the SEO-optimized main location names
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="location-search-widget location-search-careers">
	<div class="search-input-wrapper">
	<input
		type="text"
		id="location-search-careers"
		role="combobox"
		aria-autocomplete="list"
		aria-controls="search-dropdown-careers"
		aria-expanded="false"
		aria-label="Search for Mia Aesthetics career locations by city, state, or zip code"
		placeholder="Search by city, state, or zip code..."
		autocomplete="off"
	/>
	<div class="search-icon"><i class="fas fa-search"></i></div>
	<div id="loading-spinner-careers" class="loading-spinner" style="display: none">
		<div class="spinner"></div>
	</div>
	</div>

	<div id="search-dropdown-careers" class="search-dropdown" role="listbox" aria-label="Search results">
	<!-- Results will appear here -->
	</div>
</div>

<?php
// Enqueue careers location search assets (they are already registered in inc/enqueue.php)
wp_enqueue_style( 'mia-location-search-careers' );
wp_enqueue_script( 'mia-location-search-careers' );

// Only load Google Maps if not already loaded.
if ( ! wp_script_is( 'google-maps', 'registered' ) ) {
	wp_register_script(
		'google-maps',
		'https://maps.googleapis.com/maps/api/js?key=AIzaSyAiXSTjbyqjv_b9yGrxVyXYRmZQZ4GXBJ4&libraries=places&callback=initGoogleMapsCareers',
		array( 'mia-location-search-careers' ), // Depends on our careers search script.
		null,
		true
	);
}
if ( ! wp_script_is( 'google-maps', 'enqueued' ) ) {
	wp_enqueue_script( 'google-maps' );
}
?>