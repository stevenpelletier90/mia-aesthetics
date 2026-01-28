<?php
/**
 * Template Name: Careers Locations
 *
 * Career opportunities page template that adapts the single location layout
 * for displaying job opportunities at specific locations
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<section class="location-header">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<h1 class="mb-3">Careers at <?php echo esc_html( get_the_title() ); ?></h1>
					<div class="location-intro mb-4">
						<p>Join our team at our 
						<?php
						$mia_aesthetics_location_title = get_the_title();
						$mia_aesthetics_location_title = str_replace( 'Mia Aesthetics', '', $mia_aesthetics_location_title );
						echo esc_html( trim( $mia_aesthetics_location_title ) );
						?>
						location. We're looking for passionate professionals to join our 
						mission of transforming lives through exceptional aesthetic care.</p>
					</div>
					<div class="location-info mb-4">
<?php
						// Get location data from linked main location.
						$mia_aesthetics_linked_location = get_field( 'linked_main_location' );
if ( null !== $mia_aesthetics_linked_location ) {
	// Use ACF fields from the linked location post.
	$mia_aesthetics_location_map       = get_field( 'location_map', $mia_aesthetics_linked_location->ID );
	$mia_aesthetics_phone_number       = get_field( 'phone_number', $mia_aesthetics_linked_location->ID );
	$mia_aesthetics_location_maps_link = get_field( 'location_maps_link', $mia_aesthetics_linked_location->ID );
} else {
	// Fallback to current page fields.
	$mia_aesthetics_location_map       = get_field( 'location_map' );
	$mia_aesthetics_phone_number       = get_field( 'phone_number' );
	$mia_aesthetics_location_maps_link = get_field( 'location_maps_link' );
}

if ( is_array( $mia_aesthetics_location_map ) && count( $mia_aesthetics_location_map ) > 0 ) {
	$mia_aesthetics_street = ( $mia_aesthetics_location_map['street_number'] ?? '' ) . ' ' . ( $mia_aesthetics_location_map['street_name'] ?? '' );
	$mia_aesthetics_city   = $mia_aesthetics_location_map['city'] ?? '';
	$mia_aesthetics_state  = $mia_aesthetics_location_map['state_short'] ?? '';
	$mia_aesthetics_zip    = $mia_aesthetics_location_map['post_code'] ?? '';

	// Special handling for locations where Google Maps doesn't populate city correctly.
	// Check if city is empty but we have other address components.
	// For Brooklyn/NYC addresses, Google sometimes doesn't populate city.
	if ( '' === $mia_aesthetics_city && '' !== $mia_aesthetics_state && ( 'NY' === $mia_aesthetics_state && false !== stripos( $mia_aesthetics_street, 'atlantic' ) ) ) {
		$mia_aesthetics_city = 'Brooklyn';
	}
	?>
							<?php if ( ! in_array( trim( $mia_aesthetics_street ), array( '', '0' ), true ) || '' !== $mia_aesthetics_city || '' !== $mia_aesthetics_state || '' !== $mia_aesthetics_zip ) : ?>
							<div class="location-detail mb-4">
								<div class="d-flex flex-column">
									<?php if ( ! in_array( trim( $mia_aesthetics_street ), array( '', '0' ), true ) ) : ?>
										<span><?php echo esc_html( trim( $mia_aesthetics_street ) ); ?></span>
									<?php endif; ?>
									<?php
									$mia_aesthetics_address_line2 = trim( $mia_aesthetics_city . ', ' . $mia_aesthetics_state . ' ' . $mia_aesthetics_zip, ', ' );
									if ( '' !== $mia_aesthetics_address_line2 && '0' !== $mia_aesthetics_address_line2 ) :
										?>
										<span><?php echo esc_html( $mia_aesthetics_address_line2 ); ?></span>
									<?php endif; ?>
								</div>
							</div>
							<?php endif; ?>
						<?php } ?>

						<?php // $mia_aesthetics_phone_number already set above. ?>
						<?php if ( null !== $mia_aesthetics_phone_number && '' !== $mia_aesthetics_phone_number ) : ?>
							<div class="location-detail mb-2">
								<div class="d-flex align-items-center">
									<i class="fas fa-phone location-icon" aria-hidden="true"></i>
									<a href="tel:<?php echo esc_attr( $mia_aesthetics_phone_number ); ?>" class="location-phone">
										<?php echo esc_html( $mia_aesthetics_phone_number ); ?>
									</a>
								</div>
							</div>
						<?php endif; ?>

						<?php
						// Grouped hours of operation (short format).
						$mia_aesthetics_short_days = array(
							'Monday'    => 'Mon',
							'Tuesday'   => 'Tue',
							'Wednesday' => 'Wed',
							'Thursday'  => 'Thu',
							'Friday'    => 'Fri',
							'Saturday'  => 'Sat',
							'Sunday'    => 'Sun',
						);
						$mia_aesthetics_hours_rows = array();
						// Use business hours from linked location if available.
							$mia_aesthetics_hours_field_location = null !== $mia_aesthetics_linked_location ? $mia_aesthetics_linked_location->ID : false;
						if ( have_rows( 'business_hours', $mia_aesthetics_hours_field_location ) ) {
							while ( have_rows( 'business_hours', $mia_aesthetics_hours_field_location ) ) :
								the_row();
								$mia_aesthetics_day   = get_sub_field( 'day' );
								$mia_aesthetics_hours = get_sub_field( 'hours' );
								if ( null !== $mia_aesthetics_day && '' !== $mia_aesthetics_day && null !== $mia_aesthetics_hours && '' !== $mia_aesthetics_hours ) {
									$mia_aesthetics_hours_rows[] = array(
										'day'   => $mia_aesthetics_day,
										'hours' => $mia_aesthetics_hours,
									);
								}
							endwhile;
						}
						$mia_aesthetics_output = array();
						$mia_aesthetics_n      = count( $mia_aesthetics_hours_rows );
						$mia_aesthetics_i      = 0;
						while ( $mia_aesthetics_i < $mia_aesthetics_n ) {
							$mia_aesthetics_start         = $mia_aesthetics_i;
							$mia_aesthetics_current_hours = $mia_aesthetics_hours_rows[ $mia_aesthetics_i ]['hours'];
							while (
								$mia_aesthetics_i + 1 < $mia_aesthetics_n &&
								$mia_aesthetics_hours_rows[ $mia_aesthetics_i + 1 ]['hours'] === $mia_aesthetics_current_hours
							) {
								++$mia_aesthetics_i;
							}
							if ( $mia_aesthetics_start === $mia_aesthetics_i ) {
								$mia_aesthetics_label = $mia_aesthetics_short_days[ $mia_aesthetics_hours_rows[ $mia_aesthetics_start ]['day'] ];
							} else {
								$mia_aesthetics_label = $mia_aesthetics_short_days[ $mia_aesthetics_hours_rows[ $mia_aesthetics_start ]['day'] ] . '–' . $mia_aesthetics_short_days[ $mia_aesthetics_hours_rows[ $mia_aesthetics_i ]['day'] ];
							}
							$mia_aesthetics_output[] = $mia_aesthetics_label . ' ' . $mia_aesthetics_current_hours;
							++$mia_aesthetics_i;
						}
						if ( array() !== $mia_aesthetics_output ) :
							?>
							<div class="location-detail mb-2">
								<div class="d-flex align-items-center">
									<i class="fas fa-clock location-icon" aria-hidden="true"></i>
									<span><?php echo esc_html( implode( ' | ', $mia_aesthetics_output ) ); ?></span>
								</div>
							</div>
						<?php endif; ?>

						<?php // $mia_aesthetics_location_maps_link already set above ?>
						<?php if ( null !== $mia_aesthetics_location_maps_link && '' !== $mia_aesthetics_location_maps_link ) : ?>
							<div class="location-directions">
								<a href="<?php echo esc_url( $mia_aesthetics_location_maps_link ); ?>" class="location-map-link" target="_blank" rel="noopener">
									<i class="fas fa-map-marker-alt location-icon" aria-hidden="true"></i> Get Directions
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-lg-6 ps-lg-5">
					<?php
					// Get video details from linked main location.
					if ( null !== $mia_aesthetics_linked_location ) {
						$mia_aesthetics_video_info = mia_get_video_field( $mia_aesthetics_linked_location->ID );
					} else {
						// Fallback to current page.
						$mia_aesthetics_video_info = mia_get_video_field();
					}
					$mia_aesthetics_video_id      = $mia_aesthetics_video_info['video_id'] ?? '';
					$mia_aesthetics_thumbnail_url = $mia_aesthetics_video_info['thumbnail'] ?? '';

					// Build YouTube embed URL from ID.
					$mia_aesthetics_embed_url = '';
					if ( '' !== $mia_aesthetics_video_id ) {
						$mia_aesthetics_embed_url = 'https://www.youtube.com/embed/' . $mia_aesthetics_video_id;
					}
					?>

					<!-- Video container - only show if we have video ID and thumbnail -->
					<?php if ( '' !== $mia_aesthetics_video_id && '' !== $mia_aesthetics_thumbnail_url ) : ?>
					<div class="sidebar-section border-radius-none">
						<div class="ratio ratio-16x9">
							<div class="video-thumbnail" data-embed-url="<?php echo esc_url( $mia_aesthetics_embed_url ); ?>">
								<img 
									src="<?php echo esc_url( $mia_aesthetics_thumbnail_url ); ?>" 
									alt="<?php echo esc_attr( get_the_title() ); ?> Video Thumbnail" 
									class="img-fluid object-fit-cover"
									loading="lazy"
									width="640"
									height="360"
								/>
								<button class="video-play-button" aria-label="Play video about <?php echo esc_attr( get_the_title() ); ?>">
									<i class="fa-solid fa-play" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<article class="location-article">
		<div class="container">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<div class="location-content">
					<h2 class="section-title text-center">Career Opportunities</h2>
					<?php the_content(); ?>

					<!-- High Level Dept Breakdown -->
					<div class="dept-breakdown mt-5">
						<h3 class="mb-4">High Level Department Breakdown</h3>

						<div class="accordion" id="deptAccordion">
							<!-- Outpatient Surgical Centers -->
							<div class="accordion-item mb-3">
								<h2 class="accordion-header" id="headingOutpatient">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOutpatient" aria-expanded="false" aria-controls="collapseOutpatient">
										<i class="fas fa-hospital-user me-3" aria-hidden="true"></i>
										Positions at our Outpatient Surgical Centers
									</button>
								</h2>
								<div id="collapseOutpatient" class="accordion-collapse collapse" aria-labelledby="headingOutpatient">
									<div class="accordion-body">
										<p class="lead">Our frontline team members who directly aid patients throughout the surgical journey. Check openings at our clinic and join the team responsible for providing moments of Mia magic through each interaction.</p>

										<div class="row g-4 mt-3">
											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Medical Assistants</h4>
													<p class="job-function-description">Medical Assistants at Mia Aesthetics play a vital role in supporting patient care and the clinical team. They assist with pre- and post-op care, take vitals, prep exam rooms, and help educate patients throughout their recovery. This role combines clinical skill with compassion in a fast-paced, aesthetics-driven setting.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">PRNs</h4>
													<p class="job-function-description">PRNs at Mia Aesthetics provide flexible, high-quality support wherever needed—pre-op, post-op, or during surgery. This role suits experienced professionals who thrive in a fast-paced setting and value flexible, team-based work.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Registered Nurses</h4>
													<p class="job-function-description">Registered Nurses at Mia Aesthetics support patients through every step of surgery—from pre-op assessments to post-op recovery. They provide education, assist in procedures, and ensure safe, comfortable healing. Clinical skill, communication, and compassion are essential.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Field Trainers</h4>
													<p class="job-function-description">Field Trainers at Mia Aesthetics onboard and train new staff across clinics, ensuring consistent protocols and high standards in care and service. They serve as mentors, combining clinical expertise with strong communication and a passion for teaching.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">RN Clinical Trainers</h4>
													<p class="job-function-description">RN Clinical Trainers at Mia Aesthetics onboard and educate nursing staff, ensuring consistent, high-quality care. With strong leadership and clinical expertise, they lead training, reinforce safety standards, and promote a culture of continuous learning.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Clinical Operations Specialists</h4>
													<p class="job-function-description">Clinical Operations Specialists at Mia Aesthetics are the first point of contact, managing check-ins, scheduling, and records with professionalism and care. Their attention to detail and strong communication keep the clinic running smoothly and patients supported throughout their visit.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">OR Circulators</h4>
													<p class="job-function-description">OR Circulators at Mia Aesthetics ensure smooth, safe surgeries by prepping the OR, supporting the surgical team, and advocating for patients. This fast-paced role demands precision, communication, and attention to detail.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">In-Clinic Surgical Coordinators</h4>
													<p class="job-function-description">In-Clinic Surgical Coordinators at Mia Aesthetics guide patients through their surgical journey, managing documentation, scheduling, and communication. This fast-paced role requires strong multitasking, attention to detail, and compassionate support.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Surgical Assistants</h4>
													<p class="job-function-description">Surgical Assistants at Mia Aesthetics support surgeons during procedures by prepping the OR, positioning patients, and handling instruments. This hands-on role demands clinical skill, focus, and a commitment to excellent patient care.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Social Media Coordinators</h4>
													<p class="job-function-description">As a Social Media Coordinator at Mia Aesthetics, you'll create content, share patient stories, and grow our presence on Instagram, TikTok, and Facebook. This creative, fast-paced role blends storytelling with strategy to connect and inspire.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Clinic Administrator/Practice Manager</h4>
													<p class="job-function-description">Clinic Administrator/Practice Manager leads daily operations of the plastic surgery center, managing staff, scheduling, billing, and compliance to ensure a smooth, patient-focused experience. Strong leadership, organization, and customer service skills are key.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Clinical Assistants</h4>
													<p class="job-function-description">Clinical Assistants at Mia Aesthetics support patient care by prepping rooms, assisting in procedures, and ensuring comfort. This role requires organization, attention to detail, and compassion to help deliver a smooth clinical experience.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Licensed Massage Therapists</h4>
													<p class="job-function-description">As a Licensed Massage Therapist at Mia Aesthetics, you support post-op recovery through lymphatic massages that reduce swelling and promote healing. You help patients feel cared for and comfortable during their transformation journey.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Cleaning Personnel</h4>
													<p class="job-function-description">Cleaning Personnel at Mia Aesthetics ensure a clean, safe, and welcoming environment by sanitizing patient rooms, surgical areas, and common spaces. Their attention to detail is key to maintaining high hygiene standards and supporting quality care.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">OR Sterilizers</h4>
													<p class="job-function-description">OR Sterilizers at Mia Aesthetics ensure surgical safety by cleaning and sterilizing instruments to meet strict infection control standards. Their precision and dedication are key to smooth operations and positive patient outcomes.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Training Positions -->
							<div class="accordion-item mb-3">
								<h2 class="accordion-header" id="headingTraining">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTraining" aria-expanded="false" aria-controls="collapseTraining">
										<i class="fas fa-chalkboard-teacher me-3" aria-hidden="true"></i>
										Training Positions
									</button>
								</h2>
								<div id="collapseTraining" class="accordion-collapse collapse" aria-labelledby="headingTraining">
									<div class="accordion-body">
										<p class="lead">Help onboard and train new staff across Mia locations nationwide. Join the team ensuring consistent protocols and high standards in care and service. Serve as mentor, combining strong coaching, communication, and teaching abilities with a passion for helping others.</p>

										<div class="row g-4 mt-3">
											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Field Trainers</h4>
													<p class="job-function-description">Field Trainers at Mia Aesthetics onboard and train new staff across clinics, ensuring consistent protocols and high standards in care and service. They serve as mentors, combining clinical expertise with strong communication and a passion for teaching.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">RN Clinical Trainers</h4>
													<p class="job-function-description">RN Clinical Trainers at Mia Aesthetics onboard and educate nursing staff, ensuring consistent, high-quality care. With strong leadership and clinical expertise, they lead training, reinforce safety standards, and promote a culture of continuous learning.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- CX Center of Excellence -->
							<div class="accordion-item mb-3">
								<h2 class="accordion-header" id="headingCX">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCX" aria-expanded="false" aria-controls="collapseCX">
										<i class="fas fa-headset me-3" aria-hidden="true"></i>
										CX Center of Excellence
									</button>
								</h2>
								<div id="collapseCX" class="accordion-collapse collapse" aria-labelledby="headingCX">
									<div class="accordion-body">
										<p class="lead">Our behind-the-scenes teams that support our patients throughout their surgical journey. Check out our customer support openings and become a caring team member supporting internal and external communications needed to make our patients dreams become reality.</p>

										<div class="row g-4 mt-3">
											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">CX Center of Excellence</h4>
													<p class="job-function-description">Working at the Mia Aesthetics CX Center of Excellence makes you the friendly voice that supports and guides our patients along their journey. You will be a helpful point of contact for patients and the backbone of our support center. Strong communications skills, attention to detail, and a passion for servicing others are key to success in these roles.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Social Media Coordinators</h4>
													<p class="job-function-description">As a Social Media Coordinator at Mia Aesthetics, you'll create content, share patient stories, and grow our presence on Instagram, TikTok, and Facebook. This creative, fast-paced role blends storytelling with strategy to connect and inspire.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Labs and Clearance Coordinators</h4>
													<p class="job-function-description">Labs and Clearance Coordinators at Mia Aesthetics manage pre-op clearances by reviewing labs, coordinating tests, and ensuring all documentation is complete. Their organization and communication help keep surgeries safe and on schedule.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Medical Compliance Specialists</h4>
													<p class="job-function-description">Medical Compliance Specialists at Mia Aesthetics ensure clinical practices meet regulatory standards through audits, safety checks, and policy enforcement. Their attention to detail and regulatory knowledge help protect patient safety and care quality.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- People Leader Positions -->
							<div class="accordion-item mb-3">
								<h2 class="accordion-header" id="headingPeople">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePeople" aria-expanded="false" aria-controls="collapsePeople">
										<i class="fas fa-users-cog me-3" aria-hidden="true"></i>
										People Leader Positions
									</button>
								</h2>
								<div id="collapsePeople" class="accordion-collapse collapse" aria-labelledby="headingPeople">
									<div class="accordion-body">
										<p class="lead">Manager roles that directly impact team members play a mission critical role in accomplishing our goal in delivering the highest quality of plastic surgery at affordable prices. Check out our people leader roles, including departments such as marketing, finance, compliance, operations, etc.</p>

										<div class="row g-4 mt-3">
											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Clinic Administrator/Practice Manager</h4>
													<p class="job-function-description">Clinic Administrator/Practice Manager leads daily operations of the plastic surgery center, managing staff, scheduling, billing, and compliance to ensure a smooth, patient-focused experience. Strong leadership, organization, and customer service skills are key.</p>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="job-function-card h-100 p-4 border rounded">
													<h4 class="job-function-title">Director of Operations</h4>
													<p class="job-function-description">As Director of Operations at Mia Aesthetics, you lead clinic teams, streamline processes, and ensure top-quality patient care. It's a fast-paced, strategic role focused on driving performance and growth.</p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</article>

	<!-- Benefits Section - Full width background -->
	<section class="benefits-section">
		<div class="container">
			<div class="benefits-top-content mb-5">
				<div class="row align-items-center">
					<!-- Left Column: Headings and Paragraphs -->
					<div class="col-lg-6">
						<div class="benefits-header">
							<p class="section-tagline">What We Offer</p>
							<h3 class="benefits-title">Benefits</h3>
							<p class="benefits-subtitle">At Mia Aesthetics, we support our team and their families with benefits that care for their physical, mental, and financial well-being.</p>
						</div>
					</div>

					<!-- Right Column: Founders Quote -->
					<div class="col-lg-6">
						<div class="founders-quote-wrapper">
							<blockquote class="founders-quote">
								<div class="quote-icon">
									<i class="fas fa-quote-left" aria-hidden="true"></i>
								</div>
								<p class="quote-text">"Our growth has taught us many things, and we have learned that building a strong brand stands on the execution of excellent customer service driven by employee happiness."</p>
								<footer class="quote-attribution">
									<span class="attribution-line"></span>
									<span class="attribution-text">Our Founders</span>
								</footer>
							</blockquote>
						</div>
					</div>
				</div>
			</div>

			<div class="row g-4">
				<!-- Health & Wellness -->
				<div class="col-lg-3 col-md-6">
					<div class="benefit-card h-100 p-4 text-center">
						<div class="benefit-icon mb-3">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Health-Wellness.svg' ) ); ?>" alt="Health & Wellness" class="benefit-svg-icon">
						</div>
						<h4 class="benefit-title">Health & Wellness</h4>
						<ul class="benefit-list">
							<li><strong>Medical, Dental, and Vision:</strong> Available to employees, their dependents, and domestic partners</li>
							<li><strong>Additional Benefits:</strong> Short-Term Disability, Long-Term Disability, Dependent Care Flexible Spending Account, Healthcare Flexible Spending Account</li>
							<li><strong>Employee Assistance Program (EAP):</strong> Available to all employees</li>
						</ul>
					</div>
				</div>

				<!-- Time To Unwind -->
				<div class="col-lg-3 col-md-6">
					<div class="benefit-card h-100 p-4 text-center">
						<div class="benefit-icon mb-3">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/PTO.svg' ) ); ?>" alt="Time To Unwind" class="benefit-svg-icon">
						</div>
						<h4 class="benefit-title">Time To Unwind</h4>
						<ul class="benefit-list">
							<li><strong>Paid Time Off (PTO):</strong> Accrue from date of hire; PTO for vacation, personal appointments, illness, and other events</li>
							<li><strong>Paid Holidays:</strong> Recognized company holidays throughout the calendar year</li>
							<li><strong>Paid Leave:</strong> As dictated by applicable state law</li>
						</ul>
					</div>
				</div>

				<!-- Financial Well-Being -->
				<div class="col-lg-3 col-md-6">
					<div class="benefit-card h-100 p-4 text-center">
						<div class="benefit-icon mb-3">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Financial.svg' ) ); ?>" alt="Financial Well-Being" class="benefit-svg-icon">
						</div>
						<h4 class="benefit-title">Financial Well-Being</h4>
						<ul class="benefit-list">
							<li><strong>Competitive Pay</strong></li>
							<li><strong>401k with employer match & no vesting schedule</strong></li>
						</ul>
					</div>
				</div>

				<!-- Other Perks -->
				<div class="col-lg-3 col-md-6">
					<div class="benefit-card h-100 p-4 text-center">
						<div class="benefit-icon mb-3">
							<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Other-Perks.svg' ) ); ?>" alt="Other Perks" class="benefit-svg-icon">
						</div>
						<h4 class="benefit-title">Other Perks</h4>
						<ul class="benefit-list">
							<li><strong>Discounts on service offerings</strong></li>
							<li><strong>Celebratory company-wide event(s)</strong></li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Benefits CTA -->
			<div class="text-center mt-5">
				<a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>" class="btn btn-primary btn-lg">
					Join Our Team <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</section>

	<!-- Team Section -->
	<section class="team-section">
		<div class="container">
			<h2 class="section-title text-center">Meet the <?php echo esc_html( get_the_title() ); ?> Team</h2>
			<div class="row g-4 justify-content-center">
				<?php
				// Get the linked main location from ACF relationship field.
				$mia_aesthetics_main_location = get_field( 'linked_main_location' );

				if ( null !== $mia_aesthetics_main_location ) {
					// We have a linked location, use its ID to find surgeons.
					$mia_aesthetics_location_id_for_surgeons = $mia_aesthetics_main_location->ID;
				} else {
					// No linked location - this shouldn't happen in production.
					// but we'll handle it gracefully.
					$mia_aesthetics_location_id_for_surgeons = 0; // This will return no surgeons.
				}

				$mia_aesthetics_args = array(
					'post_type'      => 'surgeon',
					'posts_per_page' => -1, // Get all surgeons.
					'orderby'        => 'menu_order', // Use WordPress menu order.
					'order'          => 'ASC',
					// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'meta_query'     => array(
						array(
							'key'     => 'surgeon_location',
							'value'   => $mia_aesthetics_location_id_for_surgeons,
							'compare' => '=',
						),
					),
				);

				$mia_aesthetics_surgeons = new WP_Query( $mia_aesthetics_args );

				if ( $mia_aesthetics_surgeons->have_posts() ) :
					while ( $mia_aesthetics_surgeons->have_posts() ) :
						$mia_aesthetics_surgeons->the_post();
						?>
						
						<div class="col-12 col-md-6 col-lg-3">
							<div class="surgeon-card text-center">
								<?php
								$mia_aesthetics_surgeon_headshot_id = get_field( 'surgeon_headshot' );
								if ( null !== $mia_aesthetics_surgeon_headshot_id && is_numeric( $mia_aesthetics_surgeon_headshot_id ) ) :
									?>
									<img src="
									<?php
									$headshot_url = wp_get_attachment_image_url( (int) $mia_aesthetics_surgeon_headshot_id, 'medium' );
									echo esc_url( false !== $headshot_url ? $headshot_url : '' );
									?>
									"
										alt="<?php echo esc_attr( get_the_title() ); ?> Headshot" />
								<?php elseif ( has_post_thumbnail() ) : ?>
									<?php $thumbnail_url = get_the_post_thumbnail_url(); ?>
									<img src="<?php echo esc_url( false !== $thumbnail_url ? $thumbnail_url : '' ); ?>" 
									alt="<?php echo esc_attr( get_the_title() ); ?>" />
								<?php endif; ?>
								
								<div class="surgeon-info">
									<h3><?php echo esc_html( get_the_title() ); ?></h3>
									<p>Plastic Surgeon</p>
									<?php $surgeon_permalink = get_permalink(); ?>
									<a href="<?php echo esc_url( false !== $surgeon_permalink ? $surgeon_permalink : '#' ); ?>" class="btn btn-primary btn-sm">
										View Profile <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
						
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<div class="col-12">
						<div class="text-center">
							<p>Our team information will be available soon. In the meantime, 
							feel free to reach out to learn more about working with us.</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	$faq_output = mia_aesthetics_display_faqs();
	if ( '' !== $faq_output ) :
		?>
	<section class="location-faq-section">
		<div class="container">
			<?php echo wp_kses_post( $faq_output ); ?>
		</div>
	</section>
	<?php endif; ?>

</main>

<?php
// Add careers CTA component for careers locations page.
require get_template_directory() . '/components/careers-cta.php';
?>

<?php get_footer(); ?>
