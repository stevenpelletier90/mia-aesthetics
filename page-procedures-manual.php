<?php
/**
 * Template Name: Procedures Manual Layout
 * Template Post Type: procedure
 * Description: Displays manually curated procedure posts in a grid layout.
 * Similar to Procedures Listing but with manually placed content instead of
 * automatically pulling child procedures. Perfect for specialized procedure
 * collections like male-specific procedures.
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

		<!-- Manual Procedures Grid -->
		<section class="procedures-manual-section">
			<div class="container">
				<div class="row g-4">
					<?php
					// Manually placed procedure IDs.
					// These can be hardcoded or managed via ACF field.
					$manual_procedures = array();

					// Check if we have an ACF field for manual procedures.
					if ( function_exists( 'get_field' ) && get_field( 'manual_procedures' ) ) {
						$manual_procedures = get_field( 'manual_procedures' );
					} else {
						// Fallback to hardcoded IDs based on the URLs provided.
						// You'll need to replace these with actual post IDs.
						$manual_procedures = array(
							// Male BBL - https://miaprod.wpenginepowered.com/cosmetic-plastic-surgery/body/male-bbl/
							array(
								'post_id'      => 205,
								'custom_title' => 'Male BBL',
							),

							// Male Breast Procedures - https://miaprod.wpenginepowered.com/cosmetic-plastic-surgery/breast/male-breast-procedures/
							array(
								'post_id'      => 231,
								'custom_title' => 'Male Breast Procedures',
							),

							// Male Liposuction - https://miaprod.wpenginepowered.com/cosmetic-plastic-surgery/body/male-liposuction/
							array(
								'post_id'      => 206,
								'custom_title' => 'Male Liposuction',
							),

							// Male Tummy Tuck - https://miaprod.wpenginepowered.com/cosmetic-plastic-surgery/body/male-tummy-tuck/
							array(
								'post_id'      => 208,
								'custom_title' => 'Male Tummy Tuck',
							),
						);
					}

					if ( ! empty( $manual_procedures ) ) :
						foreach ( $manual_procedures as $procedure_data ) :
							// Handle both simple ID arrays and complex arrays with custom data.
							if ( is_numeric( $procedure_data ) ) {
								$procedure_id   = $procedure_data;
								$custom_title   = '';
								$custom_excerpt = '';
							} else {
								$procedure_id   = isset( $procedure_data['post_id'] ) ? $procedure_data['post_id'] : ( isset( $procedure_data['procedure'] ) ? $procedure_data['procedure'] : 0 );
								$custom_title   = isset( $procedure_data['custom_title'] ) ? $procedure_data['custom_title'] : '';
								$custom_excerpt = isset( $procedure_data['custom_excerpt'] ) ? $procedure_data['custom_excerpt'] : '';
							}

							if ( ! $procedure_id ) {
								continue;
							}

							$procedure = get_post( $procedure_id );
							if ( ! $procedure || $procedure->post_status !== 'publish' ) {
								continue;
							}

							setup_postdata( $procedure );

							// Use custom or default values.
							$procedure_title   = $custom_title ?: get_the_title( $procedure_id );
							$procedure_excerpt = $custom_excerpt ?: get_the_excerpt( $procedure_id );
							$procedure_link    = get_permalink( $procedure_id );
							$procedure_image   = get_the_post_thumbnail(
								$procedure_id,
								'medium_large',
								array(
									'class' => 'img-fluid',
									'alt'   => esc_attr( $procedure_title ),
								)
							);

							// Fallback image if no featured image.
							if ( ! $procedure_image ) {
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
										<h3 class="procedure-title">
											<a href="<?php echo esc_url( $procedure_link ); ?>">
												<?php echo esc_html( $procedure_title ); ?>
											</a>
										</h3>
										<a href="<?php echo esc_url( $procedure_link ); ?>" class="mia-button" data-variant="gold-outline" data-size="sm" role="button">
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
							<p class="lead">No procedures have been selected for this page.</p>
						</div>
						<?php
					endif;
					?>
				</div>
			</div>
		</section>

		<!-- Page Content (from WordPress editor) -->
		<?php if ( get_the_content() ) : ?>
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