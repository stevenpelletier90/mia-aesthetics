<?php
/**
 * Query Modifications and Filters for Mia Aesthetics Theme
 *
 * Handles all query modifications, custom filters, and archive behaviors.
 * Centralizes query logic for better maintainability and performance.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get archive query configuration for post types
 *
 * @return array<string, array<string, mixed>>
 */
function mia_get_archive_configurations(): array {
	return array(
		'location'     => array(
			'posts_per_page' => -1,
			'post_parent'    => 0,
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
		'surgeon'      => array(
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		),
		'procedure'    => array(
			'posts_per_page' => -1,
			'post_parent'    => 0,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		),
		'case'         => array(
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		),
		'condition'    => array(
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		),
		'special'      => array(
			'posts_per_page' => 6,
			'orderby'        => 'menu_order date',
			'order'          => 'DESC',
		),
		'non-surgical' => array(
			'posts_per_page' => -1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		),
	);
}

/**
 * Modify main queries for custom post type archives
 *
 * @param WP_Query $query The WP_Query object.
 * @return void
 */
function mia_modify_archive_queries( $query ): void {
	// Only modify main queries on the frontend.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	$configurations = mia_get_archive_configurations();

	foreach ( $configurations as $post_type => $config ) {
		if ( is_post_type_archive( $post_type ) ) {
			mia_apply_archive_config( $query, $config );

			// Special handling for specials archive.
			if ( 'special' === $post_type ) {
				mia_apply_special_archive_meta_query( $query );
			}
			break;
		}
	}
}

/**
 * Apply archive configuration to query
 *
 * @param WP_Query             $query  The WP_Query object.
 * @param array<string, mixed> $config Archive configuration.
 * @return void
 */
function mia_apply_archive_config( $query, $config ): void {
	foreach ( $config as $key => $value ) {
		$query->set( $key, $value );
	}
}

/**
 * Apply special archive meta query for active specials
 *
 * @param WP_Query $query The WP_Query object.
 * @return void
 */
function mia_apply_special_archive_meta_query( $query ): void {
	$query->set(
		'meta_query',
		array(
			'relation' => 'OR',
			array(
				'key'     => 'special_end_date',
				'value'   => gmdate( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
			array(
				'key'     => 'special_end_date',
				'compare' => 'NOT EXISTS',
			),
		)
	);
}

add_action( 'pre_get_posts', 'mia_modify_archive_queries' );

/**
 * Modify taxonomy archive queries
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_modify_taxonomy_queries( $query ): void {
	// Only modify main queries on the frontend.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	// Case category taxonomy.
	if ( is_tax( 'case-category' ) ) {
		$query->set( 'posts_per_page', -1 );      // Show all cases.
		$query->set( 'orderby', 'date' );         // Most recent first.
		$query->set( 'order', 'DESC' );
	}
}

add_action( 'pre_get_posts', 'mia_modify_taxonomy_queries' );

/**
 * Ensure correct body classes for custom post type archives
 *
 * @param array<int, string> $classes Array of body classes.
 * @return array<int, string> Modified body classes.
 */
function mia_archive_body_classes( $classes ) {
	// Get all registered post types.
	$post_types = get_post_types( array( 'public' => true ), 'names' );

	foreach ( $post_types as $post_type ) {
		if ( is_post_type_archive( $post_type ) ) {
			$class_name = 'post-type-archive-' . $post_type;
			if ( ! in_array( $class_name, $classes, true ) ) {
				$classes[] = $class_name;
			}
		}
	}

	// Add specific classes for single post types.
	if ( is_singular() ) {
		$post_type = get_post_type();
		if ( '' !== $post_type ) {
			$class_name = 'single-type-' . $post_type;
			if ( ! in_array( $class_name, $classes, true ) ) {
				$classes[] = $class_name;
			}
		}
	}

	return $classes;
}

add_filter( 'body_class', 'mia_archive_body_classes', 999 );

/**
 * Customize excerpt length based on context
 *
 * @param int $length The default excerpt length.
 * @return int
 */
function mia_custom_excerpt_length( $length ) {
	// Shorter excerpts for archive pages.
	if ( is_archive() || is_home() ) {
		return 20;
	}

	// Even shorter for search results.
	if ( is_search() ) {
		return 15;
	}

	// Default length for other contexts.
	return $length;
}

add_filter( 'excerpt_length', 'mia_custom_excerpt_length' );

/**
 * Customize excerpt more text
 *
 * @param string $more The default "more" text.
 * @return string
 */
function mia_excerpt_more( $more ) {
	// Don't add "more" text in admin.
	if ( is_admin() ) {
		return $more;
	}

	return esc_html__( '…', 'mia-aesthetics' );
}

add_filter( 'excerpt_more', 'mia_excerpt_more' );

/**
 * Use native title format filters to remove Protected/Private prefixes
 *
 * @param string $format The current title format.
 * @return string Title format without prefixes.
 */
function mia_protected_title_format( $format ) {
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	unset( $format );
	// Always return the raw title without the default "Protected: " prefix.
	return '%s';
}

add_filter( 'protected_title_format', 'mia_protected_title_format' );

/**
 * Remove Private prefix via native filter.
 *
 * @param string $format The current title format.
 * @return string Title format without prefixes.
 */
function mia_private_title_format( $format ) {
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	unset( $format );
	return '%s';
}

add_filter( 'private_title_format', 'mia_private_title_format' );

/**
 * Modify search query to include custom post types
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_search_filter( $query ): void {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		// Include relevant post types in search.
		$searchable_types = array(
			'post',
			'page',
			'procedure',
			'condition',
			'surgeon',
			'location',
			'non-surgical',
		);

		$query->set( 'post_type', $searchable_types );

		// Limit search results.
		$query->set( 'posts_per_page', 20 );
	}
}

add_action( 'pre_get_posts', 'mia_search_filter' );

/**
 * Exclude certain pages from search results (with caching)
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_exclude_pages_from_search( $query ): void {
	if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
		// Get cached excluded page IDs.
		$cache_key   = 'mia_excluded_search_pages';
		$exclude_ids = get_transient( $cache_key );

		if ( false === $exclude_ids ) {
			// Get IDs of pages to exclude with a single query.
			$exclude_slugs = array(
				'thank-you',
				'privacy-policy',
				'terms-of-service',
				'sitemap',
			);

			$pages = get_posts(
				array(
					'post_type'              => 'page',
					'post_name__in'          => $exclude_slugs,
					'post_status'            => 'publish',
					'posts_per_page'         => -1,
					'fields'                 => 'ids',
					'no_found_rows'          => true,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
				)
			);

			$exclude_ids = array() !== $pages ? $pages : array();

			// Cache for 1 day (pages rarely change).
			set_transient( $cache_key, $exclude_ids, DAY_IN_SECONDS );
		}

		if ( array() !== $exclude_ids ) {
			$query->set( 'post__not_in', $exclude_ids );
		}
	}
}

add_action( 'pre_get_posts', 'mia_exclude_pages_from_search' );

/**
 * Add custom query vars
 *
 * @param array<int, string> $vars Array of query variables.
 * @return array<int, string>
 */
function mia_add_query_vars( $vars ) {
	// Add custom query variables if needed.
	$vars[] = 'surgeon_location';
	$vars[] = 'procedure_type';

	return $vars;
}

add_filter( 'query_vars', 'mia_add_query_vars' );

/**
 * Modify queries based on custom query vars
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_handle_custom_queries( $query ): void {
	if ( ! is_admin() && $query->is_main_query() ) {
		// Filter surgeons by location.
		$raw_location = $query->get( 'surgeon_location' );
		if ( '' !== $raw_location ) {
			$location_id = intval( $raw_location );
			if ( 0 !== $location_id ) {
				$meta_query   = (array) $query->get( 'meta_query' );
				$meta_query[] = array(
					'key'     => 'surgeon_location',
					'value'   => $location_id,
					'compare' => '=',
				);
				$query->set( 'meta_query', $meta_query );
			}
		}

		// Filter procedures by type.
		$raw_procedure_type = $query->get( 'procedure_type' );
		if ( '' !== $raw_procedure_type ) {
			$procedure_type = sanitize_text_field( $raw_procedure_type );
			if ( '' !== $procedure_type ) {
				$meta_query   = (array) $query->get( 'meta_query' );
				$meta_query[] = array(
					'key'     => 'procedure_type',
					'value'   => $procedure_type,
					'compare' => '=',
				);
				$query->set( 'meta_query', $meta_query );
			}
		}
	}
}

add_action( 'pre_get_posts', 'mia_handle_custom_queries' );

/**
 * Optimize queries by removing unnecessary joins and cache operations
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_optimize_queries( $query ): void {
	if ( ! is_admin() && $query->is_main_query() ) {
		// Remove unnecessary meta cache on archives that don't use meta fields.
		if ( is_post_type_archive() ) {
			$post_type = $query->get( 'post_type' );

			// Post types that don't need meta cache in archives.
			$no_meta_types = array( 'surgeon', 'procedure', 'condition' );
			if ( in_array( $post_type, $no_meta_types, true ) && null === $query->get( 'meta_query' ) ) {
				$query->set( 'update_post_meta_cache', false );
			}
		}

		// Remove term cache updates if not needed.
		if ( ! is_tax() && ! is_category() && ! is_tag() ) {
			$query->set( 'update_post_term_cache', false );
		}

		// Optimize found_rows calculation when not needed.
		if ( is_post_type_archive() && ! is_paged() ) {
			$post_type = $query->get( 'post_type' );
			// Archives that show all items don't need found_rows.
			$no_pagination_types = array( 'location', 'surgeon', 'procedure', 'condition', 'non-surgical' );
			if ( in_array( $post_type, $no_pagination_types, true ) ) {
				$query->set( 'no_found_rows', true );
			}
		}
	}
}

add_action( 'pre_get_posts', 'mia_optimize_queries', 999 );

/**
 * Add pagination support for custom queries
 *
 * @return void
 */
function mia_pagination_rewrite_rules(): void {
	// Add rewrite rules for custom post type pagination.
	$post_types = array( 'special' );

	foreach ( $post_types as $post_type ) {
		add_rewrite_rule(
			$post_type . '/page/([0-9]+)/?$',
			'index.php?post_type=' . $post_type . '&paged=$matches[1]',
			'top'
		);
	}
}

add_action( 'init', 'mia_pagination_rewrite_rules' );


/**
 * Flush rewrites on theme switch to register custom rules.
 */
function mia_flush_rewrites_on_switch(): void { // phpcs:ignore Squiz.Commenting.FunctionComment
	flush_rewrite_rules();
}

add_action( 'after_switch_theme', 'mia_flush_rewrites_on_switch' );

/**
 * Fix pagination on custom post type archives
 *
 * @param WP_Query $query The WP_Query instance.
 * @return void
 */
function mia_fix_pagination( $query ): void {
	if ( ! is_admin() && $query->is_main_query() && ( $query->is_post_type_archive( 'special' ) || in_array( 'special', (array) $query->get( 'post_type' ), true ) ) ) {
		if ( 0 !== (int) get_query_var( 'paged' ) ) {
			$query->set( 'paged', get_query_var( 'paged' ) );
		} elseif ( 0 !== (int) get_query_var( 'page' ) ) {
			$query->set( 'paged', get_query_var( 'page' ) );
		}
	}
}

add_action( 'pre_get_posts', 'mia_fix_pagination' );

/**
 * Get all non-surgical procedures grouped by category
 * Uses persistent transient caching for better performance
 *
 * @return array<string, mixed>
 */
function mia_get_non_surgical_by_category() {
	$cache_key      = 'mia_non_surgical_grouped';
	$cached_results = get_transient( $cache_key );

	if ( false !== $cached_results ) {
		return $cached_results;
	}

	$all_procedures = new WP_Query(
		array(
			'post_type'              => 'non-surgical',
			'posts_per_page'         => -1,
			'post_status'            => 'publish',
			'orderby'                => 'menu_order title',
			'order'                  => 'ASC',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true, // Needed for ACF procedure_category field.
			'update_post_term_cache' => false,
		)
	);

	$grouped = array(
		'injectable' => array(),
		'skin'       => array(),
		'body'       => array(),
		'wellness'   => array(),
	);

	$counts = array(
		'injectable' => 0,
		'skin'       => 0,
		'body'       => 0,
		'wellness'   => 0,
	);

	if ( array() !== $all_procedures->posts ) {
		foreach ( $all_procedures->posts as $post_id ) {
			$categories = get_field( 'procedure_category', $post_id );

			// Build post data array for caching.
			$post_data = array(
				'ID'         => $post_id,
				'post_title' => get_the_title( $post_id ),
				'permalink'  => get_permalink( $post_id ),
				'excerpt'    => get_the_excerpt( $post_id ),
			);

			if ( is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					if ( isset( $grouped[ $category ] ) ) {
						$grouped[ $category ][] = $post_data;
						++$counts[ $category ];
					}
				}
			} elseif ( is_string( $categories ) && isset( $grouped[ $categories ] ) ) {
				$grouped[ $categories ][] = $post_data;
				++$counts[ $categories ];
			}
		}
	}

	$cached_results = array(
		'grouped' => $grouped,
		'counts'  => $counts,
	);

	// Cache for 6 hours (procedures don't change frequently).
	set_transient( $cache_key, $cached_results, 6 * HOUR_IN_SECONDS );

	return $cached_results;
}

/**
 * Get location IDs that have a city guide assigned
 *
 * Uses transient caching to avoid slow meta_query on every page load.
 * Returns array of location post IDs that have a city_guide field set.
 *
 * @return array<int> Array of location post IDs with city guides.
 */
function mia_get_city_guide_location_ids(): array {
	$transient_key = 'mia_city_guide_location_ids';
	$cached_ids    = get_transient( $transient_key );

	if ( is_array( $cached_ids ) ) {
		return $cached_ids;
	}

	// Query all locations (fast query without meta_query).
	$all_locations = new WP_Query(
		array(
			'post_type'              => 'location',
			'posts_per_page'         => -1,
			'post_status'            => 'publish',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);

	$city_guide_ids = array();

	if ( function_exists( 'get_field' ) && is_array( $all_locations->posts ) ) {
		foreach ( $all_locations->posts as $location_id ) {
			$post_id    = is_int( $location_id ) ? $location_id : $location_id->ID;
			$city_guide = get_field( 'city_guide', $post_id );
			if ( null !== $city_guide && false !== $city_guide && '' !== $city_guide ) {
				$city_guide_ids[] = $post_id;
			}
		}
	}

	// Cache for 12 hours.
	set_transient( $transient_key, $city_guide_ids, 12 * HOUR_IN_SECONDS );

	return $city_guide_ids;
}

/**
 * Get surgeons assigned to a specific location.
 *
 * @param int $location_id The location post ID.
 * @return WP_Query Query object with surgeons at this location.
 */
function mia_get_surgeons_by_location( int $location_id ): WP_Query {
	return new WP_Query(
		array(
			'post_type'              => 'surgeon',
			'posts_per_page'         => -1,
			'orderby'                => 'menu_order',
			'order'                  => 'ASC',
			// Meta query filters surgeons by their assigned location.
			// Performance acceptable: small dataset, WP Engine object cache, WP Rocket page cache.
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'meta_query'             => array(
				array(
					'key'     => 'surgeon_location',
					'value'   => $location_id,
					'compare' => '=',
				),
			),
			'update_post_term_cache' => false,
			'no_found_rows'          => true,
		)
	);
}

/**
 * Clear query-related caches when relevant posts are updated
 *
 * @param int $post_id The post ID being updated.
 * @return void
 */
function mia_clear_query_caches( $post_id ): void {
	$post_type = get_post_type( $post_id );

	// Clear non-surgical grouping cache.
	if ( 'non-surgical' === $post_type ) {
		delete_transient( 'mia_non_surgical_grouped' );
	}

	// Clear search exclusion cache if a page is updated.
	if ( 'page' === $post_type ) {
		delete_transient( 'mia_excluded_search_pages' );
	}

	// Clear city guide location cache when locations are updated.
	if ( 'location' === $post_type ) {
		delete_transient( 'mia_city_guide_location_ids' );
	}
}

add_action( 'save_post', 'mia_clear_query_caches' );
add_action( 'delete_post', 'mia_clear_query_caches' );
add_action( 'trash_post', 'mia_clear_query_caches' );
add_action( 'untrash_post', 'mia_clear_query_caches' );
