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
	<div class="search-icon"><i class="fas fa-search" aria-hidden="true"></i></div>
	<div id="loading-spinner" class="loading-spinner d-none">
		<div class="spinner"></div>
	</div>
	</div>

	<div id="search-dropdown" class="search-dropdown" role="listbox" aria-label="Search results">
	<!-- Results will appear here -->
	</div>
</div>

<?php
// Location search assets are now handled by the centralized enqueue system in inc/enqueue.php
// This component assumes the following are already enqueued:
// - mia-location-search (CSS)
// - mia-location-search (JS)
// - google-maps (JS).
?>
