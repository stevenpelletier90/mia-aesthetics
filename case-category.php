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
<main>
	
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
			// Custom query to order posts by title alphabetically.
			$term_slug     = $mia_current_term->slug;
			$ordered_posts = new WP_Query(
				array(
					'post_type'      => 'case',
					'posts_per_page' => 12,
					'paged'          => get_query_var( 'paged' ),
					'tax_query'      => array(
						array(
							'taxonomy' => 'case-category',
							'field'    => 'slug',
							'terms'    => $term_slug,
						),
					),
					'orderby'        => 'title',
					'order'          => 'ASC',
				)
			);

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
										<a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold-outline">
											View Case <i class="fa-solid fa-arrow-right"></i>
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
