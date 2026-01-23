<?php
/**
 * The front page template file
 *
 * @package Mia_Aesthetics
 */

get_header();

// Get hero banner data from ACF options.
$hero_mobile_id     = get_field( 'hero_banner_mobile', 'option' );
$hero_desktop_id    = get_field( 'hero_banner_desktop', 'option' );
$hero_widescreen_id = get_field( 'hero_banner_widescreen', 'option' );
$hero_alt           = get_field( 'hero_banner_alt', 'option' );
$hero_link          = get_field( 'hero_banner_link', 'option' );
$hero_cta_text      = get_field( 'hero_cta_text', 'option' );

// Validate and cast to integers.
$hero_mobile_id     = is_numeric( $hero_mobile_id ) ? (int) $hero_mobile_id : 0;
$hero_desktop_id    = is_numeric( $hero_desktop_id ) ? (int) $hero_desktop_id : 0;
$hero_widescreen_id = is_numeric( $hero_widescreen_id ) ? (int) $hero_widescreen_id : $hero_desktop_id;

// Get image URLs for picture element sources (with fallback sizes).
// For mobile, use 'full' as src with srcset for responsive delivery to smaller screens.
$mobile_url    = '';
$mobile_srcset = '';
if ( $hero_mobile_id > 0 ) {
	$mobile_url    = wp_get_attachment_image_url( $hero_mobile_id, 'full' );
	$mobile_srcset = wp_get_attachment_image_srcset( $hero_mobile_id, 'full' );
}
$mobile_url    = false !== $mobile_url ? $mobile_url : '';
$mobile_srcset = is_string( $mobile_srcset ) ? $mobile_srcset : '';

$desktop_url = '';
if ( $hero_desktop_id > 0 ) {
	$desktop_url = wp_get_attachment_image_url( $hero_desktop_id, 'hero-desktop' );
	if ( false === $desktop_url ) {
		$desktop_url = wp_get_attachment_image_url( $hero_desktop_id, 'large' );
	}
	if ( false === $desktop_url ) {
		$desktop_url = wp_get_attachment_image_url( $hero_desktop_id, 'full' );
	}
}
$desktop_url = false !== $desktop_url ? $desktop_url : '';

$widescreen_url = $hero_widescreen_id > 0 ? wp_get_attachment_image_url( $hero_widescreen_id, 'full' ) : '';

// Get srcsets for responsive/WebP delivery.
$desktop_srcset    = $hero_desktop_id > 0 ? wp_get_attachment_image_srcset( $hero_desktop_id, 'hero-desktop' ) : '';
$widescreen_srcset = $hero_widescreen_id > 0 ? wp_get_attachment_image_srcset( $hero_widescreen_id, 'full' ) : '';

// Sanitize text fields.
$hero_alt      = is_string( $hero_alt ) && '' !== $hero_alt ? $hero_alt : 'Mia Aesthetics Special Offer';
$hero_link     = is_string( $hero_link ) && '' !== $hero_link ? $hero_link : home_url( '/' );
$hero_cta_text = is_string( $hero_cta_text ) && '' !== $hero_cta_text ? $hero_cta_text : 'Learn More';

// Ensure srcsets are strings for output.
$desktop_srcset    = is_string( $desktop_srcset ) ? $desktop_srcset : '';
$widescreen_srcset = is_string( $widescreen_srcset ) ? $widescreen_srcset : '';

// Check if widescreen is a separate image from desktop.
$has_separate_widescreen = $hero_widescreen_id !== $hero_desktop_id && '' !== $widescreen_srcset;
?>
<main id="primary" class="site-main">

