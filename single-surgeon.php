<?php
/**
 * Template for displaying single surgeon
 *
 * @package Mia_Aesthetics
 */

get_header();

// Extract last name from surgeon title (second word, remove comma).
$full_name  = get_the_title();
$name_parts = explode( ' ', $full_name );
$last_name  = isset( $name_parts[1] ) ? rtrim( $name_parts[1], ',' ) : $full_name;
?>
<main id="primary" tabindex="0">
<div class="surgeon-breadcrumb-wrapper">
	<div class="surgeon-fluid-container">
		<?php mia_aesthetics_breadcrumbs(); ?>
	</div>
</div>

	<!-- Professional Surgeon Header -->
	<section class="surgeon-hero">
		<div class="surgeon-fluid-container">
			<div class="row align-items-center">
				<div class="col-lg-5">
					<?php
					$headshot_id = get_field( 'surgeon_headshot' );
					if ( null !== $headshot_id && '' !== $headshot_id && is_numeric( $headshot_id ) ) :
						$headshot_url = wp_get_attachment_image_url( (int) $headshot_id, 'large' );
						if ( false !== $headshot_url ) :
							?>
						<div class="surgeon-hero-image">
							<img src="<?php echo esc_url( $headshot_url ); ?>" alt="<?php echo esc_attr( $full_name ); ?>" class="img-fluid">
							<div class="surgeon-image-accent"></div>
						</div>
						<?php endif; ?>
					<?php else : ?>
						<div class="surgeon-hero-image surgeon-placeholder">
							<div class="placeholder-content">
								<i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
							</div>
							<div class="surgeon-image-accent"></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-7">
					<div class="surgeon-hero-content">
						<div class="badge surgeon-badge">Plastic Surgeon</div>
						<h1 class="surgeon-name"><?php echo esc_html( get_the_title() ); ?></h1>
						<?php
						$location = get_field( 'surgeon_location' );
						if ( null !== $location && '' !== $location ) :
							if ( is_numeric( $location ) ) {
								$location = (int) $location;
							} elseif ( is_object( $location ) && property_exists( $location, 'ID' ) ) {
								$location = (int) $location->ID;
							}

							$location_title = get_the_title( $location );
							$location_url   = get_permalink( $location );

							// Type safety checks.
							if ( false !== $location_title && false !== $location_url ) {
								$location_title = preg_replace( '/, [A-Z]{2}$/', '', $location_title ) ?? $location_title;
								?>
						<p class="surgeon-location"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <a href="<?php echo esc_url( $location_url ); ?>"><?php echo esc_html( $location_title ); ?></a></p>
								<?php
							}
						endif;
						?>
						
						<!-- Quick Actions -->
						<div class="surgeon-hero-actions">
							<!-- Mobile Gallery Link -->
							<?php
							$doctor_slug = get_post_field( 'post_name', get_post() );
							$doctor_slug = is_string( $doctor_slug ) ? $doctor_slug : '';
							$gallery_url = '/before-after/before-after-by-doctor/?doctor=' . rawurlencode( $doctor_slug );
							?>
							<a href="<?php echo esc_url( $gallery_url ); ?>" class="btn btn-outline-primary-alt2 surgeon-gallery-mobile d-lg-none">
								<i class="fas fa-images" aria-hidden="true"></i> View Before & Afters
							</a>

							<?php
							$instagram_url = get_field( 'instagram_url' );
							if ( null !== $instagram_url && '' !== $instagram_url ) :
								?>
							<a href="<?php echo esc_url( $instagram_url ); ?>" class="btn btn-outline-primary-alt2" target="_blank" rel="noopener">
								<i class="fab fa-instagram" aria-hidden="true"></i> Follow Dr. <?php echo esc_html( $last_name ); ?>
							</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Main Content Area -->
	<div class="surgeon-content-area">
		<div class="surgeon-fluid-container">
			<div class="row">
				<!-- Main Content Column -->
				<div class="col-lg-8">
					<div class="surgeon-main-column">
						<!-- About Section -->
						<section class="surgeon-bio-section">
							<h2 class="section-title">About Dr. <?php echo esc_html( $last_name ); ?></h2>
							<div class="bio-content">
								<?php
								while ( have_posts() ) :
									the_post();
									?>
									<?php the_content(); ?>
								<?php endwhile; ?>
							</div>
						</section>

						<!-- Video Section -->
						<?php
						$video_details = get_field( 'video_details' );
						if ( null !== $video_details && is_array( $video_details ) && isset( $video_details['video_id'] ) && '' !== $video_details['video_id'] ) :
							$video_id      = $video_details['video_id'];
							$embed_url     = 'https://www.youtube.com/embed/' . $video_id;
							$thumbnail_url = '';

							if ( isset( $video_details['video_thumbnail'] ) ) {
								$video_thumbnail = $video_details['video_thumbnail'];
								if ( is_array( $video_thumbnail ) ) {
									$thumbnail_url = $video_thumbnail['url'];
								} elseif ( is_numeric( $video_thumbnail ) ) {
									$thumbnail_url = wp_get_attachment_image_url( (int) $video_thumbnail, 'full' );
								}
							}
							?>
						<section class="surgeon-video-section">
							<h2 class="section-title">Meet Dr. <?php echo esc_html( $last_name ); ?></h2>
							<div class="video-container">
								<div class="ratio ratio-16x9">
									<?php if ( '' !== $thumbnail_url ) : ?>
									<div class="video-thumbnail" data-embed-url="<?php echo esc_url( $embed_url ); ?>">
										<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="Video thumbnail" class="img-fluid">
										<button class="video-play-button" aria-label="Play video">
											<i class="fa-solid fa-play" aria-hidden="true"></i>
										</button>
									</div>
									<?php else : ?>
									<iframe 
										src="<?php echo esc_url( $embed_url ); ?>" 
										title="Dr. <?php echo esc_attr( $last_name ); ?> Video"
										frameborder="0" 
										allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
										allowfullscreen
									></iframe>
									<?php endif; ?>
								</div>
							</div>
						</section>
						<?php endif; ?>

						<!-- Specialties Section -->
						<?php
						$specialties = get_field( 'specialties' );
						if ( null !== $specialties && is_array( $specialties ) && count( $specialties ) > 0 ) :
							?>
						<section class="surgeon-expertise-section">
							<h2 class="section-title">Areas of Expertise</h2>
							<div class="expertise-grid">
								<?php foreach ( $specialties as $specialty ) : ?>
									<div class="expertise-card">
										<div class="expertise-icon">
											<i class="fas fa-check-circle" aria-hidden="true"></i>
										</div>
										<div class="expertise-content">
											<h3><?php echo esc_html( get_the_title( $specialty->ID ) ); ?></h3>
											<?php
											$excerpt = get_the_excerpt( $specialty->ID );
											if ( '' === $excerpt ) {
												$content = get_post_field( 'post_content', $specialty->ID );
												$content = is_string( $content ) ? $content : '';
												$excerpt = wp_trim_words( $content, 20, '...' );
											}
											?>
											<p><?php echo esc_html( $excerpt ); ?></p>
											<?php
											$specialty_permalink = get_permalink( $specialty->ID );
											if ( false !== $specialty_permalink ) :
												?>
											<a href="<?php echo esc_url( $specialty_permalink ); ?>" class="expertise-link">
												Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
											</a>
											<?php endif; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</section>
						<?php endif; ?>
					</div>
				</div>

				<!-- Sidebar Column -->
				<div class="col-lg-4">
					<div class="surgeon-sidebar">
						<!-- Quick Info Card -->
						<div class="sidebar-card d-none d-lg-block">
							<h3 class="sidebar-card-title">Quick Information</h3>
							<ul class="surgeon-info-list">
								<?php if ( null !== $location && '' !== $location && false !== $location_url ) : ?>
								<li>
									<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
									<span>Located at <a href="<?php echo esc_url( $location_url ); ?>"><?php echo esc_html( $location_title ); ?></a></span>
								</li>
								<?php endif; ?>
								<li>
									<i class="fas fa-images" aria-hidden="true"></i>
									<?php
									$doctor_slug = get_post_field( 'post_name', get_post() );
									$doctor_slug = is_string( $doctor_slug ) ? $doctor_slug : '';
									$gallery_url = '/before-after/before-after-by-doctor/?doctor=' . rawurlencode( $doctor_slug );
									?>
									<span><a href="<?php echo esc_url( $gallery_url ); ?>">View Before & After Gallery</a></span>
								</li>
							</ul>
						</div>

						<div class="sidebar-card">
							<h3 class="sidebar-card-title">Patient Resources</h3>
							<div class="resource-links">
								<a href="https://miaaesthetics.com/patient-resources/surgical-journey/" class="resource-link">
									<i class="fas fa-route" aria-hidden="true"></i>
									<span>Your Surgical Journey</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
								<a href="https://miaaesthetics.com/surgery-preparation/" class="resource-link">
									<i class="fas fa-clipboard-list" aria-hidden="true"></i>
									<span>Surgery Preparation</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
								<a href="https://miaaesthetics.com/financing/" class="resource-link">
									<i class="fas fa-credit-card" aria-hidden="true"></i>
									<span>Financing Options</span>
									<i class="fas fa-chevron-right" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	$faq_section = get_field( 'faq_section' );
	if ( null !== $faq_section && is_array( $faq_section ) && isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) && count( $faq_section['faqs'] ) > 0 ) :
		?>
	<section class="surgeon-faq-section">
		<div class="surgeon-fluid-container">
			<h2 class="section-title text-center">Frequently Asked Questions</h2>
			<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
