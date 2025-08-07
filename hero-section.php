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
 * ACF custom fields (for sidebar boxes):
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
					srcset="/wp-content/uploads/2025/08/Heat-Banner-Desktop.jpg 1920w, /wp-content/uploads/2025/08/Heat-Banner-WIDESCREEN.jpg 2560w"
					sizes="100vw">
				<!-- Mobile fallback: up to 767px (800x600 - different aspect ratio) -->
				<img src="/wp-content/uploads/2025/08/Heat-Banner-MOB.jpg" 
					class="d-block w-100" 
					alt="Beat the Heat Summer Special" 
					fetchpriority="high">
			</picture>
		</div>
	</div>

	<!-- Sidebar with Two Boxes (1600x900 each) -->
	<div class="hero-sidebar">
		<!-- Before & After Box -->
		<div class="hero-box hero-box-top">
		<?php
		$ba_image = get_field( 'ba_image' );
		if ( null !== $ba_image && is_array( $ba_image ) && isset( $ba_image['url'] ) && '' !== $ba_image['url'] ) :
			?>
		<!-- Desktop: 600x240 (2.5:1), Mobile: 600x720 (5:6) -->
		<picture>
			<img src="<?php echo esc_url( $ba_image['url'] ); ?>" 
				class="hero-box-image" 
				alt="<?php echo esc_attr( $ba_image['alt'] ?? 'Before & After Gallery' ); ?>"
				loading="lazy">
		</picture>
		<?php else : ?>
		<!-- Fallback with placehold.co -->
		<picture>
			<source media="(max-width: 767px)" srcset="https://placehold.co/600x720/1b1b1b/c8b273?text=Results+Mobile+(600x720)">
			<img src="https://placehold.co/600x240/1b1b1b/c8b273?text=Results+Desktop+(600x240)" 
				class="hero-box-image" 
				alt="Before & After Gallery"
				loading="lazy">
		</picture>
		<?php endif; ?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Before & After Gallery</div>
			<a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" class="mia-button" data-variant="hero">
			View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>

		<!-- Financing Box -->
		<div class="hero-box hero-box-bottom">
		<?php
		$financing_image = get_field( 'financing_image' );
		if ( null !== $financing_image && is_array( $financing_image ) && isset( $financing_image['url'] ) && '' !== $financing_image['url'] ) :
			?>
		<!-- Desktop: 600x240 (2.5:1), Mobile: 600x720 (5:6) -->
		<picture>
			<img src="<?php echo esc_url( $financing_image['url'] ); ?>" 
				class="hero-box-image" 
				alt="<?php echo esc_attr( $financing_image['alt'] ?? 'Affordable Financing' ); ?>">
		</picture>
		<?php else : ?>
		<!-- Fallback with placehold.co -->
		<picture>
			<source media="(max-width: 767px)" srcset="https://placehold.co/600x720/1b1b1b/c8b273?text=Financing+Mobile+(600x720)">
			<img src="https://placehold.co/600x240/1b1b1b/c8b273?text=Financing+Desktop+(600x240)" 
				class="hero-box-image" 
				alt="Affordable Financing">
		</picture>
		<?php endif; ?>
		<div class="hero-box-overlay">
			<div class="hero-box-heading">Affordable Financing</div>
			<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="mia-button" data-variant="hero">
			Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>
	</div>
	</div>
</section>
