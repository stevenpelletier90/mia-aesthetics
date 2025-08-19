<?php
/**
 * Surgeons Archive Template
 * Custom template for displaying all surgeons in a more organized layout
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main>
<?php mia_aesthetics_breadcrumbs(); ?>
	
	<!-- Archive Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="mb-2"><?php post_type_archive_title(); ?></h1>
					<p class="lead mb-0">Meet our team of board-certified plastic surgeons dedicated to providing exceptional care and results.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Archive Content -->
	<section class="py-5">
		<div class="container">
			<?php
			// Default WordPress loop will handle the query for this archive.
			if ( have_posts() ) :
				?>
				<div class="row"> <?php // Bootstrap row for the grid. ?>
					<?php
					while ( have_posts() ) :
						the_post();
						// Get surgeon location if available.
						$location       = get_field( 'surgeon_location' );
						$location_id    = '';
						$location_title = '';
						$location_class = '';

						if ( null !== $location && '' !== $location ) {
							// Handle Post Object return format.
							if ( is_object( $location ) && property_exists( $location, 'ID' ) && property_exists( $location, 'post_title' ) ) {
								$location_id    = (int) $location->ID;
								$location_title = $location->post_title;
								$location_class = 'location-' . $location_id;
							} elseif ( is_numeric( $location ) ) {
								// Handle Post ID return format.
								$location_id    = (int) $location;
								$location_title = get_the_title( $location_id );
								$location_class = 'location-' . $location_id;
								// Ensure get_the_title() didn't return false.
								if ( false === $location_title ) {
									$location_title = '';
								}
							}
						}

						// Get surgeon headshot ID.
						$headshot_id = get_field( 'surgeon_headshot' );
						?>
						<div class="col-sm-6 col-lg-4 mb-4 <?php echo esc_attr( $location_class ); ?>">
							<div class="card surgeon-card h-100">
								<div class="card-body d-flex">
									<div class="surgeon-img-container me-3">
										<?php
										// Display headshot if ID exists.
										if ( null !== $headshot_id && '' !== $headshot_id && is_numeric( $headshot_id ) ) :
											echo wp_get_attachment_image(
												(int) $headshot_id,
												'thumbnail', // Use thumbnail size for circular image.
												false,
												array(
													'class' => 'surgeon-archive-headshot rounded-circle', // Add classes for styling.
													'alt' => get_the_title() . ' Headshot',
												)
											);
											// Fallback to post thumbnail if headshot field is empty but thumbnail exists.
										elseif ( has_post_thumbnail() ) :
											the_post_thumbnail( 'thumbnail', array( 'class' => 'surgeon-archive-headshot rounded-circle' ) );
										endif;
										?>
									</div>
									<div class="surgeon-info d-flex flex-column justify-content-center">
										<h2 class="h5 mb-1"> 
											<?php the_title(); ?>
										</h2>
										
										<?php if ( '' !== $location_title && '' !== $location_id ) : ?>
											<?php
											$location_permalink = get_permalink( $location_id );
											if ( false !== $location_permalink ) :
												?>
										<p class="small mb-2"> 
											<a href="<?php echo esc_url( $location_permalink ); ?>" class="location-link">
												<?php echo esc_html( $location_title ); ?>
											</a>
										</p>
											<?php endif; ?>
										<?php endif; ?>
										
										<?php
										$surgeon_permalink = get_permalink();
										if ( false !== $surgeon_permalink ) :
											?>
										<a href="<?php echo esc_url( $surgeon_permalink ); ?>" class="surgeon-bio-link">View Bio <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

			<?php else : ?>
				<div class="row">
					<div class="col text-center">
						<p>No surgeons found.</p>
					</div>
				</div>
			<?php endif; ?>
			
			<!-- Introduction Text - Moved below the surgeon grid -->
			<div class="row mt-5 pt-5 border-top">
				<div class="col-lg-8">
					<h2 class="mb-4">Expert Plastic Surgeons</h2>
					<p class="mb-4">Our surgeons are leaders in the field of aesthetic plastic surgery, combining artistry and technical expertise to deliver beautiful, natural-looking results. Each surgeon brings unique specializations and approaches to help you achieve your aesthetic goals.</p>
					
					<h2 class="mb-4 mt-5">Comprehensive Care Approach</h2>
					<p>At our practice, we believe in a patient-centered approach to care. Our board-certified surgeons work closely with you to understand your goals and develop a personalized treatment plan that addresses your specific needs. From the initial consultation through recovery, our team is committed to providing exceptional support and guidance at every step of your journey.</p>
				</div>
			</div>
		</div>
	</section>
	
</main>

<?php get_footer(); ?>
