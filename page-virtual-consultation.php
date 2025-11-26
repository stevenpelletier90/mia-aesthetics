<?php
/**
 * Template Name: Virtual Consultation
 * Template Post Type: page
 *
 * Test page template for the virtual consultation form component.
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" <?php post_class( 'virtual-consultation-page' ); ?>>

	<section class="virtual-consultation-hero">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-6 text-center">
					<h1 class="virtual-consultation-title">Free Virtual Consultation</h1>
					<p class="virtual-consultation-subtitle">
						Take the first step toward your transformation. Fill out the form below and one of our patient coordinators will contact you within 24 hours.
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="virtual-consultation-form-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6 col-xl-5">
					<div class="consultation-form-card">
						<form id="virtual-consultation-form" class="virtual-consultation-form" novalidate>
							<div class="form-row">
								<div class="form-group">
									<label for="first-name" class="form-label">
										First Name <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="text"
										id="first-name"
										name="first_name"
										class="form-control"
										placeholder="Enter your first name"
										required
										autocomplete="given-name"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter your first name.</div>
								</div>

								<div class="form-group">
									<label for="last-name" class="form-label">
										Last Name <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="text"
										id="last-name"
										name="last_name"
										class="form-control"
										placeholder="Enter your last name"
										required
										autocomplete="family-name"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter your last name.</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label for="email" class="form-label">
										Email Address <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="email"
										id="email"
										name="email"
										class="form-control"
										placeholder="you@example.com"
										required
										autocomplete="email"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter a valid email address.</div>
								</div>

								<div class="form-group">
									<label for="phone" class="form-label">
										Phone Number <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="tel"
										id="phone"
										name="phone"
										class="form-control"
										placeholder="(555) 555-5555"
										required
										autocomplete="tel"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter a valid phone number.</div>
								</div>
							</div>

							<div class="form-row">
								<div class="form-group">
									<label for="preferred-language" class="form-label">
										Preferred Language <span class="required" aria-hidden="true">*</span>
									</label>
									<div class="select-wrapper">
										<select
											id="preferred-language"
											name="preferred_language"
											class="form-control"
											required
											aria-required="true"
										>
											<option value="" disabled selected>Select language</option>
											<option value="english">English</option>
											<option value="spanish">Spanish</option>
										</select>
									</div>
									<div class="invalid-feedback">Please select your preferred language.</div>
								</div>

								<div class="form-group">
									<label for="zip-code" class="form-label">
										Zip Code <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="text"
										id="zip-code"
										name="zip_code"
										class="form-control"
										placeholder="12345"
										required
										autocomplete="postal-code"
										pattern="[0-9]{5}"
										maxlength="5"
										inputmode="numeric"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter a valid 5-digit zip code.</div>
								</div>
							</div>

							<div class="form-group">
								<label for="location" class="form-label">
									Preferred Location <span class="required" aria-hidden="true">*</span>
								</label>
								<div class="select-wrapper">
									<select
										id="location"
										name="location"
										class="form-control"
										required
										aria-required="true"
									>
										<option value="" disabled selected>Select a location</option>
										<?php
										$locations = get_posts(
											array(
												'post_type' => 'location',
												'posts_per_page' => 100,
												'post_parent' => 0,
												'orderby' => 'title',
												'order'   => 'ASC',
												'post_status' => 'publish',
											)
										);
										foreach ( $locations as $location ) :
											?>
											<option value="<?php echo esc_attr( $location->ID ); ?>">
												<?php echo esc_html( $location->post_title ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="location-hint" id="location-hint" hidden>
									<i class="fa-solid fa-location-dot"></i>
									<span>Nearest location selected based on your zip code</span>
								</div>
								<div class="invalid-feedback">Please select a location.</div>
							</div>

							<div class="form-group form-consent">
								<div class="form-check">
									<input
										type="checkbox"
										id="consent"
										name="consent"
										class="form-check-input"
										required
										aria-required="true"
									>
									<label for="consent" class="form-check-label">
										I consent to receive communications from Mia Aesthetics.
										<a href="/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>
									</label>
								</div>
								<div class="invalid-feedback">You must agree to continue.</div>
							</div>

							<button type="submit" class="btn btn-submit">
								<span class="btn-text">Request Consultation</span>
								<span class="btn-loading" aria-hidden="true">
									<i class="fa-solid fa-spinner fa-spin"></i>
									Submitting...
								</span>
							</button>
						</form>

						<div class="form-success" role="alert" aria-live="polite" hidden>
							<div class="success-icon">
								<i class="fa-solid fa-circle-check"></i>
							</div>
							<h3>Thank You!</h3>
							<p>Your consultation request has been received. A patient coordinator will contact you within 24 hours.</p>
						</div>
					</div>

					<p class="form-disclaimer">
						<i class="fa-solid fa-lock"></i>
						Your information is secure and will never be shared with third parties.
					</p>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
