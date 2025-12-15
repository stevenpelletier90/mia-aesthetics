<?php
/**
 * Single location template
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<section class="location-header py-5">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<h1 class="mb-3"><?php echo esc_html( get_the_title() ); ?></h1>
					<div class="location-intro mb-4">
						<p>Welcome to our 
						<?php
						$location_title = get_the_title();
						$location_title = str_replace( 'Mia Aesthetics', '', $location_title );
						echo esc_html( trim( $location_title ) );
						?>
						location. Our state-of-the-art facility offers a wide range of plastic surgery and aesthetic procedures with a team of experienced surgeons dedicated to helping you achieve your aesthetic goals.</p>
					</div>
					<div class="location-info mb-4">
<?php
						$location_map = get_field( 'location_map' );

if ( null !== $location_map && is_array( $location_map ) ) {
	$street = ( $location_map['street_number'] ?? '' ) . ' ' . ( $location_map['street_name'] ?? '' );
	$city   = $location_map['city'] ?? '';
	$state  = $location_map['state_short'] ?? '';
	$zip    = $location_map['post_code'] ?? '';

	// Special handling for locations where Google Maps doesn't populate city correctly.
	// Check if city is empty but we have other address components.
	// For Brooklyn/NYC addresses, Google sometimes doesn't populate city.
	if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
		$city = 'Brooklyn';
	}
	?>
							<?php if ( ! in_array( trim( $street ), array( '', '0' ), true ) || '' !== $city || '' !== $state || '' !== $zip ) : ?>
							<div class="location-detail mb-4">
								<div class="d-flex flex-column">
									<?php if ( ! in_array( trim( $street ), array( '', '0' ), true ) ) : ?>
										<?php echo esc_html( trim( $street ) ); ?>
									<?php endif; ?>
									<?php
									$address_line2 = trim( $city . ', ' . $state . ' ' . $zip, ', ' );
									if ( '' !== $address_line2 && '0' !== $address_line2 ) :
										?>
										<?php echo esc_html( $address_line2 ); ?>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						<?php } ?>

						<?php $phone_number = get_field( 'phone_number' ); ?>
						<?php if ( null !== $phone_number && '' !== $phone_number ) : ?>
							<div class="location-detail mb-2">
								<div class="d-flex align-items-center">
									<i class="fas fa-phone location-icon" aria-hidden="true"></i>
									<a href="tel:<?php echo esc_attr( $phone_number ); ?>" class="location-phone">
										<?php echo esc_html( $phone_number ); ?>
									</a>
								</div>
							</div>
						<?php endif; ?>

						<?php
						// Grouped hours of operation (short format).
						$short_days = array(
							'Monday'    => 'Mon',
							'Tuesday'   => 'Tue',
							'Wednesday' => 'Wed',
							'Thursday'  => 'Thu',
							'Friday'    => 'Fri',
							'Saturday'  => 'Sat',
							'Sunday'    => 'Sun',
						);
						$hours_rows = array();
						if ( have_rows( 'business_hours' ) ) {
							while ( have_rows( 'business_hours' ) ) :
								the_row();
								$day   = get_sub_field( 'day' );
								$hours = get_sub_field( 'hours' );
								if ( null !== $day && '' !== $day && null !== $hours && '' !== $hours ) {
									$hours_rows[] = array(
										'day'   => $day,
										'hours' => $hours,
									);
								}
							endwhile;
						}
						$output = array();
						$n      = count( $hours_rows );
						$i      = 0;
						while ( $i < $n ) {
							$start         = $i;
							$current_hours = $hours_rows[ $i ]['hours'];
							while (
								$i + 1 < $n &&
								$hours_rows[ $i + 1 ]['hours'] === $current_hours
							) {
								++$i;
							}
							if ( $start === $i ) {
								$label = $short_days[ $hours_rows[ $start ]['day'] ];
							} else {
								$label = $short_days[ $hours_rows[ $start ]['day'] ] . '–' . $short_days[ $hours_rows[ $i ]['day'] ];
							}
							$output[] = $label . ' ' . $current_hours;
							++$i;
						}
						if ( array() !== $output ) :
							?>
							<div class="location-detail mb-2">
								<div class="d-flex align-items-center">
									<i class="fas fa-clock location-icon" aria-hidden="true"></i>
									<span><?php echo esc_html( implode( ' | ', $output ) ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php $location_maps_link = get_field( 'location_maps_link' ); ?>
						<?php if ( null !== $location_maps_link && '' !== $location_maps_link ) : ?>
							<div class="location-directions">
								<a href="<?php echo esc_url( $location_maps_link ); ?>" class="location-map-link" target="_blank" rel="noopener">
									<i class="fas fa-map-marker-alt location-icon" aria-hidden="true"></i> Get Directions
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-lg-6 ps-lg-5">
					<?php
					// Get video details from ACF.
					$video_info    = mia_get_video_field();
					$video_id      = $video_info['video_id'] ?? '';
					$thumbnail_url = $video_info['thumbnail'] ?? '';

					// Build YouTube embed URL from ID.
					$embed_url = '';
					if ( '' !== $video_id ) {
						$embed_url = 'https://www.youtube.com/embed/' . $video_id;
					}
					?>

					<!-- Video container - only show if we have video ID and thumbnail -->
					<?php if ( '' !== $video_id && '' !== $thumbnail_url ) : ?>
					<div class="sidebar-section border-radius-none">
						<div class="ratio ratio-16x9">
							<div class="video-thumbnail" data-embed-url="<?php echo esc_url( $embed_url ); ?>">
								<img 
									src="<?php echo esc_url( $thumbnail_url ); ?>" 
									alt="<?php echo esc_attr( get_the_title() ); ?> Video Thumbnail" 
									class="img-fluid object-fit-cover"
									loading="lazy"
									width="640"
									height="360"
								/>
								<button class="video-play-button" aria-label="Play video about <?php echo esc_attr( get_the_title() ); ?>">
									<i class="fa-solid fa-play" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<article class="py-5">
		<div class="container">
			<div class="row gx-5">
				<div class="col-md-7">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="location-content">
							<?php the_content(); ?>
						</div>
					<?php endwhile; ?>
				</div>

				<aside class="col-md-5" role="complementary" aria-label="Related resources">
					<div class="sidebar-card">
						<h3 class="sidebar-card-title">Patient Resources</h3>
						<div class="resource-links">
							<?php
							$city_guide = get_field( 'city_guide' );
							if ( null !== $city_guide && false !== $city_guide ) :
								$city_guide_url = get_permalink( $city_guide );
								$city_guide_url = is_string( $city_guide_url ) ? $city_guide_url : '';
								?>
							<a href="<?php echo esc_url( $city_guide_url ); ?>" class="resource-link">
								<i class="fas fa-map-marked-alt" aria-hidden="true"></i>
								<span>Explore <?php echo esc_html( str_replace( 'Mia Aesthetics', '', get_the_title() ) ); ?></span>
								<i class="fas fa-chevron-right" aria-hidden="true"></i>
							</a>
							<?php endif; ?>
							<a href="/patient-resources/surgical-journey/" class="resource-link">
								<i class="fas fa-route" aria-hidden="true"></i>
								<span>Your Surgical Journey</span>
								<i class="fas fa-chevron-right" aria-hidden="true"></i>
							</a>
							<a href="/surgery-preparation/" class="resource-link">
								<i class="fas fa-clipboard-list" aria-hidden="true"></i>
								<span>Surgery Preparation</span>
								<i class="fas fa-chevron-right" aria-hidden="true"></i>
							</a>
							<a href="/financing/" class="resource-link">
								<i class="fas fa-credit-card" aria-hidden="true"></i>
								<span>Financing Options</span>
								<i class="fas fa-chevron-right" aria-hidden="true"></i>
							</a>
							<a href="/cosmetic-plastic-surgery/" class="resource-link">
								<i class="fas fa-stethoscope" aria-hidden="true"></i>
								<span>Our Procedures</span>
								<i class="fas fa-chevron-right" aria-hidden="true"></i>
							</a>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</article>

	<!-- Team Section -->
	<section class="team-section py-5">
		<div class="container">
			<h2 class="section-title text-center mb-5">Our <?php echo esc_html( get_the_title() ); ?> Team</h2>
			<div class="row g-4 justify-content-center">
				<?php
				$args = array(
					'post_type'              => 'surgeon',
					'posts_per_page'         => -1,
					'orderby'                => 'menu_order',
					'order'                  => 'ASC',
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query'             => array(
						array(
							'key'     => 'surgeon_location',
							'value'   => (int) get_the_ID(),
							'compare' => '=',
						),
					),
					// Performance optimizations.
					'update_post_meta_cache' => true,  // We read meta (surgeon_headshot) in the loop.
					'update_post_term_cache' => false, // We don't render terms in the loop.
					'no_found_rows'          => true,  // Skip count query.
				);
				$surgeons = new WP_Query( $args );

				if ( $surgeons->have_posts() ) :
					while ( $surgeons->have_posts() ) :
						$surgeons->the_post();
						?>
						
						<div class="col-12 col-md-6 col-lg-3">
							<div class="surgeon-card text-center">
								<?php
								$surgeon_headshot_id = get_field( 'surgeon_headshot' );
								if ( null !== $surgeon_headshot_id && is_numeric( $surgeon_headshot_id ) ) :
									$headshot_url = wp_get_attachment_image_url( (int) $surgeon_headshot_id, 'medium' );
									if ( false !== $headshot_url ) :
										?>
									<img src="<?php echo esc_url( $headshot_url ); ?>"
										alt="<?php the_title(); ?> Headshot" />
									<?php endif; ?>
								<?php elseif ( has_post_thumbnail() ) : ?>
									<?php
									$thumbnail_url = get_the_post_thumbnail_url();
									if ( false !== $thumbnail_url ) :
										?>
									<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title(); ?>" />
									<?php endif; ?>
								<?php endif; ?>
								
								<div class="surgeon-info">
									<h3><?php the_title(); ?></h3>
									<p>Plastic Surgeon</p>
									<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
										View Profile <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
		</div>
	</section>

	<section class="py-5">
		<div class="container">            
			<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>           
		</div>
	</section>

<?php get_footer(); ?>
