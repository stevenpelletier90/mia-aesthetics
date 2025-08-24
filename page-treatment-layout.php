<?php
/**
 * Template Name: Treatment Layout
 * Template Post Type: procedure, non-surgical, fat-transfer, page
 *
 * @package Mia_Aesthetics
 */

get_header();

?>
<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>
<?php
$current_post           = get_queried_object();
$mia_aesthetics_hero_id = false;

if ( $current_post instanceof WP_Post ) {
	$mia_aesthetics_hero_id = get_post_thumbnail_id( $current_post );
}
?>
<main id="main-content" role="main">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
<section class="treatment-header py-5 position-relative overflow-hidden" role="banner" aria-labelledby="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>">
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
			
			<div class="container">
				<div class="row min-vh-50 d-flex align-items-center justify-content-center">
					<div class="col-12 col-lg-7 mb-4 mb-lg-0 text-center text-lg-start">
<h1 id="page-title-<?php echo esc_attr( (string) get_the_ID() ); ?>"><?php the_title(); ?></h1>
						<?php
						// Try both price fields for flexibility.
						$mia_aesthetics_procedure_price = get_field( 'procedure_price' ) ?? get_field( 'non_surgical_price' );
						if ( null !== $mia_aesthetics_procedure_price && '' !== $mia_aesthetics_procedure_price ) :
							?>
							<div class="pricing-info mt-3">
								<h2 class="h4 mb-1">Starting Price: <?php echo esc_html( $mia_aesthetics_procedure_price ); ?>*</h2>
								<small>* Pricing varies by surgeon</small>
							</div>
						<?php endif; ?>
					</div>
					
					<div class="col-lg-5 d-none d-lg-block">
						<?php get_template_part( 'components/consultation-form' ); ?>
					</div>
				</div>
			</div>
		</section>

		<article class="single-treatment">
			<section class="main-content">
				<?php the_content(); ?>
			</section>
			
			<section class="results-resources-section" aria-labelledby="results-heading" aria-describedby="results-description">
				<div class="container">
					<div class="row g-4 g-lg-5 align-items-start">
						<div class="col-lg-7 mb-4 mb-lg-0">
							<h2 id="results-heading" class="h3 fw-bold mb-4 text-white">Before &amp; After Results</h2>
							<div id="results-description" class="sr-only">Patient before and after surgery results gallery</div>

							<?php
							$mia_aesthetics_gallery_images = get_field( 'gallery_images' );
							if ( is_array( $mia_aesthetics_gallery_images ) && count( $mia_aesthetics_gallery_images ) > 0 ) :
								?>
								<div class="row g-4">
									<?php foreach ( array_slice( $mia_aesthetics_gallery_images, 0, 2 ) as $mia_aesthetics_pair ) : ?>
										<div class="col-6">
											<div class="before-after-card h-100 overflow-hidden position-relative">
<span class="badge bg-dark position-absolute top-0 start-0 m-2" aria-hidden="true"><?php echo esc_html( __( 'Before', 'mia-aesthetics' ) ); ?></span>
												<?php echo wp_kses_post( mia_before_after_img( $mia_aesthetics_pair['before_image'], 'Before' ) ); ?>
											</div>
										</div>

										<div class="col-6">
											<div class="before-after-card h-100 overflow-hidden position-relative">
