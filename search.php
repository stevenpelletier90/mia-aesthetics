<?php
/**
 * Search results template
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" class="search-results">
<?php mia_aesthetics_breadcrumbs(); ?>
	
	<!-- Page Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<h1>
						<?php
						printf(
							// translators: %s is the search query entered by the user.
							esc_html__( 'Search Results for: %s', 'mia-aesthetics' ),
							'<span class="search-highlight">' . esc_html( get_search_query() ) . '</span>'
						);
						?>
					</h1>
					<?php
					$total_results = $GLOBALS['wp_query']->found_posts;
					if ( $total_results > 0 ) {
						printf(
							'<p class="lead mb-0">Found %d %s matching your search.</p>',
							absint( $total_results ),
							1 === $total_results ? 'result' : 'results'
						);
					} else {
						echo '<p class="lead mb-0">No results found for your search query.</p>';
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="py-5">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="row g-4">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="col-md-6 col-lg-4">
							<div class="card h-100">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) ); ?>
								<?php endif; ?>
								
								<div class="card-body">
									<h2 class="h5">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h2>
									<?php the_excerpt(); ?>
									<div class="search-type-badge">
										<?php
										$search_post_type = get_post_type();
										if ( false !== $search_post_type ) {
											$search_post_type_object = get_post_type_object( $search_post_type );
											if ( null !== $search_post_type_object ) {
												echo esc_html( $search_post_type_object->labels->singular_name );
											}
										}
										?>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<div class="row mt-5">
					<div class="col">
						<?php
						the_posts_pagination(
							array(
								'prev_text' => '&laquo;',
								'next_text' => '&raquo;',
								'class'     => 'pagination justify-content-center',
							)
						);
						?>
					</div>
				</div>

			<?php else : ?>
				<div class="row">
					<div class="col">
						<div class="no-results">
							<h3>No Results Found</h3>
							<p>We couldn't find any content matching your search. Try different keywords or browse our procedures and services.</p>
							<div class="mt-4">
								<a href="<?php echo esc_url( home_url( '/procedures/' ) ); ?>" class="btn btn-outline-primary me-3">
									Browse Procedures
								</a>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-outline-primary">
									Return Home
								</a>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
