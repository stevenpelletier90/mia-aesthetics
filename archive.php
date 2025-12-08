<?php
/**
 * The template for displaying archive pages
 *
 * This template is used for category, tag, author, date, and custom post type archives.
 * It displays a list of posts in a card-based grid layout with pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
	<?php mia_aesthetics_breadcrumbs(); ?>
	<!-- Archive Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<?php
					if ( is_category() ) {
						echo '<h1>Category: ' . esc_html( single_cat_title( '', false ) ?? '' ) . '</h1>';
					} elseif ( is_tag() ) {
						echo '<h1>Tag: ' . esc_html( single_tag_title( '', false ) ?? '' ) . '</h1>';
					} elseif ( is_author() ) {
						echo '<h1>Author: ' . esc_html( get_the_author() ) . '</h1>';
					} elseif ( is_post_type_archive() ) {
						echo '<h1>' . esc_html( post_type_archive_title( '', false ) ?? '' ) . '</h1>';
					} else {
						echo '<h1>' . wp_kses_post( get_the_archive_title() ) . '</h1>';
					}

					// Archive description if it exists.
					if ( '' !== get_the_archive_description() ) {
						echo '<div class="archive-description mt-3">';
						the_archive_description();
						echo '</div>';
					}
					?>
				</div>
			</div>
		</div>
	</section>

	<!-- Archive Content -->
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
											<?php echo esc_html( get_the_title() ); ?>
										</a>
									</h2>
									<?php the_excerpt(); ?>
								</div>
								<div class="card-footer bg-white border-top-0">
									<a href="<?php the_permalink(); ?>" class="read-more-link">
										Read Full Article: <?php echo esc_html( get_the_title() ); ?> <i class="fas fa-arrow-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
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
									echo '<a href="' . esc_url( get_pagenum_link( $current_page - 1 ) ) . '" class="btn btn-outline-primary" aria-label="Previous page">';
									echo '<i class="fas fa-chevron-left"></i> Previous';
									echo '</a>';
								} else {
									echo '<button class="btn btn-outline-primary disabled" disabled aria-label="Previous page">';
									echo '<i class="fas fa-chevron-left"></i> Previous';
									echo '</button>';
								}

								// Page indicator.
								echo '<span class="page-indicator">Page ' . esc_html( $current_page ) . ' of ' . esc_html( $total_pages ) . '</span>';

								// Next button.
								if ( $current_page < $total_pages ) {
									echo '<a href="' . esc_url( get_pagenum_link( $current_page + 1 ) ) . '" class="btn btn-outline-primary" aria-label="Next page">';
									echo 'Next <i class="fas fa-chevron-right"></i>';
									echo '</a>';
								} else {
									echo '<button class="btn btn-outline-primary disabled" disabled aria-label="Next page">';
									echo 'Next <i class="fas fa-chevron-right"></i>';
									echo '</button>';
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
							<i class="fas fa-info-circle me-2" aria-hidden="true"></i> No posts found in this archive.
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