<span class="badge text-dark position-absolute top-0 start-0 m-2 badge-after" aria-hidden="true"><?php echo esc_html( __( 'After', 'mia-aesthetics' ) ); ?></span>
												<?php echo wp_kses_post( mia_before_after_img( $mia_aesthetics_pair['after_image'], 'After' ) ); ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
							
							<!-- Results Disclaimer -->
							<div class="text-center mt-3">
								<p class="small results-disclaimer-text mb-0">* Individual results may vary. All photos are of actual patients.</p>
							</div>

							<div class="text-center mt-4">
								<?php
								$mia_aesthetics_results_page = get_field( 'results_page' );
								if ( null !== $mia_aesthetics_results_page ) :
									// Handle different ACF Page Link return formats.
									if ( is_array( $mia_aesthetics_results_page ) ) {
										$mia_aesthetics_page_id  = $mia_aesthetics_results_page['ID'];
										$mia_aesthetics_page_url = $mia_aesthetics_results_page['url'];
									} elseif ( is_object( $mia_aesthetics_results_page ) && property_exists( $mia_aesthetics_results_page, 'ID' ) ) {
										$mia_aesthetics_page_id  = $mia_aesthetics_results_page->ID;
										$mia_aesthetics_page_url = get_permalink( $mia_aesthetics_page_id );
									} elseif ( is_numeric( $mia_aesthetics_results_page ) ) {
										$mia_aesthetics_page_id  = (int) $mia_aesthetics_results_page;
										$mia_aesthetics_page_url = get_permalink( $mia_aesthetics_page_id );
									} else {
										// Assume it's already a URL.
										$mia_aesthetics_page_id  = null;
										$mia_aesthetics_page_url = $mia_aesthetics_results_page;
									}

									// Only show if it's not the current page.
									if ( get_the_ID() !== $mia_aesthetics_page_id && '' !== $mia_aesthetics_page_url ) :
										?>
										<a href="<?php echo esc_url( $mia_aesthetics_page_url ); ?>" class="btn btn-primary">
											View More Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
										</a>
										<?php
									endif;
								endif;
								?>
							</div>
						</div>

						<aside class="col-lg-5" aria-labelledby="resources-heading">
							<h2 id="resources-heading" class="h3 fw-bold mb-4 text-white">Additional Resources</h2>

							<nav class="list-group list-group-flush rounded-3" aria-label="Related resources">
								<?php
								$mia_aesthetics_related_procedures = get_field( 'related_procedures' );
								if ( is_array( $mia_aesthetics_related_procedures ) && count( $mia_aesthetics_related_procedures ) > 0 ) :
									$mia_aesthetics_related_ids   = array_map(
										function ( $p ) {
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
											<a class="list-group-item list-group-item-action d-flex gap-3 py-3"
												href="<?php the_permalink(); ?>">
												<i class="fa-solid fa-stethoscope fs-4 flex-shrink-0" aria-hidden="true"></i>
												<span>
													<strong>Related: <?php the_title(); ?></strong><br>
													<small class="text-muted">Learn about this complementary procedure</small>
												</span>
											</a>
											<?php
										endwhile;
										wp_reset_postdata();
									endif;
								endif;
								?>

								<a class="list-group-item list-group-item-action d-flex gap-3 py-3"
									href="/out-of-town-patients/">
									<i class="fa-solid fa-plane fs-4 flex-shrink-0" aria-hidden="true"></i>
									<span>
										<strong>Out‑of‑Town Patients</strong><br>
										<small class="text-muted">Travel info & accommodation details</small>
									</span>
								</a>

								<a class="list-group-item list-group-item-action d-flex gap-3 py-3"
									href="/calculate-your-bmi/">
									<i class="fa-solid fa-calculator fs-4 flex-shrink-0" aria-hidden="true"></i>
									<span>
										<strong>BMI Calculator</strong><br>
										<small class="text-muted">Calculate your BMI before booking</small>
									</span>
								</a>
							</nav>
						</aside>
					</div>
				</div>
			</section>

			<?php
			$mia_aesthetics_faq_section = get_field( 'faq_section' );
			if ( is_array( $mia_aesthetics_faq_section ) && isset( $mia_aesthetics_faq_section['faqs'] ) && is_array( $mia_aesthetics_faq_section['faqs'] ) && count( $mia_aesthetics_faq_section['faqs'] ) > 0 ) :
				?>
				<section class="py-4 py-lg-5" aria-labelledby="faq-heading-<?php echo esc_attr( (string) get_the_ID() ); ?>">
					<div class="container">                        
						<?php echo wp_kses_post( mia_aesthetics_display_page_faqs() ); ?>                      
					</div>
				</section>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
