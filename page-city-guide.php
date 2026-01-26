<?php
/**
 * Template Name: City Guide
 * Template Post Type: page
 *
 * Displays city guides based on locations that have the city_guide field set.
 *
 * @package Mia_Aesthetics
 */

get_header();

// Get cached location IDs that have city guides assigned.
$city_guide_location_ids = mia_get_city_guide_location_ids();

// Query locations using cached IDs (fast query, no meta_query needed).
if ( count( $city_guide_location_ids ) > 0 ) {
	$locations = new WP_Query(
		array(
			'post_type'      => 'location',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
			'post__in'       => $city_guide_location_ids,
		)
	);
} else {
	// No locations with city guides - create empty query.
	$locations = new WP_Query( array( 'post__in' => array( 0 ) ) );
}
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<!-- Page Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="mb-2"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="lead mb-0"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php else : ?>
						<p class="lead mb-0">Explore our city guides to discover the best of each location.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- City Guide Content -->
	<section class="city-guide-archive-section py-5">
		<div class="container">
			<?php if ( $locations->have_posts() ) : ?>
				<!-- Mobile List View -->
				<div class="city-guide-mobile-list d-md-none">
					<?php
					while ( $locations->have_posts() ) :
						$locations->the_post();
						$location_id = get_the_ID();

						// Get the city guide post object.
						$city_guide = get_field( 'city_guide' );
						if ( null === $city_guide || false === $city_guide ) {
							continue;
						}

						// Get city guide URL from the linked post.
						$city_guide_url = get_permalink( $city_guide );
						if ( false === $city_guide_url || ! is_string( $city_guide_url ) ) {
							continue;
						}

						// Get location address.
						$location_map = get_field( 'location_map' );
						?>
						<div class="city-guide-list-item">
							<div class="d-flex justify-content-between align-items-start">
								<div class="city-guide-info flex-grow-1">
									<h2 class="h5 mb-2">
										<a href="<?php echo esc_url( $city_guide_url ); ?>" class="text-decoration-none">
											<?php echo esc_html( get_the_title() ); ?>
										</a>
									</h2>
									<?php
									if ( null !== $location_map && is_array( $location_map ) && count( $location_map ) > 0 ) :
										$street = ( isset( $location_map['street_number'] ) ? $location_map['street_number'] : '' ) . ' ' . ( isset( $location_map['street_name'] ) ? $location_map['street_name'] : '' );
										$city   = isset( $location_map['city'] ) ? $location_map['city'] : '';
										$state  = isset( $location_map['state_short'] ) ? $location_map['state_short'] : '';
										$zip    = isset( $location_map['post_code'] ) ? $location_map['post_code'] : '';
										$street = trim( $street );

										// Special handling for Brooklyn/NYC addresses.
										if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
											$city = 'Brooklyn';
										}
										?>
										<?php if ( '' !== $street || '' !== $city || '' !== $state || '' !== $zip ) : ?>
											<div class="small mb-1">
												<?php if ( '' !== $street && '0' !== $street ) : ?>
													<div><?php echo esc_html( $street ); ?></div>
												<?php endif; ?>
												<?php
												$address_line2 = '';
												if ( '' !== $city ) {
													$address_line2 .= $city;
												}
												if ( '' !== $state ) {
													$address_line2 .= ( '' !== $city ? ', ' : '' ) . $state;
												}
												if ( '' !== $zip ) {
													$address_line2 .= ( '' !== $city || '' !== $state ? ' ' : '' ) . $zip;
												}
												if ( '' !== $address_line2 && '0' !== $address_line2 ) :
													?>
													<div><?php echo esc_html( $address_line2 ); ?></div>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endif; ?>
								</div>
								<div class="city-guide-action">
									<a href="<?php echo esc_url( $city_guide_url ); ?>" class="btn btn-sm btn-primary" aria-label="Learn more about <?php echo esc_attr( get_the_title() ); ?>">
										Learn More <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

				<!-- Desktop Card View -->
				<div class="row g-5 d-none d-md-flex">
					<?php
					$locations->rewind_posts();
					while ( $locations->have_posts() ) :
						$locations->the_post();
						$location_id = get_the_ID();

						// Get the city guide post object.
						$city_guide = get_field( 'city_guide' );
						if ( null === $city_guide || false === $city_guide ) {
							continue;
						}

						// Get city guide URL from the linked post.
						$city_guide_url = get_permalink( $city_guide );
						if ( false === $city_guide_url || ! is_string( $city_guide_url ) ) {
							continue;
						}

						// Get image from location.
						$bg_image_id = get_field( 'background_image' );
						if ( null !== $bg_image_id && '' !== $bg_image_id && is_numeric( $bg_image_id ) ) {
							$bg_image_url = wp_get_attachment_image_url( (int) $bg_image_id, 'medium_large' );
						} elseif ( has_post_thumbnail() ) {
							$current_location_id = get_the_ID();
							if ( false !== $current_location_id ) {
								$bg_image_url = get_the_post_thumbnail_url( $current_location_id, 'medium_large' );
							} else {
								$bg_image_url = false;
							}
						} else {
							$bg_image_url = false;
						}

						// Get address from location.
						$location_map = get_field( 'location_map' );
						?>
						<div class="col-md-6 col-lg-4">
							<div class="city-guide-card">
								<?php if ( false !== $bg_image_url && '' !== $bg_image_url ) : ?>
									<img src="<?php echo esc_url( $bg_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="city-guide-card-img-top w-100" width="800" height="600" loading="lazy">
								<?php endif; ?>
								<div class="city-guide-content p-4">
									<h2 class="h4 mb-3">
										<a href="<?php echo esc_url( $city_guide_url ); ?>" class="text-decoration-none city-guide-title-link">
											<?php echo esc_html( get_the_title() ); ?>
										</a>
									</h2>

									<div class="city-guide-details mb-3">
										<?php
										if ( null !== $location_map && is_array( $location_map ) && count( $location_map ) > 0 ) :
											$street = ( isset( $location_map['street_number'] ) ? $location_map['street_number'] : '' ) . ' ' . ( isset( $location_map['street_name'] ) ? $location_map['street_name'] : '' );
											$city   = isset( $location_map['city'] ) ? $location_map['city'] : '';
											$state  = isset( $location_map['state_short'] ) ? $location_map['state_short'] : '';
											$zip    = isset( $location_map['post_code'] ) ? $location_map['post_code'] : '';
											$street = trim( $street );

											// Special handling for Brooklyn/NYC addresses.
											if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
												$city = 'Brooklyn';
											}
											?>
											<?php if ( '' !== $street || '' !== $city || '' !== $state || '' !== $zip ) : ?>
												<div class="city-guide-detail mb-2">
													<span>
														<?php if ( '' !== $street && '0' !== $street ) : ?>
															<?php echo esc_html( $street ); ?>
															<br>
														<?php endif; ?>
														<?php
														$address_line = '';
														if ( '' !== $city ) {
															$address_line .= $city;
														}
														if ( '' !== $state ) {
															$address_line .= ( '' !== $city ? ', ' : '' ) . $state;
														}
														if ( '' !== $zip ) {
															$address_line .= ( '' !== $city || '' !== $state ? ' ' : '' ) . $zip;
														}
														if ( '' !== $address_line && '0' !== $address_line ) :
															echo esc_html( $address_line );
														endif;
														?>
													</span>
												</div>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<div class="city-guide-cta-buttons">
										<a href="<?php echo esc_url( $city_guide_url ); ?>" class="btn btn-primary" aria-label="Learn more about <?php echo esc_attr( get_the_title() ); ?>">
											Learn More <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php else : ?>
				<div class="row">
					<div class="col">
						<div class="alert alert-info">
							<p class="mb-0">No city guides found. Please check back soon for updates.</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
