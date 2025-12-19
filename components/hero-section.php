<?php
/**
 * Hero Section Template
 *
 * Responsive banner implementation using ACF options fields.
 * Images are served via wp_get_attachment_image() for:
 * - Automatic srcset generation
 * - WebP/AVIF delivery via Imagify
 * - Proper WP Rocket integration
 *
 * ACF Fields (Theme Settings → Hero Section):
 * - hero_banner_mobile: Mobile image (800x600, 4:3)
 * - hero_banner_desktop: Desktop image (1920x600, 3.2:1)
 * - hero_banner_widescreen: Widescreen image (2560x800, 3.2:1)
 * - hero_banner_alt: Alt text for banner
 * - hero_banner_link: CTA destination URL
 * - hero_cta_text: CTA button text
 * - hero_ba_image: Before & After box background
 * - hero_financing_image: Financing box background
 *
 * @package Mia_Aesthetics
 */

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

<section class="hero-section">
	<div class="hero-container">
	<!-- Hero Banner (Responsive) -->
	<div class="hero-carousel">
		<div class="hero-banner">
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
		</div>

		<!-- Hero CTA Button -->
		<div class="hero-cta-wrapper">
			<a href="<?php echo esc_url( $hero_link ); ?>" class="hero-cta" role="button">
				<?php echo esc_html( $hero_cta_text ); ?>
			</a>
		</div>
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
		<div class="hero-box hero-box-top">
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
			<a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>" class="btn btn-outline-primary-alt2" role="button">
			View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>

		<!-- Financing Box -->
		<div class="hero-box hero-box-bottom">
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
			<a href="<?php echo esc_url( home_url( '/financing/' ) ); ?>" class="btn btn-outline-primary-alt2" role="button">
			Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
		</div>
	</div>
	</div>
</section>
