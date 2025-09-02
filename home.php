<?php
/**
 * The template for displaying the blog posts index
 *
 * This template is used when a static front page is set and
 * another page is designated to display the blog posts index.
 * In your case, this is the /patient-resources/ page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	<!-- Blog Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<h1>Patient Resources & Blog</h1>
					<p class="lead mt-3">Stay informed with the latest news, tips, and insights from our medical professionals.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Blog Content -->
	<section class="py-5">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="row g-4">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="col-md-6 col-lg-4">
							<div class="card h-100 blog-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php
									the_post_thumbnail(
										'medium',
										array(
											'class' => 'card-img-top',
										)
									);
									?>
								<?php endif; ?>
								
								<div class="card-body">
									<h2 class="h5">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h2>
									<?php the_excerpt(); ?>
								</div>
								<div class="card-footer bg-white border-top-0">
									<a href="<?php the_permalink(); ?>" class="read-more-link">
										Read Full Article: <?php the_title(); ?> <i class="fas fa-arrow-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>

				<!-- Simplified Pagination -->
				<div class="row mt-5">
					<div class="col">
						<nav aria-label="Page navigation">
							<?php
							// Don't display pagination if there's only one page.
							if ( $GLOBALS['wp_query']->max_num_pages > 1 ) :

								// Get current page.
								$current_page = max( 1, get_query_var( 'paged' ) );

									// Get total pages.
									$total_pages = $GLOBALS['wp_query']->max_num_pages;

								echo '<div class="d-flex justify-content-between align-items-center pagination-container">';
									// Previous button.
								if ( $current_page > 1 ) {
									echo '<a href="' . esc_url( get_pagenum_link( $current_page - 1 ) ) . '" class="btn btn-outline-primary" aria-label="Previous page"><i class="fas fa-chevron-left me-1" aria-hidden="true"></i> Previous</a>';
								} else {
									echo '<button class="btn btn-outline-primary" aria-label="Previous page" disabled><i class="fas fa-chevron-left me-1" aria-hidden="true"></i> Previous</button>';
								}

								// Page indicator.
								echo '<span class="page-indicator">Page ' . esc_html( $current_page ) . ' of ' . esc_html( $total_pages ) . '</span>';

								// Next button.
								if ( $current_page < $total_pages ) {
									echo '<a href="' . esc_url( get_pagenum_link( $current_page + 1 ) ) . '" class="btn btn-outline-primary" aria-label="Next page">Next <i class="fas fa-chevron-right ms-1" aria-hidden="true"></i></a>';
								} else {
									echo '<button class="btn btn-outline-primary" aria-label="Next page" disabled>Next <i class="fas fa-chevron-right ms-1" aria-hidden="true"></i></button>';
								}

								echo '</div>';

							endif;
							?>
						</nav>
					</div>
				</div>

			<?php else : ?>
				<div class="row">
					<div class="col text-center py-5">
						<div class="alert alert-info">
							<i class="fas fa-info-circle me-2" aria-hidden="true"></i> No blog posts have been published yet. Check back soon for updates!
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
