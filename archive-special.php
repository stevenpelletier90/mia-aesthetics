<?php
/**
 * The template for displaying the Specials archive page.
 *
 * @package Mia_Aesthetics
 */

get_header();

// Get current language filter from URL parameter, default to English.
$current_language = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : 'english';
?>

<div class="specials-archive-page">

	<!-- 1. Hero Section -->
	<header class="specials-hero text-center">
		<div class="container">
			<h1 class="display-4 fw-bold">Current Specials</h1>
			<p class="lead mb-4">Limited-time offers on your favorite treatments</p>
			<a href="/free-virtual-consultation/" class="btn btn-primary btn-lg">
				Free Virtual Consultation <i class="fas fa-arrow-right"></i>
			</a>
		</div>
	</header>

	<!-- 2. Language Toggle -->
	<div class="specials-language-toggle py-3">
		<div class="container">
			<div class="btn-group" role="group" aria-label="Language filter">
				<button type="button" class="btn <?php echo 'english' === $current_language ? 'btn-primary active' : 'btn-outline-primary'; ?>" 
						data-lang="english">English</button>
				<button type="button" class="btn <?php echo 'spanish' === $current_language ? 'btn-primary active' : 'btn-outline-primary'; ?>" 
						data-lang="spanish">Español</button>
			</div>
		</div>
	</div>

	<!-- 3. Specials Archive Grid -->
	<main class="specials-grid py-5">
		<div class="container">

			<?php
			// Modern WordPress query optimization with caching.
			$cache_key     = 'specials_archive_' . $current_language;
			$specials_data = get_transient( $cache_key );

			if ( false === $specials_data ) {
				// Build optimized single query with custom ordering.
				$query_args = array(
					'post_type'              => 'special',
					'posts_per_page'         => 50, // Limit to reasonable number instead of -1.
					'post_status'            => 'publish',
					// Performance optimizations.
					'no_found_rows'          => true,
					'update_post_meta_cache' => false, // Only enable if actually needed.
					'update_post_term_cache' => false, // Only enable if actually needed.
					// More efficient ordering using menu_order for sticky posts.
					'orderby'                => array(
						'menu_order' => 'DESC',  // Use menu_order instead of meta_query.
						'date'       => 'DESC',  // Then by date.
					),
				);

				// Add language filtering using high-performance post__in approach.
				if ( in_array( $current_language, array( 'english', 'spanish' ), true ) ) {
					$language_term = ( 'english' === $current_language ) ? 'english-specials' : 'spanish-specials';

					// Get post IDs directly from taxonomy - much faster than tax_query.
					$term_cache_key = 'special_term_ids_' . $language_term;
					$post_ids       = get_transient( $term_cache_key );

					if ( false === $post_ids ) {
						// Validate taxonomy exists before querying.
						if ( taxonomy_exists( 'special-category' ) ) {
							$language_term_obj = get_term_by( 'slug', $language_term, 'special-category' );
							if ( $language_term_obj instanceof WP_Term ) {
								// Direct taxonomy lookup - bypasses expensive JOINs.
								$post_ids = get_objects_in_term( $language_term_obj->term_id, 'special-category' );
								$post_ids = is_array( $post_ids ) ? array_map( 'intval', $post_ids ) : array();

								// Cache term->post relationships for 30 minutes.
								set_transient( $term_cache_key, $post_ids, 30 * MINUTE_IN_SECONDS );
							} else {
								$post_ids = array();
							}
						} else {
							$post_ids = array();
						}
					}

					// Use post__in for indexed primary key lookup (much faster).
					if ( ! empty( $post_ids ) ) {
						$query_args['post__in'] = $post_ids;
					} else {
						// No posts found for this language.
						$query_args['post__in'] = array( 0 ); // Returns no results.
					}
				}

				$specials_query = new WP_Query( $query_args );

				// Cache the results for 15 minutes.
				$specials_data = array(
					'posts'     => $specials_query->posts,
					'has_posts' => $specials_query->have_posts(),
				);
				set_transient( $cache_key, $specials_data, 15 * MINUTE_IN_SECONDS );

				// Clean up.
				wp_reset_postdata();
			}

			$has_specials = $specials_data['has_posts'];
			?>

			<?php if ( $has_specials ) : ?>

				<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 g-lg-5">

					<?php
					// Display all specials (sticky first due to custom ordering).
					foreach ( $specials_data['posts'] as $special_post ) :
						setup_postdata( $special_post );
						$post_id = get_the_ID();
						$is_sticky  = $post_id !== false ? (bool) get_post_meta( $post_id, 'is_sticky', true ) : false;
						$is_spanish = (bool) has_term( 'spanish-specials', 'special-category' );
						?>

							<div class="col">
								<article class="special-card h-100 <?php echo $is_sticky ? 'sticky-special' : ''; ?>">
									<?php if ( $is_sticky ) : ?>
										<div class="featured-badge">
											<span><?php echo $is_spanish ? 'Destacado' : 'Featured'; ?></span>
										</div>
									<?php endif; ?>
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="special-card-image">
											<a href="<?php the_permalink(); ?>"
												aria-label="View <?php echo esc_attr( get_the_title() ); ?>">
												<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
											</a>
										</div>
									<?php endif; ?>
									<div class="special-card-body">
										<?php
										$permalink = get_permalink();
										if ( false !== $permalink ) :
											the_title(
												'<h2 class="special-card-title"><a href="' . esc_url( $permalink ) . '">',
												'</a></h2>'
											);
										else :
											the_title( '<h2 class="special-card-title">', '</h2>' );
										endif;
										?>
										<div class="special-card-excerpt">
											<?php the_excerpt(); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="btn <?php echo $is_sticky ? 'btn-primary' : 'btn-outline-primary'; ?>">
											<?php echo $is_spanish ? 'Ver Oferta' : 'View Special'; ?> 
											<i class="fas fa-arrow-right"></i>
										</a>
									</div>
								</article>
							</div>

							<?php
					endforeach;
					wp_reset_postdata();
					?>

				</div> <!-- .row -->

			<?php else : ?>

				<div class="text-center">
					<h2><?php echo 'spanish' === $current_language ? 'No Se Encontraron Ofertas' : 'No Specials Found'; ?></h2>
					<p><?php echo 'spanish' === $current_language ? 'Por favor, vuelva más tarde para nuevas ofertas.' : 'Please check back later for new offers.'; ?></p>
				</div>

			<?php endif; ?>

		</div> <!-- .container -->
	</main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>