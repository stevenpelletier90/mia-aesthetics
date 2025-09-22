<?php
/**
 * Template Name: Treatment Layout
 * Template Post Type: procedure, non-surgical, fat-transfer, page
 *
 * @package Mia_Aesthetics
 */

get_header();
$current_post           = get_queried_object();
$mia_aesthetics_hero_id = false;

if ( $current_post instanceof WP_Post ) {
	$mia_aesthetics_hero_id = get_post_thumbnail_id( $current_post );
}
?>
<main id="primary" role="main">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
<section class="treatment-hero position-relative overflow-hidden" role="banner" aria-labelledby="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>">
			<?php if ( false !== $mia_aesthetics_hero_id ) : ?>
				<picture class="hero-picture">
					<?php
					$hero_mobile_url = wp_get_attachment_image_url( $mia_aesthetics_hero_id, 'hero-mobile' );
					$hero_tablet_url = wp_get_attachment_image_url( $mia_aesthetics_hero_id, 'hero-tablet' );
					?>
					<?php if ( false !== $hero_mobile_url ) : ?>
					<source media="(max-width: 640px)" 
						srcset="<?php echo esc_url( $hero_mobile_url ); ?>">
					<?php endif; ?>
					<?php if ( false !== $hero_tablet_url ) : ?>
					<source media="(max-width: 1024px)" 
						srcset="<?php echo esc_url( $hero_tablet_url ); ?>">
					<?php endif; ?>
					<?php
					$hero_desktop_url = wp_get_attachment_image_url( $mia_aesthetics_hero_id, 'hero-desktop' );
					if ( false !== $hero_desktop_url ) :
						?>
					<img src="<?php echo esc_url( $hero_desktop_url ); ?>" 
						alt="<?php echo esc_attr( get_the_title() ); ?> treatment background"
						class="hero-bg"
						loading="eager"
						fetchpriority="high">
					<?php endif; ?>
				</picture>
			<?php endif; ?>
			
			<div class="hero-content">
				<div class="hero-inner">
					<div class="hero-text">
						<h1 id="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>"><?php the_title(); ?></h1>
						<?php
						// Try both price fields for flexibility.
						$mia_aesthetics_procedure_price = get_field( 'procedure_price' ) ?? get_field( 'non_surgical_price' );
						if ( null !== $mia_aesthetics_procedure_price && '' !== $mia_aesthetics_procedure_price ) :
							?>
							<div class="pricing-info">
								<h2>Starting Price: <?php echo esc_html( $mia_aesthetics_procedure_price ); ?>*</h2>
								<small>* Pricing varies by surgeon</small>
							</div>
						<?php endif; ?>
					</div>
					
					<div class="hero-form">
						<?php get_template_part( 'components/consultation-form' ); ?>
					</div>
				</div>
			</div>
		</section>

		<article class="single-treatment">
			<section class="main-content">
				<?php the_content(); ?>
			</section>

			<aside class="resources-featured-section py-5" aria-labelledby="resources-heading">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="text-center mb-4">
								<h3 id="resources-heading" class="h3 fw-bold mb-3 text-white">Additional Resources</h3>
								<p class="text-white opacity-75">Helpful information and related procedures to support your journey</p>
							</div>
						</div>
					</div>
					
					<div class="d-flex flex-wrap justify-content-center gap-3" aria-label="Related resources">
								<?php
								$mia_aesthetics_related_procedures = get_field( 'related_procedures' );
								if ( is_array( $mia_aesthetics_related_procedures ) && count( $mia_aesthetics_related_procedures ) > 0 ) :
									$mia_aesthetics_related_ids   = array_map(
										static function ( $p ) {
											return is_object( $p ) && property_exists( $p, 'ID' ) ? $p->ID : (int) $p;
										},
										$mia_aesthetics_related_procedures
									);
									$mia_aesthetics_related_query = new WP_Query(
										array(
											'post_type' => array( 'procedure', 'non-surgical', 'fat-transfer' ),
											'post__in'  => $mia_aesthetics_related_ids,
											'orderby'   => 'post__in',
											'posts_per_page' => count( $mia_aesthetics_related_ids ),
										)
									);
									if ( $mia_aesthetics_related_query->have_posts() ) :
										while ( $mia_aesthetics_related_query->have_posts() ) :
											$mia_aesthetics_related_query->the_post();
											?>
							<a class="resource-card d-flex align-items-center gap-3 p-3 text-decoration-none" style="width: 300px; max-width: 100%;"
								href="<?php the_permalink(); ?>">
								<i class="fa-solid fa-stethoscope flex-shrink-0" aria-hidden="true"></i>
								<div>
									<strong>Related: <?php the_title(); ?></strong><br>
									<span class="description">Learn about this complementary procedure</span>
								</div>
							</a>
											<?php
										endwhile;
										wp_reset_postdata();
									endif;
								endif;
								?>

						<a class="resource-card d-flex align-items-center gap-3 p-3 text-decoration-none" style="width: 300px; max-width: 100%;"
							href="/out-of-town-patients/">
							<i class="fa-solid fa-plane flex-shrink-0" aria-hidden="true"></i>
							<div>
								<strong>Out‑of‑Town Patients</strong><br>
								<span class="description">Travel info & accommodation details</span>
							</div>
						</a>

						<a class="resource-card d-flex align-items-center gap-3 p-3 text-decoration-none" style="width: 300px; max-width: 100%;"
							href="/calculate-your-bmi/">
							<i class="fa-solid fa-calculator flex-shrink-0" aria-hidden="true"></i>
							<div>
								<strong>BMI Calculator</strong><br>
								<span class="description">Calculate your BMI before booking</span>
							</div>
						</a>
					</div>
				</div>
			</aside>

			<?php
			$mia_aesthetics_faq_section = get_field( 'faq_section' );
			if ( is_array( $mia_aesthetics_faq_section ) && isset( $mia_aesthetics_faq_section['faqs'] ) && is_array( $mia_aesthetics_faq_section['faqs'] ) && count( $mia_aesthetics_faq_section['faqs'] ) > 0 ) :
				?>
				<section class="py-4 py-lg-5" aria-labelledby="faq-heading-<?php echo esc_attr( (string) get_the_ID() ); ?>">
					<div class="container">                        
						<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>                      
					</div>
				</section>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
