<?php
/**
 * Taxonomy Archive Template for "case-category"
 *
 * @package Mia_Aesthetics
 */

get_header();

// Grab current term object.
$mia_current_term = get_queried_object();

// Ensure we have a valid term object.
if ( ! $mia_current_term instanceof WP_Term ) {
	return;
}
?>
<main id="primary">
	
	<?php mia_aesthetics_breadcrumbs(); ?>
	<!-- Archive Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<!-- Show the term name -->
					<h1 class="mb-3">
						<?php single_term_title(); ?>
					</h1>
					
					<!-- Optional: display term description if present -->
					<?php if ( '' !== $mia_current_term->description ) : ?>
						<div class="taxonomy-description">
							<?php echo wp_kses_post( $mia_current_term->description ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- Archive Content -->
	<section class="py-5">
		<div class="container">
			<?php
			// High-performance approach using post__in instead of tax_query.
			$term_slug = sanitize_title( $mia_current_term->slug );
			$cache_key = 'case_category_' . $term_slug . '_page_' . get_query_var( 'paged', 1 );

			$ordered_posts = get_transient( $cache_key );
			if ( false === $ordered_posts ) {
				// Get post IDs directly from taxonomy - much faster than tax_query JOINs.
				$term_ids_cache_key = 'case_term_ids_' . $term_slug;
				$post_ids           = get_transient( $term_ids_cache_key );

				if ( false === $post_ids ) {
					// Validate taxonomy exists and get term.
					if ( taxonomy_exists( 'case-category' ) ) {
						$category_term = get_term_by( 'slug', $term_slug, 'case-category' );
						if ( $category_term instanceof WP_Term ) {
							// Direct taxonomy lookup - bypasses expensive JOINs.
							$post_ids = get_objects_in_term( $category_term->term_id, 'case-category' );
							$post_ids = is_array( $post_ids ) ? array_map( 'intval', $post_ids ) : array();

							// Cache term->post relationships for 30 minutes.
							set_transient( $term_ids_cache_key, $post_ids, 30 * MINUTE_IN_SECONDS );
						} else {
							$post_ids = array();
						}
					} else {
						$post_ids = array();
					}
				}

				if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
					$ordered_posts = new WP_Query(
						array(
							'post_type'              => 'case',
							'posts_per_page'         => 12,
							'paged'                  => get_query_var( 'paged' ),
							// Performance optimizations.
							'no_found_rows'          => false, // Need for pagination.
							'update_post_meta_cache' => false, // Only if meta needed.
							'update_post_term_cache' => false, // Only if terms needed.
						// Use post__in for indexed primary key lookup (much faster).
							'post__in'               => $post_ids,
							'orderby'                => 'title',
							'order'                  => 'ASC',
						)
					);
				} else {
					// Handle invalid term or no posts gracefully.
					$ordered_posts = new WP_Query( array( 'post__in' => array( 0 ) ) );
				}

				// Cache for 10 minutes.
				set_transient( $cache_key, $ordered_posts, 10 * MINUTE_IN_SECONDS );
			}

			if ( $ordered_posts->have_posts() ) :
				?>
				<div class="row g-4">
					<?php
					while ( $ordered_posts->have_posts() ) :
						$ordered_posts->the_post();
						?>
						<div class="col-md-6 col-lg-4">
							<div class="card h-100">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) ); ?>
								<?php endif; ?>

								<div class="card-body d-flex flex-column">
									<h2 class="h5">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h2>
									<?php the_excerpt(); ?>
									
									<div class="mt-auto pt-3">
										<a href="<?php the_permalink(); ?>" class="btn btn-primary" aria-label="View Case: <?php echo esc_attr( get_the_title() ); ?>">
											View Case <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>

				<!-- Pagination -->
				<?php if ( $ordered_posts->max_num_pages > 1 ) : ?>
					<div class="row mt-5">
						<div class="col">
							<?php
							echo wp_kses_post(
								paginate_links(
									array(
										'total'     => $ordered_posts->max_num_pages,
										'current'   => max( 1, get_query_var( 'paged' ) ),
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;',
										'type'      => 'list',
										'class'     => 'pagination justify-content-center',
									)
								)
							);
							?>
						</div>
					</div>
				<?php endif; ?>

			<?php else : ?>
				<div class="row">
					<div class="col">
						<p>No cases found in this category.</p>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
