<?php
/**
 * Location Archive Template
 * Displays all locations in alphabetical order with CTA sections
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<!-- Archive Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="mb-2">Our Locations</h1>
					<p class="lead mb-0">Find a Mia Aesthetics location near you and schedule your consultation today.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Archive Content -->
	<section class="location-archive-section py-5">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<!-- Mobile List View -->
				<div class="location-mobile-list d-md-none">
					<?php
					while ( have_posts() ) :
						the_post();
						// Get location data.
						$phone_number = get_field( 'phone_number' );
						$location_map = get_field( 'location_map' );
						?>
						<div class="location-list-item">
							<div class="d-flex justify-content-between align-items-start">
								<div class="location-info flex-grow-1">
									<h3 class="h5 mb-2">
										<?php
										$location_permalink = get_permalink();
										if ( false !== $location_permalink ) :
											?>
										<a href="<?php echo esc_url( $location_permalink ); ?>" class="text-decoration-none">
											<?php echo esc_html( get_the_title() ); ?>
										</a>
										<?php else : ?>
											<?php echo esc_html( get_the_title() ); ?>
										<?php endif; ?>
									</h3>
									<?php
									if ( null !== $location_map && is_array( $location_map ) && count( $location_map ) > 0 ) :
										$street = ( isset( $location_map['street_number'] ) ? $location_map['street_number'] : '' ) . ' ' . ( isset( $location_map['street_name'] ) ? $location_map['street_name'] : '' );
										$city   = isset( $location_map['city'] ) ? $location_map['city'] : '';
										$state  = isset( $location_map['state_short'] ) ? $location_map['state_short'] : '';
										$zip    = isset( $location_map['post_code'] ) ? $location_map['post_code'] : '';
										$street = trim( $street ); // Clean up any extra spaces.
										// Special handling for locations where Google Maps doesn't populate city correctly.
										// For Brooklyn/NYC addresses, Google sometimes doesn't populate city.
										if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
											$city = 'Brooklyn';
										}
										?>
										<?php if ( '' !== $street || '' !== $city || '' !== $state || '' !== $zip ) : ?>
											<div class="text-muted mb-1">
												<?php if ( '' !== $street && '0' !== $street ) : ?>
													<div><?php echo esc_html( $street ); ?></div>
												<?php endif; ?>
												<?php
												$address_line2 = '';
												if ( '' !== $city ) {
													$address_line2 .= $city;
												}
												if ( '' !== $state ) {
													$address_line2 .= ( '' !== $city ? ', ' : '' )
														. $state;
												}
												if ( '' !== $zip ) {
													$address_line2 .= ( '' !== $city || '' !== $state ? ' ' : '' )
														. $zip;
												}
												if ( '' !== $address_line2 && '0' !== $address_line2 ) :
													?>
													<div><?php echo esc_html( $address_line2 ); ?></div>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									<?php endif; ?>
									<?php if ( '' !== $phone_number ) : ?>
										<p class="mb-0">
											<a href="tel:<?php echo esc_attr( $phone_number ); ?>" class="location-phone text-decoration-none">
												<i class="fas fa-phone me-1" aria-hidden="true"></i><span class="phone-number"><?php echo esc_html( $phone_number ); ?></span>
											</a>
										</p>
									<?php endif; ?>
								</div>
								<div class="location-action">
									<?php
									$location_view_permalink = get_permalink();
									if ( false !== $location_view_permalink ) :
										?>
									<a href="<?php echo esc_url( $location_view_permalink ); ?>" class="btn btn-sm btn-primary">
										View <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
									</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>

				<!-- Desktop Card View -->
				<div class="row g-5 d-none d-md-flex">
					<?php rewind_posts(); ?>
					<?php
					while ( have_posts() ) :
						the_post();
							// Get location data.
							$phone_number = get_field( 'phone_number' );
						?>
<div class="col-md-6 col-lg-4">
						<?php
						$bg_image_id = get_field( 'background_image' );
						if ( null !== $bg_image_id && '' !== $bg_image_id && is_numeric( $bg_image_id ) ) {
							$bg_image_url = wp_get_attachment_image_url( (int) $bg_image_id, 'medium_large' );
						} elseif ( has_post_thumbnail() ) {
							$location_post_id = get_the_ID();
							if ( false !== $location_post_id ) {
								$bg_image_url = get_the_post_thumbnail_url( $location_post_id, 'medium_large' );
							} else {
								$bg_image_url = false;
							}
						} else {
							$bg_image_url = false;
						}
						?>
	<div class="location-card">
						<?php if ( false !== $bg_image_url && '' !== $bg_image_url ) : ?>
			<img src="<?php echo esc_url( $bg_image_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="location-card-img-top w-100">
		<?php endif; ?>
		<div class="location-content p-4">
			<h2 class="h4 mb-3">
				<a href="<?php the_permalink(); ?>" class="text-decoration-none location-title-link">
						<?php echo esc_html( get_the_title() ); ?>
				</a>
			</h2>

			<div class="location-details mb-3">
						<?php
						$location_map = get_field( 'location_map' );
						if ( null !== $location_map && is_array( $location_map ) && count( $location_map ) > 0 ) :
							$street = ( isset( $location_map['street_number'] ) ? $location_map['street_number'] : '' ) . ' ' . ( isset( $location_map['street_name'] ) ? $location_map['street_name'] : '' );
							$city   = isset( $location_map['city'] ) ? $location_map['city'] : '';
							$state  = isset( $location_map['state_short'] ) ? $location_map['state_short'] : '';
							$zip    = isset( $location_map['post_code'] ) ? $location_map['post_code'] : '';
							$street = trim( $street ); // Clean up any extra spaces.
							// Special handling for locations where Google Maps doesn't populate city correctly.
							// For Brooklyn/NYC addresses, Google sometimes doesn't populate city.
							if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
								$city = 'Brooklyn';
							}
							?>
							<?php if ( '' !== $street || '' !== $city || '' !== $state || '' !== $zip ) : ?>
					<div class="location-detail mb-2">
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

						<?php if ( '' !== $phone_number ) : ?>
					<div class="location-detail mb-2">
						<div class="d-flex align-items-center">
							<i class="fas fa-phone me-2 location-icon" aria-hidden="true"></i>
							<a href="tel:<?php echo esc_attr( $phone_number ); ?>" class="location-phone text-decoration-none">
								<?php echo esc_html( $phone_number ); ?>
							</a>
						</div>
					</div>
				<?php endif; ?>
			</div>
			<div class="location-cta-buttons">
				<a href="<?php the_permalink(); ?>" class="btn btn-primary">
					View Location <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</div>
</div>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="row">
					<div class="col">
						<div class="alert alert-info">
							<p class="mb-0">No locations found. Please check back soon for updates.</p>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
