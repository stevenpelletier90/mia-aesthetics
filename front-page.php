<?php
/**
 * The front page template file
 *
 * Consolidated template with hero section integrated for simpler maintainability.
 *
 * @package Mia_Aesthetics
 */

get_header();

// ============================================================================
// HERO SECTION - ACF Data
// ============================================================================
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

// Get image URLs for picture element sources.
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

// Get hero box images from ACF options.
$ba_img_id  = get_field( 'hero_ba_image', 'option' );
$fin_img_id = get_field( 'hero_financing_image', 'option' );
$ba_img_id  = is_numeric( $ba_img_id ) ? (int) $ba_img_id : 0;
$fin_img_id = is_numeric( $fin_img_id ) ? (int) $fin_img_id : 0;

// Get site stats.
$stats = mia_aesthetics_get_site_stats();
?>

<main id="primary" class="site-main">

	<!-- ====================================================================
		HERO SECTION
		==================================================================== -->
	<section class="hero" aria-label="Featured promotion">
		<div class="hero__banner">
			<?php if ( $hero_mobile_id > 0 && $hero_desktop_id > 0 ) : ?>
			<picture>
				<?php if ( $has_separate_widescreen ) : ?>
				<source media="(min-width: 1921px)"
					srcset="<?php echo esc_attr( $widescreen_srcset ); ?>"
					sizes="100vw">
				<?php endif; ?>
				<?php if ( '' !== $desktop_srcset ) : ?>
				<source media="(min-width: 768px)"
					srcset="<?php echo esc_attr( $desktop_srcset ); ?>"
					sizes="100vw">
				<?php elseif ( '' !== $desktop_url ) : ?>
				<source media="(min-width: 768px)"
					srcset="<?php echo esc_url( $desktop_url ); ?>">
				<?php endif; ?>
				<img src="<?php echo esc_url( $mobile_url ); ?>"
					<?php if ( '' !== $mobile_srcset ) : ?>
					srcset="<?php echo esc_attr( $mobile_srcset ); ?>"
					sizes="100vw"
					<?php endif; ?>
					class="hero__image"
					alt="<?php echo esc_attr( $hero_alt ); ?>"
					width="800"
					height="600"
					fetchpriority="high"
					data-no-lazy="1">
			</picture>
			<?php endif; ?>

			<div class="hero__cta-wrapper">
				<a href="<?php echo esc_url( $hero_link ); ?>" class="hero__cta">
					<?php echo esc_html( $hero_cta_text ); ?>
				</a>
			</div>
		</div>

		<div class="hero__features">
			<a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" class="hero__feature">
				<?php
				if ( $ba_img_id > 0 ) {
					echo wp_get_attachment_image(
						$ba_img_id,
						'medium_large',
						false,
						array(
							'class'   => 'hero__feature-image',
							'alt'     => 'Before & After Gallery Results',
							'sizes'   => '(max-width: 767px) 100vw, 50vw',
							'loading' => 'lazy',
						)
					);
				}
				?>
				<div class="hero__feature-overlay">
					<span class="hero__feature-title">Before & After Gallery</span>
					<span class="hero__feature-action">
						View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</span>
				</div>
			</a>

			<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="hero__feature">
				<?php
				if ( $fin_img_id > 0 ) {
					echo wp_get_attachment_image(
						$fin_img_id,
						'medium_large',
						false,
						array(
							'class'   => 'hero__feature-image',
							'alt'     => 'Affordable Financing Options',
							'sizes'   => '(max-width: 767px) 100vw, 50vw',
							'loading' => 'lazy',
						)
					);
				}
				?>
				<div class="hero__feature-overlay">
					<span class="hero__feature-title">Affordable Financing</span>
					<span class="hero__feature-action">
						Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</span>
				</div>
			</a>
		</div>
	</section>

	<!-- ====================================================================
		ABOUT / STATS SECTION
		==================================================================== -->
	<section class="about" aria-labelledby="about-heading">
		<div class="container">
			<header class="about__header">
				<h1 id="about-heading" class="about__title">Why Mia Aesthetics?</h1>
			</header>

			<div class="stats">
				<div class="stats__item">
					<span class="stats__number" data-count="2017">0</span>
					<span class="stats__label">Year Founded</span>
				</div>
				<div class="stats__item">
					<span class="stats__number" data-count="<?php echo esc_attr( (string) $stats['surgeons'] ); ?>">0</span>
					<span class="stats__label">Expert Surgeons</span>
				</div>
				<div class="stats__item">
					<span class="stats__number" data-count="<?php echo esc_attr( (string) $stats['locations'] ); ?>">0</span>
					<span class="stats__label">Clinic Locations</span>
				</div>
				<div class="stats__item">
					<span class="stats__number" data-count="150" data-suffix="k+">0</span>
					<span class="stats__label">Satisfied Patients</span>
				</div>
			</div>

			<div class="about__mission">
				<p>Our mission at <strong>Mia Aesthetics</strong> is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
			</div>
		</div>
	</section>

	<!-- ====================================================================
		EXCELLENCE SECTION
		==================================================================== -->
	<section class="excellence" aria-labelledby="excellence-heading">
		<div class="container">
			<div class="excellence__grid">
				<div class="excellence__media">
					<?php
					$surgery_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/05/surgery-1.jpg' ) );
					if ( 0 !== $surgery_img_id ) {
						echo wp_get_attachment_image(
							$surgery_img_id,
							'large',
							false,
							array(
								'class' => 'excellence__image',
								'alt'   => 'Trusted Surgical Excellence',
								'sizes' => '(max-width: 991px) 100vw, 50vw',
							)
						);
					} else {
						echo '<img src="/wp-content/uploads/2025/05/surgery-1.jpg" alt="Trusted Surgical Excellence" class="excellence__image" width="800" height="600">';
					}
					?>
				</div>
				<div class="excellence__content">
					<span class="excellence__tagline">Our Commitment</span>
					<h2 id="excellence-heading" class="excellence__title">Trusted Surgical Excellence</h2>
					<p class="excellence__description">Delivering life-changing results with expert care at every step. Our team of highly skilled specialists, years of experience, and thousands of satisfied patients set us apart in the industry.</p>
					<div class="excellence__actions">
						<a href="/locations/" class="btn btn-primary">Our Locations</a>
						<a href="/plastic-surgeons/" class="btn btn-primary">Our Surgeons</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ====================================================================
		VIDEO SECTION
		==================================================================== -->
	<section class="video-showcase" aria-labelledby="video-heading">
		<div class="video-showcase__container">
			<video autoplay muted loop playsinline class="video-showcase__video">
				<source src="/wp-content/uploads/2025/09/home-video.mp4" type="video/mp4">
			</video>
		</div>
		<div class="video-showcase__overlay">
			<div class="container">
				<div class="video-showcase__content">
					<h2 id="video-heading" class="video-showcase__title">Experience Excellence</h2>
					<p class="video-showcase__description">Join thousands of satisfied patients who have transformed their lives at Mia Aesthetics</p>
					<a href="/free-plastic-surgery-consultation/" class="btn btn-primary btn-lg">Free Virtual Consultation</a>
				</div>
			</div>
		</div>
	</section>

	<!-- ====================================================================
		PROCEDURES SECTION
		==================================================================== -->
	<section class="procedures" aria-labelledby="procedures-heading">
		<div class="container">
			<header class="procedures__header">
				<h2 id="procedures-heading" class="procedures__title">Our Procedures</h2>
				<p class="procedures__subtitle">Discover the perfect procedure for your aesthetic goals</p>
			</header>

			<!-- Desktop Tabs -->
			<div class="procedures__tabs-wrapper">
				<button class="procedures__arrow procedures__arrow--prev" type="button" aria-label="Previous category">
					<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
				</button>

				<ul class="procedures__tabs" id="procedureTabs" role="tablist">
					<li role="presentation">
						<button class="procedures__tab active" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-panel" type="button" role="tab" aria-controls="body-panel" aria-selected="true">Body</button>
					</li>
					<li role="presentation">
						<button class="procedures__tab" id="breast-tab" data-bs-toggle="tab" data-bs-target="#breast-panel" type="button" role="tab" aria-controls="breast-panel" aria-selected="false">Breast</button>
					</li>
					<li role="presentation">
						<button class="procedures__tab" id="face-tab" data-bs-toggle="tab" data-bs-target="#face-panel" type="button" role="tab" aria-controls="face-panel" aria-selected="false">Face</button>
					</li>
					<li role="presentation">
						<button class="procedures__tab" id="nonsurgical-tab" data-bs-toggle="tab" data-bs-target="#nonsurgical-panel" type="button" role="tab" aria-controls="nonsurgical-panel" aria-selected="false">Non-Surgical</button>
					</li>
					<li role="presentation">
						<button class="procedures__tab" id="men-tab" data-bs-toggle="tab" data-bs-target="#men-panel" type="button" role="tab" aria-controls="men-panel" aria-selected="false">Men</button>
					</li>
				</ul>

				<button class="procedures__arrow procedures__arrow--next" type="button" aria-label="Next category">
					<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
				</button>
			</div>

			<!-- Mobile Dropdown -->
			<select id="procedureDropdown" class="procedures__dropdown" aria-label="Select procedure category">
				<option value="body-panel" selected>Body</option>
				<option value="breast-panel">Breast</option>
				<option value="face-panel">Face</option>
				<option value="nonsurgical-panel">Non-Surgical</option>
				<option value="men-panel">Men</option>
			</select>

			<!-- Tab Panels -->
			<div class="tab-content procedures__panels" id="procedureTabsContent">

				<!-- Body Panel -->
				<div class="tab-pane fade show active procedures__panel" id="body-panel" role="tabpanel" aria-labelledby="body-tab">
					<div class="procedures__content">
						<div class="procedures__media">
							<?php
							$body_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/body-home.jpg' ) );
							if ( 0 !== $body_img_id ) {
								echo wp_get_attachment_image(
									$body_img_id,
									'large',
									false,
									array(
										'class' => 'procedures__image',
										'alt'   => 'Body Procedures',
										'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 50vw',
									)
								);
							} else {
								echo '<img src="/wp-content/uploads/2025/04/body-home.jpg" alt="Body Procedures" class="procedures__image" width="800" height="600">';
							}
							?>
						</div>
						<div class="procedures__info">
							<span class="procedures__tagline">Sculpt Your Ideal Figure</span>
							<h3 class="procedures__name">Body Contouring</h3>
							<p class="procedures__description">Our body contouring procedures help you achieve the silhouette you desire. Whether you're looking to remove excess fat, tighten loose skin, or enhance your curves, our specialists can help you reach your aesthetic goals.</p>
							<div class="procedures__links">
								<div class="procedures__links-grid">
									<a href="/cosmetic-plastic-surgery/body/awake-liposuction/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Awake Lipo</a>
									<a href="/cosmetic-plastic-surgery/body/mommy-makeover/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mommy Makeover</a>
									<a href="/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brazilian Butt Lift (BBL)</a>
									<a href="/cosmetic-plastic-surgery/body/tummy-tuck/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Tummy Tuck</a>
									<a href="/cosmetic-plastic-surgery/body/lipo-360/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Lipo 360</a>
									<a href="/cosmetic-plastic-surgery/body/arm-lift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Arm Lift</a>
								</div>
								<a href="/cosmetic-plastic-surgery/body/" class="procedures__link procedures__link--all"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Body Procedures</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Breast Panel -->
				<div class="tab-pane fade procedures__panel" id="breast-panel" role="tabpanel" aria-labelledby="breast-tab">
					<div class="procedures__content">
						<div class="procedures__media">
							<?php
							$breast_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/breast-home.jpg' ) );
							if ( 0 !== $breast_img_id ) {
								echo wp_get_attachment_image(
									$breast_img_id,
									'large',
									false,
									array(
										'class' => 'procedures__image',
										'alt'   => 'Breast Procedures',
										'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 50vw',
									)
								);
							} else {
								echo '<img src="/wp-content/uploads/2025/04/breast-home.jpg" alt="Breast Procedures" class="procedures__image" width="800" height="600">';
							}
							?>
						</div>
						<div class="procedures__info">
							<span class="procedures__tagline">Achieve Your Desired Look</span>
							<h3 class="procedures__name">Breast Enhancement</h3>
							<p class="procedures__description">Our breast procedures are designed to enhance, reduce, or lift your breasts to achieve your desired appearance. Our board-certified surgeons use the latest techniques to deliver natural-looking results with minimal scarring.</p>
							<div class="procedures__links">
								<div class="procedures__links-grid">
									<a href="/cosmetic-plastic-surgery/breast/augmentation-implants/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Augmentation</a>
									<a href="/cosmetic-plastic-surgery/breast/implant-revision-surgery/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Implant Revision</a>
									<a href="/cosmetic-plastic-surgery/breast/lift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Lift</a>
									<a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures</a>
									<a href="/cosmetic-plastic-surgery/breast/reduction/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Reduction</a>
								</div>
								<a href="/cosmetic-plastic-surgery/breast/" class="procedures__link procedures__link--all"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Breast Procedures</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Face Panel -->
				<div class="tab-pane fade procedures__panel" id="face-panel" role="tabpanel" aria-labelledby="face-tab">
					<div class="procedures__content">
						<div class="procedures__media">
							<?php
							$face_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg' ) );
							if ( 0 !== $face_img_id ) {
								echo wp_get_attachment_image(
									$face_img_id,
									'large',
									false,
									array(
										'class' => 'procedures__image',
										'alt'   => 'Facial Procedures',
										'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 50vw',
									)
								);
							} else {
								echo '<img src="/wp-content/uploads/2025/04/face-2-home.jpg" alt="Facial Procedures" class="procedures__image" width="800" height="600">';
							}
							?>
						</div>
						<div class="procedures__info">
							<span class="procedures__tagline">Enhance Your Natural Beauty</span>
							<h3 class="procedures__name">Facial Rejuvenation</h3>
							<p class="procedures__description">Our facial procedures are designed to enhance your natural features and restore youthful appearance. From facelifts to rhinoplasty, our board-certified surgeons use the latest techniques to deliver exceptional results.</p>
							<div class="procedures__links">
								<div class="procedures__links-grid">
									<a href="/cosmetic-plastic-surgery/face/brow-lift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brow Lift</a>
									<a href="/cosmetic-plastic-surgery/face/facelift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Facelift</a>
									<a href="/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Buccal Fat Removal</a>
									<a href="/cosmetic-plastic-surgery/face/mini-facelift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mini Facelift</a>
									<a href="/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Eyelid Lift</a>
									<a href="/cosmetic-plastic-surgery/face/neck-lift/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Neck Lift</a>
								</div>
								<a href="/cosmetic-plastic-surgery/face/" class="procedures__link procedures__link--all"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Facial Procedures</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Non-Surgical Panel -->
				<div class="tab-pane fade procedures__panel" id="nonsurgical-panel" role="tabpanel" aria-labelledby="nonsurgical-tab">
					<div class="procedures__content">
						<div class="procedures__media">
							<?php
							$nonsurg_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/face-home.jpg' ) );
							if ( 0 !== $nonsurg_img_id ) {
								echo wp_get_attachment_image(
									$nonsurg_img_id,
									'large',
									false,
									array(
										'class' => 'procedures__image',
										'alt'   => 'Non-Surgical Procedures',
										'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 50vw',
									)
								);
							} else {
								echo '<img src="/wp-content/uploads/2025/04/face-home.jpg" alt="Non-Surgical Procedures" class="procedures__image" width="800" height="600">';
							}
							?>
						</div>
						<div class="procedures__info">
							<span class="procedures__tagline">Rejuvenate Without Surgery</span>
							<h3 class="procedures__name">Non-Surgical Treatments</h3>
							<p class="procedures__description">Our non-surgical treatments offer remarkable results with minimal downtime. From injectables to laser therapies, we provide a range of options to address your concerns without the need for surgery.</p>
							<div class="procedures__links">
								<div class="procedures__links-grid">
									<a href="/non-surgical/j-plasma-skin-tightening/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> J-Plasma</a>
									<a href="/weight-loss/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Weight Loss</a>
								</div>
								<a href="/non-surgical/" class="procedures__link procedures__link--all"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Non-Surgical Treatments</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Men Panel -->
				<div class="tab-pane fade procedures__panel" id="men-panel" role="tabpanel" aria-labelledby="men-tab">
					<div class="procedures__content">
						<div class="procedures__media">
							<?php
							$men_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/men-home.jpg' ) );
							if ( 0 !== $men_img_id ) {
								echo wp_get_attachment_image(
									$men_img_id,
									'large',
									false,
									array(
										'class' => 'procedures__image',
										'alt'   => "Men's Procedures",
										'sizes' => '(max-width: 767px) 100vw, (max-width: 1199px) 100vw, 50vw',
									)
								);
							} else {
								echo '<img src="/wp-content/uploads/2025/04/men-home.jpg" alt="Men\'s Procedures" class="procedures__image" width="800" height="600">';
							}
							?>
						</div>
						<div class="procedures__info">
							<span class="procedures__tagline">Tailored Solutions For Men</span>
							<h3 class="procedures__name">Men's Procedures</h3>
							<p class="procedures__description">Our men's procedures are specifically designed to address the unique concerns and aesthetic goals of our male patients. From body contouring to facial rejuvenation, we offer a range of treatments to help you look and feel your best.</p>
							<div class="procedures__links">
								<div class="procedures__links-grid">
									<a href="/cosmetic-plastic-surgery/body/male-bbl/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Brazilian Butt Lift</a>
									<a href="/cosmetic-plastic-surgery/body/male-liposuction/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Liposuction</a>
									<a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures</a>
									<a href="/cosmetic-plastic-surgery/body/male-tummy-tuck/" class="procedures__link"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Tummy Tuck</a>
								</div>
								<a href="/cosmetic-plastic-surgery/men/" class="procedures__link procedures__link--all"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Men's Procedures</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ====================================================================
		FOUNDATION SECTION
		==================================================================== -->
	<section class="foundation" aria-labelledby="foundation-heading">
		<div class="foundation__background">
			<?php
			$foundation_img_id = attachment_url_to_postid( home_url( '/wp-content/uploads/2025/04/miaf6.jpg' ) );
			if ( 0 !== $foundation_img_id ) {
				echo wp_get_attachment_image(
					$foundation_img_id,
					'full',
					false,
					array(
						'class'   => 'foundation__image',
						'alt'     => 'Mia Aesthetics Foundation community outreach',
						'sizes'   => '100vw',
						'loading' => 'lazy',
					)
				);
			} else {
				echo '<img src="/wp-content/uploads/2025/04/miaf6.jpg" alt="Mia Aesthetics Foundation community outreach" class="foundation__image" width="1920" height="1080" loading="lazy">';
			}
			?>
		</div>
		<div class="foundation__overlay">
			<div class="container">
				<div class="foundation__content">
					<span class="foundation__tagline">Giving Back to Our Community</span>
					<h2 id="foundation-heading" class="foundation__title">The Mia Aesthetics Foundation</h2>
					<p class="foundation__description">At Mia Aesthetics, we are committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
					<a href="/mia-foundation/" class="btn btn-primary" aria-label="Discover Mia Aesthetics Foundation">
						See Our Impact <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
