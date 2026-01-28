<?php
/**
 * Single location template
 *
 * @package Mia_Aesthetics
 */

get_header();
$mia_location_hero_id = get_post_thumbnail_id();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();

		// Get location data for info bar.
		$location_map = get_field( 'location_map' );

		$street = '';
		$city   = '';
		$state  = '';
		$zip    = '';

		if ( null !== $location_map && is_array( $location_map ) ) {
			$street = ( $location_map['street_number'] ?? '' ) . ' ' . ( $location_map['street_name'] ?? '' );
			$city   = $location_map['city'] ?? '';
			$state  = $location_map['state_short'] ?? '';
			$zip    = $location_map['post_code'] ?? '';

			// Special handling for Brooklyn/NYC addresses.
			if ( '' === $city && '' !== $state && ( 'NY' === $state && false !== stripos( $street, 'atlantic' ) ) ) {
				$city = 'Brooklyn';
			}
		}

		$phone_number       = get_field( 'phone_number' );
		$location_maps_link = get_field( 'location_maps_link' );

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
		$hours_output = array();
		$n            = count( $hours_rows );
		$i            = 0;
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
			$hours_output[] = $label . ' ' . $current_hours;
			++$i;
		}

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

		<!-- Hero Section with Background Image -->
		<section class="location-hero position-relative overflow-hidden" aria-labelledby="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>">
			<?php if ( false !== $mia_location_hero_id && 0 !== $mia_location_hero_id ) : ?>
				<picture class="hero-picture">
					<?php
					$hero_mobile_url  = wp_get_attachment_image_url( $mia_location_hero_id, 'hero-mobile' );
					$hero_tablet_url  = wp_get_attachment_image_url( $mia_location_hero_id, 'hero-tablet' );
					$hero_desktop_url = wp_get_attachment_image_url( $mia_location_hero_id, 'hero-desktop' );
					?>
					<?php if ( false !== $hero_mobile_url ) : ?>
					<source media="(max-width: 640px)" srcset="<?php echo esc_url( $hero_mobile_url ); ?>">
					<?php endif; ?>
					<?php if ( false !== $hero_tablet_url ) : ?>
					<source media="(max-width: 1024px)" srcset="<?php echo esc_url( $hero_tablet_url ); ?>">
					<?php endif; ?>
					<?php if ( false !== $hero_desktop_url ) : ?>
					<img src="<?php echo esc_url( $hero_desktop_url ); ?>"
						alt="<?php echo esc_attr( get_the_title() ); ?> location background"
						class="hero-bg"
						loading="eager"
						fetchpriority="high">
					<?php endif; ?>
				</picture>
			<?php endif; ?>

			<div class="hero-content">
				<div class="hero-inner">
					<!-- Left: Title + CTA + Video -->
					<div class="hero-left">
						<div class="hero-text">
							<h1 id="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>"><?php the_title(); ?></h1>
							<h2 class="location-cta">Book Your Surgery Today!</h2>
						</div>

						<!-- Video thumbnail -->
						<?php if ( '' !== $video_id && '' !== $thumbnail_url ) : ?>
						<div class="location-video">
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

					<!-- Right: Consultation Form -->
					<div class="hero-form">
						<?php get_template_part( 'components/consultation-form' ); ?>
					</div>
				</div>
			</div>
		</section>

		<!-- Location Info Bar -->
		<section class="location-info-bar">
			<div class="container">
				<div class="info-items">
					<?php
					$address_line1 = trim( $street );
					$address_line2 = trim( $city . ', ' . $state . ' ' . $zip, ', ' );
					if ( ! in_array( $address_line1, array( '', '0' ), true ) || ( '' !== $address_line2 && '0' !== $address_line2 ) ) :
						?>
					<div class="info-item">
						<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
						<span>
							<?php
							if ( ! in_array( $address_line1, array( '', '0' ), true ) ) {
								echo esc_html( $address_line1 );
								if ( '' !== $address_line2 && '0' !== $address_line2 ) {
									echo ', ';
								}
							}
							if ( '' !== $address_line2 && '0' !== $address_line2 ) {
								echo esc_html( $address_line2 );
							}
							?>
						</span>
					</div>
					<?php endif; ?>

					<?php if ( null !== $phone_number && '' !== $phone_number ) : ?>
					<div class="info-item">
						<i class="fas fa-phone" aria-hidden="true"></i>
						<a href="tel:<?php echo esc_attr( $phone_number ); ?>">
							<?php echo esc_html( $phone_number ); ?>
						</a>
					</div>
					<?php endif; ?>

					<?php if ( array() !== $hours_output ) : ?>
					<div class="info-item info-item-hours">
						<i class="fas fa-clock" aria-hidden="true"></i>
						<span><?php echo esc_html( implode( ' | ', $hours_output ) ); ?></span>
					</div>
					<?php endif; ?>

					<?php if ( null !== $location_maps_link && '' !== $location_maps_link ) : ?>
					<div class="info-item">
						<i class="fas fa-directions" aria-hidden="true"></i>
						<a href="<?php echo esc_url( $location_maps_link ); ?>" target="_blank" rel="noopener">
							Get Directions
						</a>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<article class="location-article">
			<div class="container-xl">
				<div class="row gx-5">
					<div class="col-12 location-main">
						<div class="location-content">
							<?php the_content(); ?>
						</div>
					</div>

					<aside class="col-12 location-sidebar" role="complementary" aria-label="Related resources">
						<div class="sidebar-card patient-resources-card">
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
								<a href="<?php echo esc_url( home_url( '/surgical-journey/' ) ); ?>" class="resource-link">
									<i class="fas fa-route" aria-hidden="true"></i>
									<span>Your Surgical Journey</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
								<a href="<?php echo esc_url( home_url( '/surgery-preparation/' ) ); ?>" class="resource-link">
									<i class="fas fa-clipboard-list" aria-hidden="true"></i>
									<span>Surgery Preparation</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
								<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="resource-link">
									<i class="fas fa-credit-card" aria-hidden="true"></i>
									<span>Financing Options</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
								<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/' ) ); ?>" class="resource-link">
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

	<?php endwhile; ?>

	<!-- Team Section -->
	<section class="team-section">
		<div class="container">
			<h2 class="section-title text-center">Our <?php echo esc_html( get_the_title() ); ?> Team</h2>
			<div class="row g-4 surgeon-list">
				<?php
				$surgeons = mia_get_surgeons_by_location( (int) get_the_ID() );

				// Determine column classes based on surgeon count.
				$surgeon_count = $surgeons->post_count;
				if ( 1 === $surgeon_count ) {
					$col_classes = 'col-12 col-md-6 col-lg-4';
				} elseif ( 2 === $surgeon_count ) {
					$col_classes = 'col-12 col-md-6';
				} elseif ( 3 === $surgeon_count ) {
					$col_classes = 'col-12 col-md-4';
				} elseif ( 4 === $surgeon_count ) {
					$col_classes = 'col-12 col-sm-6 col-xl-3';
				} else {
					$col_classes = 'col-12 col-sm-6 col-md-4 col-xl-3';
				}

				if ( $surgeons->have_posts() ) :
					while ( $surgeons->have_posts() ) :
						$surgeons->the_post();
						?>

						<div class="<?php echo esc_attr( $col_classes ); ?>">
							<div class="surgeon-item">
								<?php
								$surgeon_headshot_id = get_field( 'surgeon_headshot' );
								if ( null !== $surgeon_headshot_id && is_numeric( $surgeon_headshot_id ) ) :
									$headshot_url = wp_get_attachment_image_url( (int) $surgeon_headshot_id, 'medium' );
									if ( false !== $headshot_url ) :
										?>
									<img src="<?php echo esc_url( $headshot_url ); ?>"
										alt="<?php the_title(); ?> Headshot"
										width="300"
										height="300"
										loading="lazy" />
									<?php endif; ?>
								<?php elseif ( has_post_thumbnail() ) : ?>
									<?php
									$thumbnail_url = get_the_post_thumbnail_url();
									if ( false !== $thumbnail_url ) :
										?>
									<img src="<?php echo esc_url( $thumbnail_url ); ?>"
										alt="<?php the_title(); ?>"
										width="300"
										height="300"
										loading="lazy" />
									<?php endif; ?>
								<?php endif; ?>

								<div class="surgeon-info">
									<h3><?php the_title(); ?></h3>
									<p>Plastic Surgeon</p>
									<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm" aria-label="View <?php echo esc_attr( get_the_title() ); ?>'s profile">
										View Profile
									</a>
								</div>
							</div>
							<hr class="surgeon-divider" role="separator" aria-hidden="true">
						</div>

						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>
		</div>
	</section>

	<?php
	$faq_output = mia_aesthetics_display_faqs();
	if ( '' !== $faq_output ) :
		?>
	<section class="location-faq-section">
		<div class="container">
			<?php echo wp_kses_post( $faq_output ); ?>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
