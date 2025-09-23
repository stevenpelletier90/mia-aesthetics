<?php
/**
 * Menu Helper Functions for Mia Aesthetics theme.
 *
 * Provides rendering logic and caching for navigation menus (procedures, locations, surgeons, before/after).
 * All menu display logic is centralized here for maintainability.
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize a URL/path for comparison (lowercase, strip domain/query, trim slashes)
 *
 * @param string $url URL to normalize.
 * @return string
 */
function mia_aesthetics_normalize_path( $url ): string {
	if ( '' === $url ) {
		return '';
	}

	$parsed = wp_parse_url( $url );
	$path   = isset( $parsed['path'] ) ? (string) $parsed['path'] : (string) $url;
	$path   = strtok( $path, '?#' );
	$path   = false !== $path ? $path : '';
	$path   = wp_normalize_path( strtolower( rtrim( $path, '/' ) ) );
	return '' === $path ? '/' : $path;
}

/**
 * Check if a given URL matches the current request URL (path-only).
 *
 * @param string $url URL to compare to current request.
 * @return bool
 */
function mia_aesthetics_is_current_url( string $url ): bool {
	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$current = mia_aesthetics_normalize_path( $req_uri );
	$target  = mia_aesthetics_normalize_path( $url );
	return $current === $target;
}

/**
 * Determine if current request falls within a top-level section.
 *
 * @param string $menu_slug Section key: procedures|locations|surgeons|before-after|specials|financing|non-surgical.
 * @return bool
 */