<!-- Hero Section -->
<section class="hero-section">
	<div class="hero-container">
	<!-- Hero Banner (Responsive) -->
	<div class="hero-carousel">
		<a href="<?php echo esc_url( $hero_link ); ?>" class="hero-banner hero-banner-link" aria-label="<?php echo esc_attr( $hero_alt ); ?>">
			<?php if ( $hero_mobile_id > 0 && $hero_desktop_id > 0 ) : ?>
			<picture>
				<?php if ( $has_separate_widescreen ) : ?>
				<!-- Widescreen: 1921px+ (separate design for ultrawide) -->
				<source media="(min-width: 1921px)"
					srcset="<?php echo esc_attr( $widescreen_srcset ); ?>"
					sizes="100vw">
				<?php endif; ?>
				<!-- Desktop: 768px to 1920px -->
				<?php if ( '' !== $desktop_srcset ) : ?>
				<source media="(min-width: 768px)"
					srcset="<?php echo esc_attr( $desktop_srcset ); ?>"
					sizes="100vw">
				<?php elseif ( '' !== $desktop_url ) : ?>
				<source media="(min-width: 768px)"
					srcset="<?php echo esc_url( $desktop_url ); ?>">
				<?php endif; ?>
				<!-- Mobile fallback: up to 767px -->
				<img src="<?php echo esc_url( $mobile_url ); ?>"
					<?php if ( '' !== $mobile_srcset ) : ?>
					srcset="<?php echo esc_attr( $mobile_srcset ); ?>"
					sizes="100vw"
					<?php endif; ?>
					class="d-block w-100"
					alt="<?php echo esc_attr( $hero_alt ); ?>"
					width="800"
					height="600"
					fetchpriority="high"
					data-no-lazy="1">
			</picture>
			<?php endif; ?>
		</a>
	</div>

	<!-- Hero Content Boxes -->
	<?php
	// Get hero box images from ACF options (no expensive DB lookups).
	$ba_img_id  = get_field( 'hero_ba_image', 'option' );
	$fin_img_id = get_field( 'hero_financing_image', 'option' );
	$ba_img_id  = is_numeric( $ba_img_id ) ? (int) $ba_img_id : 0;
	$fin_img_id = is_numeric( $fin_img_id ) ? (int) $fin_img_id : 0;
	?>
	<div class="hero-boxes">
		<!-- Before & After Box -->
		<a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" class="hero-box hero-box-top hero-box-link">
		<?php
		if ( $ba_img_id > 0 ) {
			echo wp_get_attachment_image(
				$ba_img_id,
				'medium_large',
				false,
				array(
					'class'   => 'hero-box-image',
					'alt'     => 'Before & After Gallery Results - Mia Aesthetics',
					'sizes'   => '(max-width: 767px) 100vw, 412px',
					'loading' => 'lazy',
				)
			);
		}
		?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Before & After Gallery</div>
			<span class="btn btn-outline-primary-alt2" role="button">
			View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</span>
		</div>
		</a>

		<!-- Financing Box -->
		<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="hero-box hero-box-bottom hero-box-link">
		<?php
		if ( $fin_img_id > 0 ) {
			echo wp_get_attachment_image(
				$fin_img_id,
				'medium_large',
				false,
				array(
					'class'   => 'hero-box-image',
					'alt'     => 'Affordable Financing at Mia Aesthetics',
					'sizes'   => '(max-width: 767px) 100vw, 412px',
					'loading' => 'lazy',
				)
			);
		}
		?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Affordable Financing</div>
			<span class="btn btn-outline-primary-alt2" role="button">
			Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</span>
		</div>
		</a>
	</div>
	</div>
</section>

<!-- About Section - Centered Layout -->
<section class="about-section">
	<div class="container">
	<!-- Centered Content -->
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-8">
		<div class="about-content text-center">
			<h1 class="page-heading">Why Mia Aesthetics?</h1>
		</div>
		</div>
	</div>

	<!-- Custom Statistics Grid -->
	<div class="stats-grid">
		<!-- Founded Year -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="2017">0</div>
		<div class="homepage-stat-label">Year Founded</div>
		</div>

		<!-- Expert Surgeons (Dynamic) -->
		<a href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>" class="stat-box stat-box-link text-center">
		<div class="homepage-stat-number" data-count="
		<?php
			$stats = mia_aesthetics_get_site_stats();
			echo esc_html( (string) $stats['surgeons'] );
		?>
		">0</div>
		<div class="homepage-stat-label">Expert Surgeons</div>
		</a>

		<!-- Clinic Locations (Dynamic) -->
		<a href="<?php echo esc_url( home_url( '/locations/' ) ); ?>" class="stat-box stat-box-link text-center">
		<div class="homepage-stat-number" data-count="<?php echo esc_attr( (string) $stats['locations'] ); ?>">0</div>
		<div class="homepage-stat-label">Clinic Locations</div>
		</a>

		<!-- Satisfied Patients -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="150" data-suffix="k+">0</div>
		<div class="homepage-stat-label">Satisfied Patients</div>
		</div>
	</div>

	<!-- Section Description -->
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-8">
		<div class="about-content text-center">
			<p class="about-description mb-3 mt-4">Our mission at Mia Aesthetics is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
		</div>
		</div>
	</div>
	</div>
</section>

