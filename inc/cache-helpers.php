<?php
/**
 * Lightweight cache/statistics helpers.
 *
 * Purge logic previously handled here has been removed because WP Rocket and
 * Imagify already manage their own caches. This file now only exposes two
 * small helper functions used by templates: a cached post-counter and a site
 * statistics aggregator. No actions or filters are registered, so there is
 * zero performance overhead.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the number of published posts for a given post-type.
 * Result is cached for two hours (object cache if available).
 *
 * @param string $post_type Custom post-type slug.
 * @param array  $args      Optional WP_Query args used to narrow the count.
 * @return int
 */
function mia_get_cached_post_count( $post_type, $args = [] ) {
	$cache_key = "mia_count_{$post_type}_" . md5( serialize( $args ) );
	$count     = wp_cache_get( $cache_key );

	if ( false === $count ) {
		$query = new WP_Query(
			wp_parse_args(
				$args,
				[
					'post_type'              => $post_type,
					'post_status'            => 'publish',
					'posts_per_page'         => -1,
					'fields'                 => 'ids',
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				]
			)
		);

		$count = (int) $query->post_count;
		wp_reset_postdata();

		// Cache for 2 hours (or for the duration of the request if no persistent cache).
		$cache_duration = apply_filters( 'mia_post_count_cache_duration', 2 * HOUR_IN_SECONDS );
		wp_cache_set( $cache_key, $count, '', $cache_duration );
	}

	return $count;
}

/**
 * Aggregate site-wide statistics displayed on the front page hero section.
 *
 * @return array{
 *   surgeons:int,
 *   locations:int,
 *   procedures:int,
 *   cases:int
 * }
 */
function mia_get_site_stats() {
	$cache_key = 'mia_site_stats';
	$stats     = wp_cache_get( $cache_key );

	if ( false === $stats ) {
		$stats = [
			'surgeons'   => mia_get_cached_post_count( 'surgeon',   [ 'post_parent' => 0 ] ),
			'locations'  => mia_get_cached_post_count( 'location',  [ 'post_parent' => 0 ] ),
			'procedures' => mia_get_cached_post_count( 'procedure' ),
			'cases'      => mia_get_cached_post_count( 'case' ),
		];

		$cache_duration = apply_filters( 'mia_site_stats_cache_duration', 2 * HOUR_IN_SECONDS );
		wp_cache_set( $cache_key, $stats, '', $cache_duration );
	}

	return $stats;
}
