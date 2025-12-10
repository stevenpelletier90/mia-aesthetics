<?php
/**
 * Hero Section Template
 *
 * Responsive banner implementation with hardcoded images:
 * - Heat Banner: Mobile/Desktop/Widescreen versions
 * - HotMom Banner: Mobile/Desktop/Widescreen versions
 *
 * Uses picture element with source sets for optimal loading:
 * - Mobile: up to 767px
 * - Desktop: 768px to 1920px
 * - Widescreen: above 1920px
 *
 * ACF custom fields (for hero boxes):
 * - ba_image: Before & After box image (image array)
 * - financing_image: Financing box image (image array)
 *
 * @package Mia_Aesthetics
 */

?>

<section class="hero-section">
	<div class="hero-container">
	<!-- Hero Banner (Responsive) -->
	<div class="hero-carousel">
		<div class="hero-banner">
			<picture>
				<!-- Desktop/Widescreen: 768px+ (same 3.2:1 aspect ratio) -->
				<source media="(min-width: 768px)" 
					srcset="/wp-content/uploads/2025/11/BF25-Desktop.jpg 1920w, /wp-content/uploads/2025/11/BF25-Banner-Wide.jpg 2560w"
					sizes="100vw">
				<!-- Mobile fallback: up to 767px (800x600 - different aspect ratio) -->
				<img src="/wp-content/uploads/2025/11/BF25-Banner-MOB-V2.jpg"
					class="d-block w-100"
					alt="Beat the Heat Summer Special"
					width="800"
					height="600"
					fetchpriority="high"
					data-no-lazy="1">
			</picture>
		</div>

		<!-- Hero CTA Button -->
		<div class="hero-cta-wrapper">
			<a href="<?php echo esc_url( home_url( '/black-friday/' ) ); ?>" class="btn btn-secondary" role="button">
				Save Now!
			</a>
		</div>
	</div>

	<!-- Hero Content Boxes -->
	<div class="hero-boxes">
		<!-- Before & After Box -->
		<div class="hero-box hero-box-top">
		<?php
		$ba_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/10/results-desktop-color.jpg' );
		if ( 0 !== $ba_img_id ) {
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
		} else {
			echo '<img src="/wp-content/uploads/2025/10/results-desktop-color.jpg" class="hero-box-image" alt="Before & After Gallery Results - Mia Aesthetics" width="412" height="155" loading="lazy">';
		}
		?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Before & After Gallery</div>
			<a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" class="btn btn-outline-primary-alt2" role="button">
			View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>

		<!-- Financing Box -->
		<div class="hero-box hero-box-bottom">
		<?php
		$fin_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/10/financing-02.jpg' );
		if ( 0 !== $fin_img_id ) {
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
		} else {
			echo '<img src="/wp-content/uploads/2025/10/financing-02.jpg" class="hero-box-image" alt="Affordable Financing at Mia Aesthetics" width="412" height="155" loading="lazy">';
		}
		?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Affordable Financing</div>
			<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="btn btn-outline-primary-alt2" role="button">
			Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>
	</div>
	</div>
</section>