<!-- Meet Our Surgeons Section -->
<section class="surgeons-section">
	<div class="container">
		<div class="row mb-4 text-center">
			<div class="col-12">
				<p class="surgeons-tagline mb-3">Our Team</p>
				<h2 class="section-heading">Meet Our Surgeons</h2>				
			</div>
		</div>

		<div class="surgeons-carousel-wrapper">
			<button class="carousel-nav carousel-prev" aria-label="Previous surgeons">
				<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
			</button>

			<div class="surgeons-carousel">
				<?php
				$surgeons_query = new WP_Query(
					array(
						'post_type'      => 'surgeon',
						'posts_per_page' => -1,
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
					)
				);

				if ( $surgeons_query->have_posts() ) :
					while ( $surgeons_query->have_posts() ) :
						$surgeons_query->the_post();
						$surgeon_permalink = get_permalink();
						$location          = get_field( 'surgeon_location' );
						$location_title    = '';
						$location_id       = 0;

						if ( null !== $location && is_object( $location ) && property_exists( $location, 'ID' ) ) {
							$location_id    = (int) $location->ID;
							$location_title = $location->post_title;
						}

						$headshot_id = get_field( 'surgeon_headshot' );
						?>
						<div class="surgeon-slide">
							<div class="homepage-surgeon-card">
								<div class="homepage-surgeon-image">
									<?php
									if ( null !== $headshot_id && '' !== $headshot_id && is_numeric( $headshot_id ) ) :
										echo wp_get_attachment_image(
											(int) $headshot_id,
											'medium',
											false,
											array(
												'class' => 'homepage-surgeon-headshot',
												'alt'   => get_the_title() . ' Headshot',
											)
										);
									elseif ( has_post_thumbnail() ) :
										the_post_thumbnail( 'medium', array( 'class' => 'homepage-surgeon-headshot' ) );
									endif;
									?>
								</div>
								<div class="homepage-surgeon-info">
									<h3 class="homepage-surgeon-name">
										<?php if ( false !== $surgeon_permalink ) : ?>
											<a href="<?php echo esc_url( $surgeon_permalink ); ?>"><?php the_title(); ?></a>
										<?php else : ?>
											<?php the_title(); ?>
										<?php endif; ?>
									</h3>
									<?php if ( '' !== $location_title && 0 !== $location_id ) : ?>
										<p class="homepage-surgeon-location">
											<i class="fa-solid fa-location-dot" aria-hidden="true"></i>
											<?php
											$location_permalink = get_permalink( $location_id );
											if ( false !== $location_permalink ) :
												?>
												<a href="<?php echo esc_url( $location_permalink ); ?>"><?php echo esc_html( $location_title ); ?></a>
											<?php else : ?>
												<?php echo esc_html( $location_title ); ?>
											<?php endif; ?>
										</p>
									<?php endif; ?>
									<?php if ( false !== $surgeon_permalink ) : ?>
										<a href="<?php echo esc_url( $surgeon_permalink ); ?>" class="homepage-surgeon-bio-link">
											View Bio <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			</div>

			<button class="carousel-nav carousel-next" aria-label="Next surgeons">
				<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
			</button>

			<div class="carousel-counter" aria-live="polite" aria-atomic="true">
				<span class="carousel-current">1</span>/<span class="carousel-total"><?php echo esc_html( (string) $surgeons_query->post_count ); ?></span>
			</div>
		</div>
	</div>
</section>


<!-- Video Background Section -->
<section class="video-background-section position-relative overflow-hidden">
	<!-- Video Container with 16:9 aspect ratio -->
	<div class="video-container ratio ratio-16x9">
	<video
		autoplay
		muted
		loop
		playsinline
		class="video-bg">
		<source src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/home-video.mp4' ) ); ?>" type="video/mp4">
		Your browser does not support the video tag.
	</video>
	</div>

	<!-- Content Overlay -->
	<div class="video-content-overlay position-absolute top-0 start-0 w-100 h-100 d-none d-md-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
		<div class="col-lg-8 text-center">
			<h2 class="text-white video-heading mb-3">Experience Excellence</h2>
			<p class="video-description text-white mb-4">Join over 150,000 satisfied patients who have transformed their lives with our board-certified plastic surgeons. From your first consultation to your final results, we are committed to delivering exceptional care and life-changing outcomes.</p>
			<a href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" class="btn btn-primary btn-lg" role="button">Free Virtual Consultation</a>
		</div>
		</div>
	</div>
	</div>
</section>

