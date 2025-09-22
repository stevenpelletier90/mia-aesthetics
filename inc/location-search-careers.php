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
	<div class="search-icon"><i class="fas fa-search" aria-hidden="true"></i></div>
	<div id="loading-spinner-careers" class="loading-spinner d-none">
		<div class="spinner"></div>
	</div>
	</div>

	<div id="search-dropdown-careers" class="search-dropdown" role="listbox" aria-label="Search results">
	<!-- Results will appear here -->
	</div>
</div>

<?php
// Location search careers assets are now handled by the centralized enqueue system in inc/enqueue.php
// This component assumes the following are already enqueued:
// - mia-location-search-careers (CSS)
// - mia-location-search-careers (JS)
// - google-maps (JS with callback=initGoogleMapsCareers).
?>
