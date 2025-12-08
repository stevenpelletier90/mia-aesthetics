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
		<div class="hero-background"></div>
		<div class="hero-overlay"></div>
		<div class="container hero-content">
			<div class="row justify-content-center">
				<div class="col-lg-10 col-xl-8 text-center">
					<h1 class="virtual-consultation-title">Free Virtual Consultation</h1>
					<p class="virtual-consultation-subtitle">
						Take the first step toward your transformation. Fill out the form below and one of our patient coordinators will contact you within 24 hours.
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="virtual-consultation-form-section">
		<div class="container container-lg">
			<div class="row justify-content-center">
				<div class="col-lg-8 col-xl-7">
					<div class="consultation-form-card">
						<form id="virtual-consultation-form" class="virtual-consultation-form">
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
										minlength="2"
										autocomplete="given-name"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter your first name (at least 2 characters).</div>
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
										minlength="2"
										autocomplete="family-name"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter your last name (at least 2 characters).</div>
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
										pattern="\(\d{3}\) \d{3}-\d{4}"
										title="Phone number in format (555) 555-5555"
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
										Zip/Postal Code <span class="required" aria-hidden="true">*</span>
									</label>
									<input
										type="text"
										id="zip-code"
										name="zip_code"
										class="form-control"
										placeholder="12345 or A1A 1A1"
										required
										pattern="(\d{5}|[A-Za-z]\d[A-Za-z]\s?\d[A-Za-z]\d)"
										title="US zip (12345) or Canadian postal code (A1A 1A1)"
										autocomplete="postal-code"
										maxlength="7"
										aria-required="true"
									>
									<div class="invalid-feedback">Please enter a valid zip/postal code.</div>
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
											<option value="<?php echo esc_attr( (string) $location->ID ); ?>">
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

							<div class="form-group form-preferences">
								<p class="form-label">Communication Preferences</p>
								<div class="form-check">
									<input
										type="checkbox"
										id="text-deals"
										name="text_deals"
										class="form-check-input"
									>
									<label for="text-deals" class="form-check-label">
										Text me the latest deals and promotions
									</label>
								</div>
								<div class="form-check">
									<input
										type="checkbox"
										id="email-specials"
										name="email_specials"
										class="form-check-input"
									>
									<label for="email-specials" class="form-check-label">
										Email me the latest specials
									</label>
								</div>
								<div class="form-check">
									<input
										type="checkbox"
										id="text-reminders"
										name="text_reminders"
										class="form-check-input"
									>
									<label for="text-reminders" class="form-check-label">
										Text me appointment reminders
									</label>
								</div>
							</div>

							<p class="form-disclaimer-consent">
								By checking this box and submitting this form, I consent by electronic signature to be contacted by Mia Aesthetics by live agent, email &amp; automatic telephone dialer for information, offers or advertisements via email/ phone call/ text message at the number &amp; email provided. I consent to call recording of all phone calls to and with Mia Aesthetics. I am not required to sign this document as a condition to purchase any goods or services. I understand that I can revoke this consent at any time by providing notice to Mia Aesthetics. Message data rates may apply. Message frequency may vary. To learn more, see our Privacy Policy, SMS Terms and Conditions, and Terms of Use.
							</p>

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