function mia_aesthetics_is_current_section( string $menu_slug ): bool {
	$menu_slug = strtolower( $menu_slug );

	switch ( $menu_slug ) {
		case 'procedures':
			if ( is_post_type_archive( 'procedure' ) || is_singular( 'procedure' ) ) {
				return true;
			}
			break;
		case 'non-surgical':
			if ( is_post_type_archive( 'non-surgical' ) || is_singular( 'non-surgical' ) ) {
				return true;
			}
			break;
		case 'locations':
			if ( is_post_type_archive( 'location' ) || is_singular( 'location' ) ) {
				return true;
			}
			break;
		case 'surgeons':
			if ( is_post_type_archive( 'surgeon' ) || is_singular( 'surgeon' ) ) {
				return true;
			}
			break;
		case 'specials':
			if ( is_post_type_archive( 'special' ) || is_singular( 'special' ) ) {
				return true;
			}
			break;
		// before-after and financing are pages only; URL fallback below.
		default:
			break;
	}

	$req_uri = isset( $_SERVER['REQUEST_URI'] ) ? (string) wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$current = mia_aesthetics_normalize_path( $req_uri );

	$patterns = array(
		'procedures'   => array( '/cosmetic-plastic-surgery', '/non-surgical' ),
		'non-surgical' => array( '/non-surgical' ),
		'locations'    => array( '/locations' ),
		'surgeons'     => array( '/plastic-surgeons', '/surgeon' ),
		'before-after' => array( '/before-after' ),
		'specials'     => array( '/specials' ),
		'financing'    => array( '/financing' ),
	);

	if ( isset( $patterns[ $menu_slug ] ) ) {
		foreach ( $patterns[ $menu_slug ] as $prefix ) {
			if ( 0 === strpos( $current, mia_aesthetics_normalize_path( $prefix ) ) ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Get all menu data with a single optimized query
 * Returns cached results for locations, surgeons, and non-surgical procedures
 *
 * @return array<string, array<int, mixed>> Associative array with 'locations', 'surgeons', 'non_surgical' keys
 */
function mia_get_all_menu_data(): array {
	$cache_key = 'mia_all_menu_data';
	$all_data  = get_transient( $cache_key );

	if ( false === $all_data ) {
		// Single query for all menu post types.
		$args = array(
			'post_type'              => array( 'location', 'surgeon', 'non-surgical' ),
			'posts_per_page'         => -1,
			'orderby'                => 'post_type title',
			'order'                  => 'ASC',
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => true, // Only for locations that need ACF state field.
			'update_post_term_cache' => false,
			// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Cached result, acceptable for menu functionality.
			'meta_query'             => array(
				'relation' => 'OR',
				array(
					'key'     => 'post_type',
					'compare' => 'NOT EXISTS', // Include posts without post_type meta.
				),
				array(
					'key'     => 'post_parent',
					'value'   => 0,
					'compare' => '=',
					'type'    => 'NUMERIC',
				),
			),
		);

		$post_ids = get_posts( $args );
		$all_data = array(
			'locations'    => array(),
			'surgeons'     => array(),
			'non_surgical' => array(),
		);

		if ( array() !== $post_ids ) {
			foreach ( $post_ids as $post_id ) {
				$post_type = get_post_type( $post_id );

				switch ( $post_type ) {
					case 'location':
						// Only include parent locations (post_parent = 0).
						if ( 0 === wp_get_post_parent_id( $post_id ) ) {
							$all_data['locations'][] = array(
								'id'    => $post_id,
								'title' => get_the_title( $post_id ),
								'url'   => get_permalink( $post_id ),
								'state' => get_field( 'state', $post_id ),
							);
						}
						break;

					case 'surgeon':
						$surgeon_name = get_the_title( $post_id );
						$name_parts   = explode( ' ', $surgeon_name );
						$last_name    = isset( $name_parts[1] ) ? $name_parts[1] : $surgeon_name;

						$all_data['surgeons'][] = array(
							'id'        => $post_id,
							'name'      => $surgeon_name,
							'url'       => get_permalink( $post_id ),
							'last_name' => $last_name,
						);
						break;

					case 'non-surgical':
						$all_data['non_surgical'][] = array(
							'id'    => $post_id,
							'title' => get_the_title( $post_id ),
							'url'   => get_permalink( $post_id ),
						);
						break;
				}
			}

			// Sort surgeons by last name.
			if ( array() !== $all_data['surgeons'] ) {
				usort(
					$all_data['surgeons'],
					static function ( $a, $b ) {
						return strcasecmp( $a['last_name'], $b['last_name'] );
					}
				);
			}
		}

		set_transient( $cache_key, $all_data, DAY_IN_SECONDS );
	}

	return $all_data;
}

/**
 * Get locations with caching (optimized to use consolidated data)
 *
 * @return array<int, mixed>
 */
function mia_aesthetics_get_locations_direct(): array {
	$all_data = mia_get_all_menu_data();
	return $all_data['locations'];
}

/**
 * Get non-surgical procedures with caching (optimized to use consolidated data)
 *
 * @return array<int, mixed>
 */
function mia_aesthetics_get_non_surgical_direct(): array {
	$all_data = mia_get_all_menu_data();
	return $all_data['non_surgical'];
}

/**
 * Get surgeons with caching (optimized to use consolidated data)
 *
 * @return array<int, mixed>
 */
function mia_aesthetics_get_surgeons_direct(): array {
	$all_data = mia_get_all_menu_data();
	return $all_data['surgeons'];
}

/**
 * Clear menu data cache when relevant posts are updated
 *
 * @param int $post_id The ID of the post being updated.
 * @return void
 */
function mia_clear_menu_cache( $post_id ): void {
	$post_type = get_post_type( $post_id );
	if ( in_array( $post_type, array( 'location', 'surgeon', 'non-surgical' ), true ) ) {
		delete_transient( 'mia_all_menu_data' );
		delete_transient( 'mia_locations_menu' );
		delete_transient( 'mia_surgeons_menu' );
		delete_transient( 'mia_non_surgical_menu' );
	}
}
add_action( 'save_post', 'mia_clear_menu_cache' );
add_action( 'delete_post', 'mia_clear_menu_cache' );
add_action( 'trash_post', 'mia_clear_menu_cache' );
add_action( 'untrash_post', 'mia_clear_menu_cache' );

/**
 * Get footer locations with surgeons data (optimized for footer display)
 * Solves N+1 query problem by fetching all surgeons in a single query
 *
 * @return array<int, array<string, mixed>> Array of locations with associated surgeons
 */
function mia_get_footer_locations(): array {
	$cache_key      = 'mia_footer_locations_with_surgeons';
	$locations_data = get_transient( $cache_key );

	if ( false === $locations_data ) {
		// Step 1: Get all locations.
		$locations_query = new WP_Query(
			array(
				'post_type'              => 'location',
				'posts_per_page'         => -1,
				'orderby'                => 'title',
				'order'                  => 'ASC',
				'post_parent'            => 0,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		if ( array() === $locations_query->posts ) {
			return array();
		}

		$location_ids = $locations_query->posts;

		// Step 2: Get ALL surgeons for ALL locations in one query (prevents N+1).
		$all_surgeons_query = new WP_Query(
			array(
				'post_type'              => 'surgeon',
				'posts_per_page'         => -1,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Cached result, prevents N+1 queries.
				'meta_query'             => array(
					array(
						'key'     => 'surgeon_location',
						'value'   => $location_ids,
						'compare' => 'IN',
					),
				),
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => true, // Need meta for surgeon_location field.
				'update_post_term_cache' => false,
			)
		);

		// Step 3: Group surgeons by location.
		$surgeons_by_location = array();
		if ( array() !== $all_surgeons_query->posts ) {
			foreach ( $all_surgeons_query->posts as $surgeon_id ) {
				$surgeon_location = get_field( 'surgeon_location', $surgeon_id );
				if ( null !== $surgeon_location ) {
					// Handle both object and ID formats - ensure we always get an integer.
					if ( is_object( $surgeon_location ) && property_exists( $surgeon_location, 'ID' ) ) {
						$location_key = (int) $surgeon_location->ID;
					} else {
						$location_key = (int) $surgeon_location;
					}
					if ( in_array( $location_key, $location_ids, true ) ) {
						if ( ! isset( $surgeons_by_location[ $location_key ] ) ) {
							$surgeons_by_location[ $location_key ] = array();
						}
						$surgeons_by_location[ $location_key ][] = array(
							'id'    => $surgeon_id,
							'title' => get_the_title( $surgeon_id ),
							'url'   => get_permalink( $surgeon_id ),
						);
					}
				}
			}
		}

		// Step 4: Build final locations data structure.
		$locations_data = array();
		foreach ( $location_ids as $location_id ) {
			if ( $location_id instanceof WP_Post ) {
				$location_int_id = (int) $location_id->ID;
			} else {
				$location_int_id = (int) $location_id;
			}
			$locations_data[] = array(
				'id'       => $location_int_id,
				'title'    => get_the_title( $location_int_id ),
				'url'      => get_permalink( $location_int_id ),
				'surgeons' => $surgeons_by_location[ $location_int_id ] ?? array(),
			);
		}

		// Cache for 6 hours (locations and surgeons don't change frequently).
		set_transient( $cache_key, $locations_data, 6 * HOUR_IN_SECONDS );
	}

	return $locations_data;
}

/**
 * Clear footer locations cache when relevant posts are updated
 *
 * @param int $post_id The ID of the post being updated.
 * @return void
 */
function mia_clear_footer_locations_cache( $post_id ): void {
	$post_type = get_post_type( $post_id );
	if ( in_array( $post_type, array( 'location', 'surgeon' ), true ) ) {
		delete_transient( 'mia_footer_locations_with_surgeons' );
	}
}
add_action( 'save_post', 'mia_clear_footer_locations_cache' );
add_action( 'delete_post', 'mia_clear_footer_locations_cache' );
add_action( 'trash_post', 'mia_clear_footer_locations_cache' );
add_action( 'untrash_post', 'mia_clear_footer_locations_cache' );

/**
 * Render procedures dropdown
 *
 * @param array<string, mixed> $procedures Array of procedure data.
 * @param bool                 $is_mobile  Whether to render mobile version.
 * @return void
 */
function mia_aesthetics_render_procedures_menu( $procedures, $is_mobile = false ): void {
	$dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
	$is_section     = mia_aesthetics_is_current_section( 'procedures' );
	$is_exact       = mia_aesthetics_is_current_url( $procedures['url'] );
	?>
	<li class="nav-item dropdown <?php echo esc_attr( $dropdown_class ); ?> <?php echo $is_section ? 'current-menu-ancestor' : ''; ?>">
		<a class="nav-link dropdown-toggle" href="<?php echo esc_url( $procedures['url'] ); ?>" 
			role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>
			<?php echo esc_html( $procedures['title'] ); ?>
		</a>
		<?php if ( $is_mobile ) : ?>
			<?php mia_aesthetics_render_mobile_procedures_menu( $procedures ); ?>
		<?php else : ?>
			<?php mia_aesthetics_render_desktop_procedures_menu( $procedures ); ?>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render desktop procedures mega menu
 *
 * @param array<string, mixed> $procedures Array of procedure data.
 * @return void
 */
function mia_aesthetics_render_desktop_procedures_menu( $procedures ): void {
	?>
	<div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<a class="mega-menu-title" href="<?php echo esc_url( $procedures['url'] ); ?>">View All Procedures <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="row">
				<?php foreach ( $procedures['sections'] as $section ) : ?>
					<div class="col-md-3 mb-3">
						<div class="dropdown-header">
							<a href="<?php echo esc_url( $section['url'] ); ?>"><?php echo esc_html( $section['title'] ); ?></a>
						</div>
						<ul class="list-unstyled">
							<?php foreach ( $section['items'] as $item ) : ?>
								<?php
								$parent_path = isset( $item['parent'] ) ? $procedures['sections'][ $item['parent'] ]['url'] : $section['url'];
								$item_url    = rtrim( $parent_path, '/' ) . '/' . $item['slug'] . '/';
								?>
								<li><a class="dropdown-item py-1" href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render mobile procedures dropdown menu
 *
 * @param array<string, mixed> $procedures Array of procedure data.
 * @return void
 */
function mia_aesthetics_render_mobile_procedures_menu( $procedures ): void {
	?>
	<ul class="dropdown-menu">
		<li><a class="dropdown-item" href="<?php echo esc_url( $procedures['url'] ); ?>">View All Procedures</a></li>
		<?php foreach ( $procedures['sections'] as $section_key => $section ) : ?>
			<!-- Section Header -->
			<li><a class="dropdown-item" href="<?php echo esc_url( $section['url'] ); ?>"><?php echo esc_html( $section['title'] ); ?></a></li>
			<!-- Section Items -->
			<?php foreach ( $section['items'] as $item ) : ?>
				<?php
				$parent_path = isset( $item['parent'] ) ? $procedures['sections'][ $item['parent'] ]['url'] : $section['url'];
				$item_url    = rtrim( $parent_path, '/' ) . '/' . $item['slug'] . '/';
				?>
				<li><a class="dropdown-item" href="<?php echo esc_url( $item_url ); ?>"><?php echo esc_html( $item['title'] ); ?></a></li>
			<?php endforeach; ?>
			<?php if ( array_key_last( $procedures['sections'] ) !== $section_key ) : ?>
				<li><hr class="dropdown-divider"></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Render locations menu for both desktop and mobile
 *
 * @param bool $is_mobile Whether to render mobile version.
 * @return void
 */
function mia_aesthetics_render_locations_menu( bool $is_mobile = false ): void {
	$locations      = mia_aesthetics_get_locations_direct();
	$dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
	?>
	<li class="nav-item dropdown <?php echo esc_attr( $dropdown_class ); ?>">
		<a class="nav-link dropdown-toggle" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>" 
			role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
			Locations
		</a>
		<?php if ( $is_mobile ) : ?>
			<?php mia_aesthetics_render_mobile_locations_menu( $locations ); ?>
		<?php else : ?>
			<?php mia_aesthetics_render_desktop_locations_menu( $locations ); ?>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render desktop locations mega menu
 *
 * @param array<int, mixed> $locations Array of location data.
 * @return void
 */
function mia_aesthetics_render_desktop_locations_menu( $locations ): void {
	?>
	<div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<a class="mega-menu-title" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">View All Locations <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="row">
				<?php
				if ( array() !== $locations ) :
					$total_locations      = count( $locations );
					$locations_per_column = ceil( $total_locations / 4 );
					$location_count       = 0;
					$column_count         = 0;

					echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
					foreach ( $locations as $location ) :
						$display_city   = trim( str_ireplace( 'Mia Aesthetics', '', $location['title'] ) );
						$abbr           = mia_aesthetics_get_state_abbr( $location['state'] );
							$menu_label = isset( $location['state'] ) && '' !== $location['state'] ? $display_city . ', ' . $abbr : $display_city;

						echo '<li><a class="dropdown-item py-1" href="' . esc_url( $location['url'] ) . '">' . esc_html( $menu_label ) . '</a></li>';
						++$location_count;

						if ( 0 === ( $location_count % $locations_per_column ) && $location_count < $total_locations ) {
							++$column_count;
							echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
						}
					endforeach;
					echo '</ul></div>';

					while ( $column_count < 3 ) {
						++$column_count;
						echo '<div class="col-md-3 mb-3"></div>';
					}
				else :
					echo '<div class="col-12"><p>No locations found. <a href="' . esc_url( home_url( '/locations/' ) ) . '">View our locations page</a> for more information.</p></div>';
				endif;
				?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render mobile locations dropdown menu
 *
 * @param array<int, mixed> $locations Array of location data.
 * @return void
 */
function mia_aesthetics_render_mobile_locations_menu( $locations ): void {
	?>
	<ul class="dropdown-menu">
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">View All Locations</a></li>
		<?php
		if ( array() !== $locations ) :
			foreach ( $locations as $location ) :
				$display_city       = trim( str_ireplace( 'Mia Aesthetics', '', $location['title'] ) );
				$abbr               = mia_aesthetics_get_state_abbr( $location['state'] );
						$menu_label = isset( $location['state'] ) && '' !== $location['state'] ? $display_city . ', ' . $abbr : $display_city;
				?>
				<li><a class="dropdown-item" href="<?php echo esc_url( $location['url'] ); ?>"><?php echo esc_html( $menu_label ); ?></a></li>
				<?php
			endforeach;
		else :
			?>
			<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">View Our Locations</a></li>
			<?php
		endif;
		?>
	</ul>
	<?php
}

/**
 * Render surgeons menu for both desktop and mobile
 *
 * @param bool $is_mobile Whether to render mobile version.
 * @return void
 */
function mia_aesthetics_render_surgeons_menu( bool $is_mobile = false ): void {
	$surgeons       = mia_aesthetics_get_surgeons_direct();
	$dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
	$is_section     = mia_aesthetics_is_current_section( 'surgeons' );
	$is_exact       = mia_aesthetics_is_current_url( home_url( '/plastic-surgeons/' ) );
	?>
	<li class="nav-item dropdown <?php echo esc_attr( $dropdown_class ); ?> <?php echo $is_section ? 'current-menu-ancestor' : ''; ?>">
		<a class="nav-link dropdown-toggle" href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>" 
			role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>
			Surgeons
		</a>
		<?php if ( $is_mobile ) : ?>
			<?php mia_aesthetics_render_mobile_surgeons_menu( $surgeons ); ?>
		<?php else : ?>
			<?php mia_aesthetics_render_desktop_surgeons_menu( $surgeons ); ?>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render desktop surgeons mega menu
 *
 * @param array<int, mixed> $surgeons Array of surgeon data.
 * @return void
 */
function mia_aesthetics_render_desktop_surgeons_menu( $surgeons ): void {
	?>
	<div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<a class="mega-menu-title" href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>">View All Surgeons <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="row">
				<?php
				if ( array() !== $surgeons ) :
					$total_surgeons      = count( $surgeons );
					$surgeons_per_column = ceil( $total_surgeons / 4 );
					$surgeon_count       = 0;
					$column_count        = 0;

					echo '<div class="col-md-3 mb-3"><ul class="list-unstyled">';
					foreach ( $surgeons as $surgeon ) :
						echo '<li><a class="dropdown-item py-1" href="' . esc_url( $surgeon['url'] ) . '">' . esc_html( $surgeon['name'] ) . '</a></li>';
						++$surgeon_count;

						if ( 0 === ( $surgeon_count % $surgeons_per_column ) && $surgeon_count < $total_surgeons ) {
							++$column_count;
							echo '</ul></div><div class="col-md-3 mb-3"><ul class="list-unstyled">';
						}
					endforeach;
					echo '</ul></div>';

					while ( $column_count < 3 ) {
						++$column_count;
						echo '<div class="col-md-3 mb-3"></div>';
					}
				else :
					echo '<div class="col-12"><p>No surgeons found. <a href="' . esc_url( home_url( '/plastic-surgeons/' ) ) . '">View our surgeons page</a> for more information.</p></div>';
				endif;
				?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render mobile surgeons dropdown menu
 *
 * @param array<int, mixed> $surgeons Array of surgeon data.
 * @return void
 */
function mia_aesthetics_render_mobile_surgeons_menu( $surgeons ): void {
	?>
	<ul class="dropdown-menu">
		<?php $is_exact_view_all = mia_aesthetics_is_current_url( home_url( '/plastic-surgeons/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>" <?php echo $is_exact_view_all ? 'aria-current="page"' : ''; ?>>View All Surgeons</a></li>
		<?php
		if ( array() !== $surgeons ) :
			foreach ( $surgeons as $surgeon ) :
				?>
				<?php $is_exact_surgeon = mia_aesthetics_is_current_url( $surgeon['url'] ); ?>
				<li><a class="dropdown-item" href="<?php echo esc_url( $surgeon['url'] ); ?>" <?php echo $is_exact_surgeon ? 'aria-current="page"' : ''; ?>><?php echo esc_html( $surgeon['name'] ); ?></a></li>
				<?php
			endforeach;
		else :
			?>
			<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>">View Our Surgeons</a></li>
			<?php
		endif;
		?>
	</ul>
	<?php
}

/**
 * Render Before & After menu for both desktop and mobile
 *
 * @param bool $is_mobile Whether to render mobile version.
 * @return void
 */
function mia_aesthetics_render_before_after_menu( bool $is_mobile = false ): void {
	$dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
	?>
	<li class="nav-item dropdown <?php echo esc_attr( $dropdown_class ); ?>">
		<a class="nav-link dropdown-toggle" href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" 
			role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
			Before & After
		</a>
		<?php if ( $is_mobile ) : ?>
			<?php mia_aesthetics_render_mobile_before_after_menu(); ?>
		<?php else : ?>
			<?php mia_aesthetics_render_desktop_before_after_menu(); ?>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render desktop Before & After mega menu
 *
 * @return void
 */
function mia_aesthetics_render_desktop_before_after_menu(): void {
	?>
	<div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<?php $is_exact_view_all = mia_aesthetics_is_current_url( home_url( '/before-after/' ) ); ?>
					<a class="mega-menu-title" href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" <?php echo $is_exact_view_all ? 'aria-current="page"' : ''; ?>>View All Before & After <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 mb-3">
					<div class="dropdown-header">
						<span>By Procedure</span>
					</div>
					<ul class="list-unstyled">
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/bbl/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/bbl/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Brazilian Butt Lift (BBL)</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-augmentation/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/breast-augmentation/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Augmentation</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-lift/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/breast-lift/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Lift</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-reduction/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/breast-reduction/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Reduction</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/lipo-360/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/lipo-360/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Lipo 360</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/mommy-makeover/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/mommy-makeover/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Mommy Makeover</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/tummy-tuck/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/tummy-tuck/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Tummy Tuck</a></li>
					</ul>
				</div>
				<div class="col-md-6 mb-3">
					<div class="dropdown-header">
						<span>By Category</span>
					</div>
					<ul class="list-unstyled">
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/before-after-by-doctor/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/before-after-by-doctor/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Results by Surgeon</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/patient-journeys/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/before-after/patient-journeys/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Patient Testimonials</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render mobile Before & After dropdown menu
 *
 * @return void
 */
function mia_aesthetics_render_mobile_before_after_menu(): void {
	?>
	<ul class="dropdown-menu">
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>View All Before & After</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/before-after-by-doctor/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/before-after-by-doctor/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Results by Surgeon</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/bbl/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/bbl/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Brazilian Butt Lift (BBL)</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-augmentation/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/breast-augmentation/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Augmentation</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-lift/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/breast-lift/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Lift</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/breast-reduction/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/breast-reduction/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Breast Reduction</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/lipo-360/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/lipo-360/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Lipo 360</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/mommy-makeover/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/mommy-makeover/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Mommy Makeover</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/tummy-tuck/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/tummy-tuck/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Tummy Tuck</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/before-after/patient-journeys/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/before-after/patient-journeys/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Patient Testimonials</a></li>
	</ul>
	<?php
}

/**
 * Render non-surgical menu for both desktop and mobile
 *
 * @param bool $is_mobile Whether to render mobile version.
 * @return void
 */
function mia_aesthetics_render_non_surgical_menu( bool $is_mobile = false ): void {
	$dropdown_class = $is_mobile ? 'd-xl-none' : 'position-static d-none d-xl-block';
	$is_section     = mia_aesthetics_is_current_section( 'non-surgical' );
	$is_exact       = mia_aesthetics_is_current_url( home_url( '/non-surgical/' ) );
	?>
	<li class="nav-item dropdown <?php echo esc_attr( $dropdown_class ); ?> <?php echo $is_section ? 'current-menu-ancestor' : ''; ?>">
		<a class="nav-link dropdown-toggle" href="<?php echo esc_url( home_url( '/non-surgical/' ) ); ?>" 
			role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>
			Non-Surgical
		</a>
		<?php if ( $is_mobile ) : ?>
			<?php mia_aesthetics_render_mobile_non_surgical_menu(); ?>
		<?php else : ?>
			<?php mia_aesthetics_render_desktop_non_surgical_menu(); ?>
		<?php endif; ?>
	</li>
	<?php
}

/**
 * Render desktop non-surgical mega menu
 *
 * @return void
 */
function mia_aesthetics_render_desktop_non_surgical_menu(): void {
	?>
	<div class="dropdown-menu mega-menu w-100 p-3 rounded-0 mt-0">
		<div class="container">
			<div class="row">
				<div class="col-12 mb-3">
					<?php $is_exact_view_all = mia_aesthetics_is_current_url( home_url( '/non-surgical/' ) ); ?>
					<a class="mega-menu-title" href="<?php echo esc_url( home_url( '/non-surgical/' ) ); ?>" <?php echo $is_exact_view_all ? 'aria-current="page"' : ''; ?>>View All Non-Surgical Procedures <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 mb-3">
					<ul class="list-unstyled">
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/non-surgical/j-plasma-skin-tightening/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/non-surgical/j-plasma-skin-tightening/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>J-Plasma</a></li>
						<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/weight-loss/' ) ); ?>
						<li><a class="dropdown-item py-1" href="<?php echo esc_url( home_url( '/weight-loss/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Weight Loss</a></li>
					</ul>
				</div>
				<div class="col-md-3 mb-3"></div>
				<div class="col-md-3 mb-3"></div>
				<div class="col-md-3 mb-3"></div>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Render mobile non-surgical dropdown menu
 *
 * @return void
 */
function mia_aesthetics_render_mobile_non_surgical_menu(): void {
	?>
	<ul class="dropdown-menu">
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/non-surgical/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/non-surgical/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>View All Non-Surgical</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/non-surgical/j-plasma-skin-tightening/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/non-surgical/j-plasma-skin-tightening/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>J-Plasma</a></li>
		<?php $is_exact = mia_aesthetics_is_current_url( home_url( '/weight-loss/' ) ); ?>
		<li><a class="dropdown-item" href="<?php echo esc_url( home_url( '/weight-loss/' ) ); ?>" <?php echo $is_exact ? 'aria-current="page"' : ''; ?>>Weight Loss</a></li>
	</ul>
	<?php
}
