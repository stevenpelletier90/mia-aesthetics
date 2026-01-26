<?php
/**
 * Template Name: Procedures Listing
 * Template Post Type: page, procedure
 * Description: Displays child procedures in a grid layout. Based on Hero Canvas
 * but without featured image display. Perfect for procedure category pages like
 * Body Procedures, Face Procedures, etc.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary" class="hero-canvas-main">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<!-- Page Header / Hero ----------------------------------------->
		<section class="post-header py-5">
			<div class="container">
				<h1><?php echo esc_html( get_the_title() ); ?></h1>
			</div>
		</section>

		<!-- Procedures Listing -->
		<section class="procedures-listing-section">
			<div class="container">
				<div class="row g-4">
					<?php
					// Get child procedures.
					$current_post_id = get_the_ID();

					$child_procedures = get_posts(
						array(
							'post_type'      => 'procedure',
							'post_parent'    => (int) $current_post_id,
							'posts_per_page' => -1,
							'orderby'        => 'menu_order title',
							'order'          => 'ASC',
							'post_status'    => 'publish',
						)
					);

					if ( count( $child_procedures ) > 0 ) :
						foreach ( $child_procedures as $procedure ) :
							setup_postdata( $procedure );
							$procedure_id      = (int) $procedure->ID;
							$procedure_title   = get_the_title( $procedure_id );
							$procedure_excerpt = get_the_excerpt( $procedure_id );
							$procedure_link    = get_permalink( $procedure_id );

							// Type safety checks.
							if ( false === $procedure_title ) {
								$procedure_title = '';
							}
							if ( false === $procedure_excerpt ) {
								$procedure_excerpt = '';
							}
							if ( false === $procedure_link ) {
								continue; // Skip this procedure if we can't get its permalink.
							}
							$procedure_image = get_the_post_thumbnail(
								$procedure_id,
								'medium_large',
								array(
									'class' => 'img-fluid',
									'alt'   => esc_attr( $procedure_title ),
								)
							);

							// Fallback image if no featured image.
							if ( '' === $procedure_image ) {
								$procedure_image = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder-procedure.jpg" alt="' . esc_attr( $procedure_title ) . '" class="img-fluid">';
							}
							?>
							
							<div class="col-lg-4 col-md-6">
								<article class="procedure-card h-100">
									<figure class="procedure-image">
										<a href="<?php echo esc_url( $procedure_link ); ?>" aria-label="Learn more about <?php echo esc_attr( $procedure_title ); ?>">
											<?php echo wp_kses_post( $procedure_image ); ?>
										</a>
									</figure>
									<div class="procedure-content">
										<h2 class="procedure-title">
											<a href="<?php echo esc_url( $procedure_link ); ?>" aria-label="<?php echo esc_attr( $procedure_title ); ?> procedure">
												<?php echo esc_html( $procedure_title ); ?>
											</a>
										</h2>
										<a href="<?php echo esc_url( $procedure_link ); ?>" class="btn btn-outline-primary-alt btn-sm" role="button" aria-label="Learn more about <?php echo esc_attr( $procedure_title ); ?>">
											Learn More
										</a>
									</div>
								</article>
							</div>
							
							<?php
						endforeach;
						wp_reset_postdata();
					else :
						?>
						<div class="col-12 text-center">
							<p class="lead">No procedures found under this category.</p>
						</div>
						<?php
					endif;
					?>
				</div>
			</div>
		</section>

		<!-- Page Content (from WordPress editor) -->
		<?php if ( '' !== trim( get_the_content() ) ) : ?>
			<section class="page-content-section">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<article class="entry-content">
								<?php the_content(); ?>
							</article>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

	<?php endwhile; ?>
</main>


<?php get_footer(); ?>
