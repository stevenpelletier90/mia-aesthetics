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
<main tabindex="0">
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
					if ( $headshot_id ) :
						$headshot_url = wp_get_attachment_image_url( $headshot_id, 'large' );
						?>
						<div class="surgeon-hero-image">
							<img src="<?php echo esc_url( $headshot_url ); ?>" alt="<?php echo esc_attr( $full_name ); ?>" class="img-fluid">
							<div class="surgeon-image-accent"></div>
						</div>
					<?php else : ?>
						<div class="surgeon-hero-image surgeon-placeholder">
							<div class="placeholder-content">
								<i class="fa-solid fa-user-doctor"></i>
							</div>
							<div class="surgeon-image-accent"></div>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-7">
					<div class="surgeon-hero-content">
						<div class="surgeon-badge">Plastic Surgeon</div>
						<h1 class="surgeon-name"><?php echo esc_html( get_the_title() ); ?></h1>
						<?php
						$location = get_field( 'surgeon_location' );
						if ( $location ) :
							$location_title = get_the_title( $location );
							$location_title = preg_replace( '/, [A-Z]{2}$/', '', $location_title );
							$location_url   = get_permalink( $location );
							?>
						<p class="surgeon-location"><i class="fas fa-map-marker-alt"></i> <a href="<?php echo esc_url( $location_url ); ?>"><?php echo esc_html( $location_title ); ?></a></p>
						<?php endif; ?>
						
						<!-- Quick Actions -->
						<?php
						$instagram_url = get_field( 'instagram_url' );
						if ( $instagram_url ) :
							?>
						<div class="surgeon-hero-actions">
							<a href="<?php echo esc_url( $instagram_url ); ?>" class="mia-button" data-variant="gold" target="_blank" rel="noopener">
								<i class="fab fa-instagram"></i> Follow Dr. <?php echo esc_html( $last_name ); ?>
							</a>
						</div>
						<?php endif; ?>
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
						if ( ! empty( $video_details ) && isset( $video_details['video_id'] ) ) :
							$video_id      = $video_details['video_id'];
							$embed_url     = 'https://www.youtube.com/embed/' . $video_id;
							$thumbnail_url = '';

							if ( isset( $video_details['video_thumbnail'] ) ) {
								$video_thumbnail = $video_details['video_thumbnail'];
								if ( $video_thumbnail && is_array( $video_thumbnail ) ) {
									$thumbnail_url = $video_thumbnail['url'];
								} elseif ( $video_thumbnail && is_numeric( $video_thumbnail ) ) {
									$thumbnail_url = wp_get_attachment_image_url( $video_thumbnail, 'full' );
								}
							}
							?>
						<section class="surgeon-video-section">
							<h2 class="section-title">Meet Dr. <?php echo esc_html( $last_name ); ?></h2>
							<div class="video-container">
								<div class="ratio ratio-16x9">
									<?php if ( $thumbnail_url ) : ?>
									<div class="video-thumbnail" data-embed-url="<?php echo esc_url( $embed_url ); ?>">
										<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="Video thumbnail" class="img-fluid">
										<button class="video-play-button" aria-label="Play video">
											<i class="fa-solid fa-play"></i>
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
						if ( $specialties && ! empty( $specialties ) ) :
							?>
						<section class="surgeon-expertise-section">
							<h2 class="section-title">Areas of Expertise</h2>
							<div class="expertise-grid">
								<?php foreach ( $specialties as $specialty ) : ?>
									<div class="expertise-card">
										<div class="expertise-icon">
											<i class="fas fa-check-circle"></i>
										</div>
										<div class="expertise-content">
											<h3><?php echo esc_html( get_the_title( $specialty->ID ) ); ?></h3>
											<?php
											$excerpt = get_the_excerpt( $specialty->ID );
											if ( empty( $excerpt ) ) {
												$content = get_post_field( 'post_content', $specialty->ID );
												$excerpt = wp_trim_words( $content, 20, '...' );
											}
											?>
											<p><?php echo esc_html( $excerpt ); ?></p>
											<a href="<?php echo esc_url( get_permalink( $specialty->ID ) ); ?>" class="expertise-link">
												Learn More <i class="fa-solid fa-arrow-right"></i>
											</a>
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
						<div class="sidebar-card">
							<h3 class="sidebar-card-title">Quick Information</h3>
							<ul class="surgeon-info-list">
								<?php if ( $location ) : ?>
								<li>
									<i class="fas fa-map-marker-alt"></i>
									<span>Located at <a href="<?php echo esc_url( $location_url ); ?>"><?php echo esc_html( $location_title ); ?></a></span>
								</li>
								<?php endif; ?>
								<li>
									<i class="fas fa-images"></i>
									<span><a href="/before-after-gallery/">View Before & After Gallery</a></span>
								</li>
							</ul>
						</div>

						<div class="sidebar-card">
							<h3 class="sidebar-card-title">Patient Resources</h3>
							<div class="resource-links">
								<a href="https://miaaesthetics.com/patient-resources/surgical-journey/" class="resource-link">
									<i class="fas fa-route"></i>
									<span>Your Surgical Journey</span>
									<i class="fas fa-chevron-right"></i>
								</a>
								<a href="https://miaaesthetics.com/out-of-town-patients/" class="resource-link">
									<i class="fas fa-plane"></i>
									<span>Out of Town Patients</span>
									<i class="fas fa-chevron-right"></i>
								</a>
								<a href="https://miaaesthetics.com/financing/" class="resource-link">
									<i class="fas fa-credit-card"></i>
									<span>Financing Options</span>
									<i class="fas fa-chevron-right"></i>
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
	if ( $faq_section && ! empty( $faq_section['faqs'] ) ) :
		?>
	<section class="surgeon-faq-section">
		<div class="surgeon-fluid-container">
			<h2 class="section-title text-center">Frequently Asked Questions</h2>
			<?php echo wp_kses_post( display_page_faqs() ); ?>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php get_footer(); ?>