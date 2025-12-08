<?php
/**
 * Template for displaying single case studies (updated for new ACF group structure)
 *
 * Pulls all sub‑fields from the `case_information` group once and
 * references them locally to minimise DB calls.
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	/*
	------------------------------------------------------------------
	 * Grab everything from the `case_information` ACF group in one go.
	 * ------------------------------------------------------------------
	 */
	$case_info = get_field( 'case_information' );

	$before_photo        = $case_info['before_photo'] ?? null; // Image array or null.
	$after_photo         = $case_info['after_photo'] ?? null; // Image array or null.
	$height              = $case_info['height'] ?? '';
	$weight              = $case_info['weight'] ?? '';
	$bmi                 = $case_info['bmi'] ?? '';
	$surgeon             = $case_info['performed_by_surgeon'] ?? null; // Post object (ID).
	$location            = $case_info['performed_at_location'] ?? null; // Post object (ID).
	$procedure_performed = $case_info['procedure_performed'] ?? array(); // Post object(s).
	$case_links          = $case_info['case_links'] ?? array(); // Post object(s).
	?>

	<!-- Page Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="text-center"><?php the_title(); ?></h1>
				</div>
			</div>
		</div>
	</section>

	<!-- Main Content - Two Column Layout -->
	<article class="py-5 py-lg-6">
		<div class="container">
			<div class="row g-4 g-lg-5">
				<!-- Before & After Images Column -->
				<div class="col-lg-6">
					<?php if ( null !== $before_photo || null !== $after_photo ) : ?>
					<div class="case-images-container">
						<h2 class="h4 mb-3">Before & After</h2>
						<div class="row g-3">
							<?php if ( null !== $before_photo ) : ?>
							<div class="col-6">
								<button type="button" 
									class="position-relative case-image-container border-0 p-0 bg-transparent w-100"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-bs-image="<?php echo esc_url( $before_photo['url'] ); ?>"
									data-bs-title="Before Treatment"
									aria-label="View before treatment image for <?php echo esc_attr( get_the_title() ); ?>">
									<img src="<?php echo esc_url( $before_photo['sizes']['medium_large'] ?? $before_photo['url'] ); ?>"
										class="img-fluid rounded cursor-pointer"
										alt="Before Treatment – <?php echo esc_attr( get_the_title() ); ?>"
										loading="lazy">
									<span class="before-label">Before</span>
								</button>
							</div>
							<?php endif; ?>

							<?php if ( null !== $after_photo ) : ?>
							<div class="col-6">
								<button type="button" 
									class="position-relative case-image-container border-0 p-0 bg-transparent w-100"
									data-bs-toggle="modal"
									data-bs-target="#imageModal"
									data-bs-image="<?php echo esc_url( $after_photo['url'] ); ?>"
									data-bs-title="After Treatment"
									aria-label="View after treatment image for <?php echo esc_attr( get_the_title() ); ?>">
									<img src="<?php echo esc_url( $after_photo['sizes']['medium_large'] ?? $after_photo['url'] ); ?>"
										class="img-fluid rounded cursor-pointer"
										alt="After Treatment – <?php echo esc_attr( get_the_title() ); ?>"
										loading="lazy">
									<span class="after-label">After</span>
								</button>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>
				</div>

				<!-- Patient Information Column -->
				<div class="col-lg-6">
					<?php /* ---------------- Patient Information -------------- */ ?>
					<?php
					$has_height_weight_bmi = '' !== $height || '' !== $weight || '' !== $bmi;
					$has_surgeon_location  = null !== $surgeon || null !== $location;
					?>
					<section class="mb-5">
						<h2 class="h4 mb-3">Patient Information</h2>
						<?php if ( ! $has_height_weight_bmi && ! $has_surgeon_location ) : ?>
							<div class="alert alert-info text-center" role="status" aria-live="polite">
								Protected for Patient Privacy
							</div>
						<?php else : ?>
							<?php if ( $has_height_weight_bmi ) : ?>
							<!-- Height, Weight, BMI Row -->
							<div class="row g-3 mb-3">
								<?php if ( '' !== $height ) : ?>
								<div class="col-4">
									<div class="patient-info-card">
										<div class="patient-info-label mb-2">Height</div>
										<p class="mb-0"><?php echo esc_html( $height ); ?></p>
									</div>
								</div>
								<?php endif; ?>

								<?php if ( '' !== $weight ) : ?>
								<div class="col-4">
									<div class="patient-info-card">
										<div class="patient-info-label mb-2">Weight</div>
										<p class="mb-0"><?php echo esc_html( $weight ); ?> lbs</p>
									</div>
								</div>
								<?php endif; ?>

								<?php if ( '' !== $bmi ) : ?>
								<div class="col-4">
									<div class="patient-info-card">
										<div class="patient-info-label mb-2">BMI</div>
										<p class="mb-0"><?php echo esc_html( $bmi ); ?></p>
									</div>
								</div>
								<?php endif; ?>
							</div>
							<?php endif; ?>

							<?php if ( $has_surgeon_location || ( is_array( $procedure_performed ) && count( $procedure_performed ) > 0 ) ) : ?>
							<!-- Surgeon, Location, and Procedure Row -->
							<div class="row g-3">
								<?php if ( null !== $surgeon && '' !== $surgeon ) : ?>
								<div class="col-6">
									<?php
									$surgeon_permalink = get_permalink( $surgeon );
									if ( false !== $surgeon_permalink ) :
										?>
									<a href="<?php echo esc_url( $surgeon_permalink ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
										<div class="patient-info-label mb-2">Performed By</div>
										<p class="mb-0"><?php echo esc_html( get_the_title( $surgeon ) ); ?></p>
										<i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
									</a>
									<?php else : ?>
									<div class="patient-info-card">
										<div class="patient-info-label mb-2">Performed By</div>
										<p class="mb-0"><?php echo esc_html( get_the_title( $surgeon ) ); ?></p>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>

								<?php if ( null !== $location ) : ?>
								<div class="col-6">
									<?php
									$location_permalink = get_permalink( $location );
									if ( false !== $location_permalink ) :
										?>
									<a href="<?php echo esc_url( $location_permalink ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
										<div class="patient-info-label mb-2">Location</div>
										<p class="mb-0"><?php echo esc_html( get_the_title( $location ) ); ?></p>
										<i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
									</a>
									<?php else : ?>
									<div class="patient-info-card">
										<div class="patient-info-label mb-2">Location</div>
										<p class="mb-0"><?php echo esc_html( get_the_title( $location ) ); ?></p>
									</div>
									<?php endif; ?>
								</div>
								<?php endif; ?>

								<?php if ( is_array( $procedure_performed ) && count( $procedure_performed ) > 0 ) : ?>
									<?php foreach ( $procedure_performed as $procedure_id ) : ?>
										<div class="col-6">
											<?php
											$procedure_permalink = get_permalink( $procedure_id );
											if ( false !== $procedure_permalink ) :
												?>
											<a href="<?php echo esc_url( $procedure_permalink ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
												<div class="patient-info-label mb-2">Procedure<?php echo count( $procedure_performed ) > 1 ? 's' : ''; ?></div>
												<p class="mb-0"><?php echo esc_html( get_the_title( $procedure_id ) ); ?></p>
												<i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
											</a>
											<?php else : ?>
											<div class="patient-info-card">
												<div class="patient-info-label mb-2">Procedure<?php echo count( $procedure_performed ) > 1 ? 's' : ''; ?></div>
												<p class="mb-0"><?php echo esc_html( get_the_title( $procedure_id ) ); ?></p>
											</div>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						<?php endif; ?>

						<?php /* ---------------- Patient Background ------------- */ ?>
						<?php if ( '' !== trim( get_the_content() ) ) : ?>
							<h3 class="h5 mb-3 mt-4">Patient Background</h3>
							<div class="case-background">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>
					</section>


				</div>
			</div>
		</div>
	</article>

	<?php /* ------------- Treatment & Recovery -------------- */ ?>
	<?php if ( is_array( $case_links ) && count( $case_links ) > 0 ) : ?>
	<section class="py-4 py-lg-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="h4 mb-4">Treatment &amp; Recovery Resources</h2>
					<div class="row g-3">
						<?php foreach ( $case_links as $resource_id ) : ?>
							<div class="col-6">
								<?php
								$resource_permalink = get_permalink( $resource_id );
								if ( false !== $resource_permalink ) :
									?>
								<a href="<?php echo esc_url( $resource_permalink ); ?>" class="patient-info-card patient-info-card-link text-decoration-none">
									<h5 class="h6">Resource</h5>
									<p class="mb-0"><?php echo esc_html( get_the_title( $resource_id ) ); ?></p>
									<i class="fas fa-chevron-right patient-info-arrow" aria-hidden="true"></i>
								</a>
								<?php else : ?>
								<div class="patient-info-card">
									<h5 class="h6">Resource</h5>
									<p class="mb-0"><?php echo esc_html( get_the_title( $resource_id ) ); ?></p>
								</div>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<?php /* ------------- Related Cases -------------- */ ?>
	<?php
	// Get related cases based on case-category taxonomy.
	$case_post_id = get_the_ID();
	if ( false === $case_post_id ) {
		// Avoid aborting the entire template; skip related section gracefully.
		$case_post_id = 0;
	}

	$current_category_terms = wp_get_post_terms( $case_post_id, 'case-category' );

	// Handle WP_Error or empty results.
	if ( is_wp_error( $current_category_terms ) || ! is_array( $current_category_terms ) || 0 === count( $current_category_terms ) ) {
		// No valid terms — skip related cases without exiting the template.
		$current_category_terms = array();
	}

	// Filter out broad parent categories, use only specific child categories.
	$specific_category_ids = array();

	foreach ( $current_category_terms as $category_term ) {
		// Use only child categories (those with a parent), skip top-level parent categories.
		if ( 0 !== $category_term->parent ) {
			$specific_category_ids[] = $category_term->term_id;
		}
	}

	if ( array() !== $specific_category_ids ) {
		// Query using only the most specific categories (exclude top-level parent cases).
		$related_cases = new WP_Query(
			array(
				'post_type'              => 'case',
				'post_status'            => 'publish',
				'post__not_in'           => array( get_the_ID() ),
				'posts_per_page'         => 4,
				'orderby'                => 'date',
				'order'                  => 'DESC',
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'tax_query'              => array(
					array(
						'taxonomy' => 'case-category',
						'field'    => 'term_id',
						'terms'    => $specific_category_ids,
						'operator' => 'IN',
					),
				),
				// Performance optimizations.
				'update_post_meta_cache' => false, // We don't read meta in the loop.
				'update_post_term_cache' => true,  // We render terms in the loop.
				'no_found_rows'          => true,  // Skip count query.
				'ignore_sticky_posts'    => true,  // Ignore sticky posts.
			)
		);

		if ( $related_cases->have_posts() ) :
			?>
	<section class="py-4 py-lg-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h2 class="h4 mb-4">Related Cases</h2>
					<div class="row g-3">
						<?php
						while ( $related_cases->have_posts() ) :
							$related_cases->the_post();
							?>
							<div class="col-md-6">
								<div class="related-case-item">
									<a href="<?php the_permalink(); ?>" class="related-case-link">
										<div class="related-case-number">
											<span><?php echo esc_html( str_pad( (string) ( $related_cases->current_post + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
										</div>
										<div class="related-case-content">
											<h3 class="related-case-title"><?php the_title(); ?></h3>
											<span class="related-case-meta">Case Study</span>
										</div>
										<div class="related-case-arrow">
											<i class="fas fa-arrow-right" aria-hidden="true"></i>
										</div>
									</a>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>
			</div>
		</div>
	</section>
			<?php
		endif; // End related cases have_posts check.
		wp_reset_postdata();
	} // End current_categories check.
	?>

	<?php /* -------------------- FAQ Section ---------------------- */ ?>
	<?php
	$faq_section = get_field( 'faq_section' );
	if ( null !== $faq_section && is_array( $faq_section['faqs'] ?? null ) && count( $faq_section['faqs'] ) > 0 ) :
		?>
	<section class="py-5 py-lg-6">
		<div class="container">
			<div class="faq-container">
				<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>
			</div>
		</div>
	</section>
	<?php endif; ?>
</main>

<!-- Image Modal with Carousel -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="imageModalLabel"><?php the_title(); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="caseCarousel" class="carousel slide carousel-fade" data-bs-ride="false">
					<div class="carousel-inner">
						<?php if ( null !== $before_photo ) : ?>
						<div class="carousel-item active">
							<img src="<?php echo esc_url( $before_photo['url'] ); ?>" class="d-block w-100" alt="Before Treatment – <?php echo esc_attr( get_the_title() ); ?>">
						</div>
						<?php endif; ?>

						<?php if ( null !== $after_photo ) : ?>
						<div class="carousel-item<?php echo null === $before_photo ? ' active' : ''; ?>">
							<img src="<?php echo esc_url( $after_photo['url'] ); ?>" class="d-block w-100" alt="After Treatment – <?php echo esc_attr( get_the_title() ); ?>">
						</div>
						<?php endif; ?>
					</div>

					<?php if ( null !== $before_photo && null !== $after_photo ) : ?>
					<button class="carousel-control-prev" type="button" data-bs-target="#caseCarousel" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#caseCarousel" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
