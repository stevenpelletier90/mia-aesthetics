<?php
/**
 * Footer Template
 *
 * @package Mia_Aesthetics
 */

?>
<!-- footer.php -->
<footer class="site-footer">
	<div class="container-fluid wide-container">
		<div class="footer-columns">     
			<div class="footer-column">
				<h2 class="footer-heading">Follow Us</h2>
				<div class="social-icons d-flex flex-wrap gap-3 mb-3">
					<?php mia_social_media_links( 'social-icon' ); ?>
				</div>
			</div>

			<!-- About Column -->
			<div class="footer-column">
				<h2 class="footer-heading">About</h2>
				<nav aria-label="About navigation">
					<ul class="footer-menu">
						<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">Our Story</a></li>
						<li><a href="<?php echo esc_url( home_url( '/mia-foundation/' ) ); ?>">Mia Foundation</a></li>
						<li><a href="<?php echo esc_url( home_url( '/locations/' ) ); ?>">Locations</a></li>
						<li><a href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>">Surgeons</a></li>
						<li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>">Careers</a></li>
						<li><a href="https://patient.miaaesthetics.com/s/login?ec=302&startURL=%2Fs%2Fhome" target="_blank" rel="noopener">Patient Portal</a></li>
					</ul>
				</nav>
			</div>

			<!-- Resources Column -->
			<div class="footer-column">
				<h2 class="footer-heading">Resources</h2>
				<nav aria-label="Resources navigation">
					<ul class="footer-menu">
						<li><a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>">FAQs</a></li>
						<li><a href="<?php echo esc_url( home_url( '/conditions/' ) ); ?>">Conditions We Treat</a></li>
						<li><a href="<?php echo esc_url( home_url( '/calculate-your-bmi/' ) ); ?>">Calculate Your BMI</a></li>
						<li><a href="<?php echo esc_url( home_url( '/patient-resources/' ) ); ?>">Patient Resources</a></li>
						<li><a href="<?php echo esc_url( home_url( '/surgical-journey/' ) ); ?>">Surgical Journey</a></li>
						<li><a href="<?php echo esc_url( home_url( '/out-of-town-patients/' ) ); ?>">Out of Town Patients</a></li>
					</ul>
				</nav>
			</div>
			
			<!-- Procedures Column -->
			<div class="footer-column">
				<h2 class="footer-heading">Procedures</h2>
				<nav aria-label="Procedures navigation">
					<ul class="footer-menu">
						<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/' ) ); ?>">Body Procedures</a></li>
						<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/' ) ); ?>">Breast Procedures</a></li>
						<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/' ) ); ?>">Face Procedures</a></li>
						<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/men/' ) ); ?>">Male Procedures</a></li>
						<li><a href="<?php echo esc_url( home_url( '/non-surgical/' ) ); ?>">Non-Surgical Options</a></li>
						<li><a href="<?php echo esc_url( home_url( '/before-after/' ) ); ?>">Before & After Gallery</a></li>
					</ul>
				</nav>
			</div>
		</div>
		
		<div class="footer-divider-container">
			<hr class="footer-divider">
		</div>
		
		<!-- Locations & Surgeons Section with Accordion -->
		<div class="locations-section mt-4">
			<h2 class="footer-heading mb-3">Locations & Surgeons</h2>
			<div class="accordion" id="locationsAccordion">
				<?php
				// Use cached footer locations to eliminate N+1 queries.
				$footer_locations = mia_get_footer_locations();

				if ( count( $footer_locations ) > 0 ) :
					foreach ( $footer_locations as $location ) :

						// Skip if no URL available.
						if ( ! isset( $location['url'] ) || '' === $location['url'] ) {
							continue;
						}
						?>
				<div class="accordion-item">
					<h2 class="accordion-header" id="location-heading-<?php echo esc_attr( (string) $location['id'] ); ?>">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#location-collapse-<?php echo esc_attr( (string) $location['id'] ); ?>" aria-expanded="false" aria-controls="location-collapse-<?php echo esc_attr( (string) $location['id'] ); ?>" aria-describedby="location-description-<?php echo esc_attr( (string) $location['id'] ); ?>">
							<?php echo esc_html( $location['title'] ); ?>
						</button>
					</h2>
					<div id="location-collapse-<?php echo esc_attr( (string) $location['id'] ); ?>" class="accordion-collapse collapse" aria-describedby="location-description-<?php echo esc_attr( (string) $location['id'] ); ?>">
						<div class="accordion-body" id="location-description-<?php echo esc_attr( (string) $location['id'] ); ?>">
							<!-- Location Link -->
							<div class="location-link mb-3">
								<a href="<?php echo esc_url( $location['url'] ); ?>" class="surgeon-link">
									<span>View <?php echo esc_html( $location['title'] ); ?> Location</span>
									<i class="fas fa-arrow-right surgeon-arrow" aria-hidden="true"></i>
								</a>
							</div>
							
							<?php if ( isset( $location['surgeons'] ) && count( $location['surgeons'] ) > 0 ) : ?>
								<div class="surgeons-list">
									<ul class="list-unstyled">
										<?php foreach ( $location['surgeons'] as $surgeon ) : ?>
											<?php if ( isset( $surgeon['url'] ) && '' !== $surgeon['url'] ) : ?>
											<li class="mb-2">
												<a href="<?php echo esc_url( $surgeon['url'] ); ?>" class="surgeon-link">
													<span><?php echo esc_html( $surgeon['title'] ); ?></span>
													<i class="fas fa-arrow-right surgeon-arrow" aria-hidden="true"></i>
												</a>
											</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								</div>
							<?php else : ?>
								<p class="mb-0">No surgeons currently listed for this location.</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
						<?php
					endforeach;
				else :
					?>
					<p>No locations found.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="footer-divider-container">
		<div class="container-fluid wide-container">
			<hr class="footer-divider">
		</div>
	</div>
	
	<!-- Bottom Footer - Simplified for better responsive behavior -->
	<div class="footer-bottom">
		<div class="container-fluid wide-container">
			<!-- Copyright Section -->
			<div class="text-center mb-4">
				<p class="copyright mb-1">© <?php echo esc_html( gmdate( 'Y' ) ); ?> Mia Aesthetics. All rights reserved.</p>
				<p class="disclaimer">The pictures on this website consist of both models and actual patients.</p>
			</div>
			
			<!-- Links Section - Centered -->
			<div class="text-center">
				<nav aria-label="Legal and policy navigation">
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/website-privacy-policy/' ) ); ?>">Privacy Policy</a></li>
						<li><a href="<?php echo esc_url( home_url( '/patient-privacy-practices/' ) ); ?>">Patient Privacy Practices</a></li>
						<li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">Terms & Conditions</a></li>
						<li><a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">Terms of Use</a></li>
						<li><a href="<?php echo esc_url( home_url( '/website-sms-terms-and-conditions/' ) ); ?>">SMS Terms & Conditions</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</footer>

<?php
// Add consultation CTA.
if ( should_show_consultation_cta() ) {
	require get_template_directory() . '/components/consultation-cta.php';
}
?>

<?php wp_footer(); ?>

</body>
</html>