<!-- Procedures Tabbed Section -->
<section class="procedures-section">
	<div class="container">
	<div class="row mb-5 text-center">
		<div class="col-12">
		<h2 class="section-heading">Our Procedures</h2>
		<p class="section-description">Discover the perfect procedure for your aesthetic goals</p>
		</div>
	</div>

	<!-- Procedure Navigation Tabs -->
	<div class="row">
		<div class="col-12">
		<!-- Desktop: tabs with arrows (≥768px) -->
		<div class="procedure-tabs-shell d-none d-md-flex align-items-center mb-4">
			<!-- Arrow – Previous -->
			<button class="procedure-nav-arrow prev-arrow me-2" type="button" aria-label="Previous procedure category">
			<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
			</button>

			<!-- Scrollable tab strip -->
			<ul class="nav nav-tabs procedure-tabs flex-nowrap overflow-auto" id="procedureTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-content" type="button" role="tab" aria-controls="body-content" aria-selected="true">
				Body
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="breast-tab" data-bs-toggle="tab" data-bs-target="#breast-content" type="button" role="tab" aria-controls="breast-content" aria-selected="false">
				Breast
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="face-tab" data-bs-toggle="tab" data-bs-target="#face-content" type="button" role="tab" aria-controls="face-content" aria-selected="false">
				Face
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="nonsurgical-tab" data-bs-toggle="tab" data-bs-target="#nonsurgical-content" type="button" role="tab" aria-controls="nonsurgical-content" aria-selected="false">
				Non-Surgical
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="men-tab" data-bs-toggle="tab" data-bs-target="#men-content" type="button" role="tab" aria-controls="men-content" aria-selected="false">
				Men
				</button>
			</li>
			</ul>

			<!-- Arrow – Next -->
			<button class="procedure-nav-arrow next-arrow ms-2" type="button" aria-label="Next procedure category">
			<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
			</button>
		</div>

		<!-- Mobile: simple select (<768px) -->
		<select id="procedureDropdown" class="form-select d-block d-md-none mt-3" aria-label="Select a procedure category">
			<option value="body-content" selected>Body</option>
			<option value="breast-content">Breast</option>
			<option value="face-content">Face</option>
			<option value="nonsurgical-content">Non-Surgical</option>
			<option value="men-content">Men</option>
		</select>
		</div>
	</div>

	<!-- Procedure Content -->
	<div class="tab-content mt-4" id="procedureTabsContent">
		<!-- Body Content -->
		<div class="tab-pane fade show active" id="body-content" role="tabpanel" aria-labelledby="body-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$body_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/body-home.jpg' ) );
			if ( 0 !== $body_img_id ) {
				echo wp_get_attachment_image(
					$body_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Body Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/body-home.jpg' ) ) . '" alt="Body Procedures" class="img-fluid rounded shadow" width="800" height="600">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline mt-3">Sculpt Your Ideal Figure</p>
			<h3 class="section-heading">Body Contouring</h3>
			<p class="section-description ">Our body contouring procedures help you achieve the silhouette you desire. Whether you're looking to remove excess fat, tighten loose skin, or enhance your curves, our specialists can help you reach your aesthetic goals.</p>

			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/awake-liposuction/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Awake Lipo
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brazilian Butt Lift (BBL)
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/lipo-360/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Lipo 360
					</a>
				</div>
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/mommy-makeover/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mommy Makeover
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/tummy-tuck/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Tummy Tuck
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/arm-lift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Arm Lift
					</a>
				</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/' ) ); ?>" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Body Procedures
				</a>
			</div>
			</div>
		</div>
		</div>

		<!-- Breast Content -->
		<div class="tab-pane fade" id="breast-content" role="tabpanel" aria-labelledby="breast-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$breast_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/breast-home.jpg' ) );
			if ( 0 !== $breast_img_id ) {
				echo wp_get_attachment_image(
					$breast_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Breast Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/breast-home.jpg' ) ) . '" alt="Breast Procedures" class="img-fluid rounded shadow" width="800" height="600">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline mt-3">Achieve Your Desired Look</p>
			<h3 class="section-heading">Breast Enhancement</h3>
			<p class="section-description ">Our breast procedures are designed to enhance, reduce, or lift your breasts to achieve your desired appearance. Our board-certified surgeons use the latest techniques to deliver natural-looking results with minimal scarring.</p>

			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/augmentation-implants/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Augmentation
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/lift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Lift
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/reduction/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Reduction
					</a>
				</div>
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/implant-revision-surgery/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Implant Revision
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/male-breast-procedures/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures
					</a>
				</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/' ) ); ?>" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Breast Procedures
				</a>
			</div>
			</div>
		</div>
		</div>

		<!-- Face Content -->
		<div class="tab-pane fade" id="face-content" role="tabpanel" aria-labelledby="face-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$face_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg' ) );
			if ( 0 !== $face_img_id ) {
				echo wp_get_attachment_image(
					$face_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Facial Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg' ) ) . '" alt="Facial Procedures" class="img-fluid rounded shadow" width="800" height="600">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline mt-3">Enhance Your Natural Beauty</p>
			<h3 class="section-heading">Facial Rejuvenation</h3>
			<p class="section-description ">Our facial procedures are designed to enhance your natural features and restore youthful appearance. From facelifts to rhinoplasty, our board-certified surgeons use the latest techniques to deliver exceptional results.</p>

			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/brow-lift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brow Lift
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Buccal Fat Removal
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Eyelid Lift
					</a>
				</div>
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/facelift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Facelift
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/mini-facelift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mini Facelift
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/neck-lift/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Neck Lift
					</a>
				</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/' ) ); ?>" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Facial Procedures
				</a>
			</div>
			</div>
		</div>
		</div>

		<!-- Non-Surgical Content -->
		<div class="tab-pane fade" id="nonsurgical-content" role="tabpanel" aria-labelledby="nonsurgical-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$nonsurg_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/face-home.jpg' ) );
			if ( 0 !== $nonsurg_img_id ) {
				echo wp_get_attachment_image(
					$nonsurg_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Non-Surgical Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/face-home.jpg' ) ) . '" alt="Non-Surgical Procedures" class="img-fluid rounded shadow" width="800" height="600">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline mt-3">Rejuvenate Without Surgery</p>
			<h3 class="section-heading">Non-Surgical Treatments</h3>
			<p class="section-description ">Our non-surgical treatments offer remarkable results with minimal downtime. From injectables to laser therapies, we provide a range of options to address your concerns without the need for surgery.</p>

			<div class="procedure-links ">
				<div class="row">

				<div class="col-md-6">
				<a href="<?php echo esc_url( home_url( '/non-surgical/j-plasma-skin-tightening/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> J-Plasma
					</a>
					<a href="<?php echo esc_url( home_url( '/weight-loss/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Weight Loss
					</a>
				</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/non-surgical/' ) ); ?>" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Non-Surgical Treatments
				</a>
			</div>
			</div>
		</div>
		</div>

		<!-- Men Content -->
		<div class="tab-pane fade" id="men-content" role="tabpanel" aria-labelledby="men-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$men_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/men-home.jpg' ) );
			if ( 0 !== $men_img_id ) {
				echo wp_get_attachment_image(
					$men_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => "Men's Procedures",
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/men-home.jpg' ) ) . '" alt="Men\'s Procedures" class="img-fluid rounded shadow" width="800" height="600">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline mt-3">Tailored Solutions For Men</p>
			<h3 class="section-heading">Men's Procedures</h3>
			<p class="section-description ">Our men's procedures are specifically designed to address the unique concerns and aesthetic goals of our male patients. From body contouring to facial rejuvenation, we offer a range of treatments to help you look and feel your best.</p>

			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-bbl/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Brazilian Butt Lift (BBL)
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/male-breast-procedures/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures
					</a>

				</div>
				<div class="col-md-6">
				<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-liposuction/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Liposuction
					</a>
					<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-tummy-tuck/' ) ); ?>" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Tummy Tuck
					</a>

				</div>
				</div>
				<a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/men/' ) ); ?>" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Men's Procedures
				</a>
			</div>
			</div>
		</div>
		</div>
	</div>
	</div>
</section>


<!-- Foundation Section -->
<section class="foundation-section">
	<div class="foundation-background">
		<?php
		$foundation_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/miaf6.jpg' ) );
		if ( 0 !== $foundation_img_id ) {
			echo wp_get_attachment_image(
				$foundation_img_id,
				'full',
				false,
				array(
					'alt'     => 'Mia Aesthetics Foundation community outreach',
					'sizes'   => '100vw',
					'loading' => 'lazy',
				)
			);
		} else {
			echo '<img src="' . esc_url( home_url( '/wp-content/uploads/2025/04/miaf6.jpg' ) ) . '" alt="Mia Aesthetics Foundation community outreach" width="1920" height="1080" loading="lazy">';
		}
		?>
	</div>

	<div class="container position-relative foundation-container">
		<div class="row">
			<div class="col-lg-8 col-12">
				<div class="foundation-content">
					<p class="foundation-tagline mb-3">Giving Back to Our Community</p>
					<h2 class="section-heading">The Mia Aesthetics Foundation</h2>
					<p class="section-description section-description--white">At Mia Aesthetics, we are committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
					<a href="<?php echo esc_url( home_url( '/mia-foundation/' ) ); ?>" class="btn btn-primary" role="button" aria-label="Discover Mia Aesthetics Foundation charitable work">See Our Impact <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>


</main>
<?php get_footer(); ?>
