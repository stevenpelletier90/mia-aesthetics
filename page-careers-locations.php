<?php
/**
 * Template Name: Careers Locations
 * 
 * Career opportunities page template that adapts the single location layout
 * for displaying job opportunities at specific locations
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main>
<?php mia_breadcrumbs(); ?>

    <section class="location-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-3">Careers at <?php echo get_the_title(); ?></h1>
                    <div class="location-intro mb-4">
                        <p>Join our team at our <?php 
                        $location_title = get_the_title();
                        $location_title = str_replace('Mia Aesthetics', '', $location_title);
                        echo trim($location_title); 
                        ?> location. We're looking for passionate professionals to join our mission of transforming lives through exceptional aesthetic care.</p>
                    </div>
                    <div class="location-info mb-4">
<?php
                        // Get location data from linked main location
                        $linked_location = get_field('linked_main_location');
                        if ($linked_location) {
                            // Use ACF fields from the linked location post
                            $location_map = get_field('location_map', $linked_location->ID);
                            $phone_number = get_field('phone_number', $linked_location->ID);
                            $location_maps_link = get_field('location_maps_link', $linked_location->ID);
                        } else {
                            // Fallback to current page fields
                            $location_map = get_field('location_map');
                            $phone_number = get_field('phone_number');
                            $location_maps_link = get_field('location_maps_link');
                        }
                        
                        if ($location_map) {
                            $street = ($location_map['street_number'] ?? '') . ' ' . ($location_map['street_name'] ?? '');
                            $city = $location_map['city'] ?? '';
                            $state = $location_map['state_short'] ?? '';
                            $zip = $location_map['post_code'] ?? '';
                            
                            // Special handling for locations where Google Maps doesn't populate city correctly
                            // Check if city is empty but we have other address components
                            if (empty($city) && !empty($state)) {
                                // For Brooklyn/NYC addresses, Google sometimes doesn't populate city
                                if ($state === 'NY' && strpos(strtolower($street), 'atlantic') !== false) {
                                    $city = 'Brooklyn';
                                }
                            }
                        ?>
                            <?php if (!empty(trim($street)) || !empty($city) || !empty($state) || !empty($zip)): ?>
                            <div class="location-detail mb-4">
                                <div class="d-flex flex-column">
                                    <?php if (!empty(trim($street))): ?>
                                        <span><?php echo esc_html(trim($street)); ?></span>
                                    <?php endif; ?>
                                    <?php 
                                    $address_line2 = trim($city . ', ' . $state . ' ' . $zip, ', ');
                                    if (!empty($address_line2)): ?>
                                        <span><?php echo esc_html($address_line2); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php } ?>

                        <?php // $phone_number already set above ?>
                        <?php if ($phone_number): ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone location-icon" aria-hidden="true"></i>
                                    <a href="tel:<?php echo esc_attr($phone_number); ?>" class="location-phone">
                                        <?php echo esc_html($phone_number); ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php
                        // Grouped hours of operation (short format)
                        $short_days = array(
                            'Monday' => 'Mon', 'Tuesday' => 'Tue', 'Wednesday' => 'Wed',
                            'Thursday' => 'Thu', 'Friday' => 'Fri', 'Saturday' => 'Sat', 'Sunday' => 'Sun'
                        );
                        $hours_rows = array();
                        // Use business hours from linked location if available
                        $hours_field_location = $linked_location ? $linked_location->ID : false;
                        if (have_rows('business_hours', $hours_field_location)) {
                            while (have_rows('business_hours', $hours_field_location)): the_row();
                                $day = get_sub_field('day');
                                $hours = get_sub_field('hours');
                                if ($day && $hours) {
                                    $hours_rows[] = array('day' => $day, 'hours' => $hours);
                                }
                            endwhile;
                        }
                        $output = array();
                        $n = count($hours_rows);
                        $i = 0;
                        while ($i < $n) {
                            $start = $i;
                            $current_hours = $hours_rows[$i]['hours'];
                            while (
                                $i + 1 < $n &&
                                $hours_rows[$i + 1]['hours'] === $current_hours
                            ) {
                                $i++;
                            }
                            if ($start == $i) {
                                $label = $short_days[$hours_rows[$start]['day']];
                            } else {
                                $label = $short_days[$hours_rows[$start]['day']] . '–' . $short_days[$hours_rows[$i]['day']];
                            }
                            $output[] = $label . ' ' . $current_hours;
                            $i++;
                        }
                        if (!empty($output)) : ?>
                            <div class="location-detail mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock location-icon" aria-hidden="true"></i>
                                    <span><?php echo implode(' | ', $output); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php // $location_maps_link already set above ?>
                        <?php if ($location_maps_link): ?>
                            <div class="location-directions">
                                <a href="<?php echo esc_url($location_maps_link); ?>" class="location-map-link" target="_blank" rel="noopener">
                                    <i class="fas fa-map-marker-alt location-icon" aria-hidden="true"></i> Get Directions
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6 ps-lg-5">
                    <?php
                    // Get video details from linked main location
                    if ($linked_location) {
                        $video_info = mia_get_video_field($linked_location->ID);
                    } else {
                        // Fallback to current page
                        $video_info = mia_get_video_field();
                    }
                    $video_id = $video_info['video_id'] ?? '';
                    $thumbnail_url = $video_info['thumbnail'] ?? '';
                    
                    // Build YouTube embed URL from ID
                    $embed_url = '';
                    if ($video_id) {
                        $embed_url = 'https://www.youtube.com/embed/' . $video_id;
                    }
                    ?>

                    <!-- Video container - only show if we have video ID and thumbnail -->
                    <?php if (!empty($video_id) && !empty($thumbnail_url)): ?>
                    <div class="sidebar-section" style="border-radius: 0;">
                        <div class="ratio ratio-16x9">
                            <div class="video-thumbnail" data-embed-url="<?php echo esc_url($embed_url); ?>">
                                <img 
                                    src="<?php echo esc_url($thumbnail_url); ?>" 
                                    alt="<?php echo esc_attr(get_the_title()); ?> Video Thumbnail" 
                                    class="img-fluid object-fit-cover"
                                    loading="lazy"
                                    width="640"
                                    height="360"
                                />
                                <button class="video-play-button" aria-label="Play video about <?php echo esc_attr(get_the_title()); ?>">
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

    <article class="py-5">
        <div class="container">
            <?php while (have_posts()) : the_post(); ?>
                <div class="location-content">
                    <h2 class="text-center mb-5">Career Opportunities</h2>
                    <?php the_content(); ?>
                    
                    <!-- High Level Dept Breakdown -->
                    <div class="dept-breakdown mt-5">
                        <h3 class="mb-4">High Level Department Breakdown</h3>
                        
                        <div class="accordion" id="deptAccordion">
                            <!-- Outpatient Surgical Centers -->
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="headingOutpatient">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOutpatient" aria-expanded="false" aria-controls="collapseOutpatient">
                                        <i class="fas fa-hospital-user me-3"></i>
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
                                        <i class="fas fa-chalkboard-teacher me-3"></i>
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
                                        <i class="fas fa-headset me-3"></i>
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
                                        <i class="fas fa-users-cog me-3"></i>
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

                    <!-- Benefits Section -->
                    <section class="benefits-section mt-5">
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
                                                <i class="fas fa-quote-left"></i>
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
                                        <i class="fas fa-heart-pulse"></i>
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
                                        <i class="fas fa-calendar-days"></i>
                                    </div>
                                    <h4 class="benefit-title">Time To Unwind</h4>
                                    <ul class="benefit-list">
                                        <li><strong>Paid Time Off (PTO):</strong> Accrue from date of hire; PTO for vacation, personal appointments, illness, and other events.</li>
                                        <li><strong>Paid Holidays:</strong> Recognized company holidays throughout the calendar year</li>
                                        <li><strong>Paid Leave:</strong> As dictated by applicable state law</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Financial Well-Being -->
                            <div class="col-lg-3 col-md-6">
                                <div class="benefit-card h-100 p-4 text-center">
                                    <div class="benefit-icon mb-3">
                                        <i class="fas fa-piggy-bank"></i>
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
                                        <i class="fas fa-gift"></i>
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
                            <a href="/careers" class="mia-button" data-variant="gold" data-size="lg">
                                Join Our Team <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </section>

                </div>
            <?php endwhile; ?>
        </div>
    </article>

    <!-- Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <h2 class="section-title text-center mb-5">Meet the <?php echo get_the_title(); ?> Team</h2>
            <div class="row g-4 justify-content-center">
                <?php
                // Get the linked main location from ACF relationship field
                $main_location = get_field('linked_main_location');
                
                if ($main_location) {
                    // We have a linked location, use its ID to find surgeons
                    $location_id_for_surgeons = $main_location->ID;
                    $location_name = $main_location->post_title;
                } else {
                    // No linked location - this shouldn't happen in production
                    // but we'll handle it gracefully
                    $location_id_for_surgeons = 0; // This will return no surgeons
                    $location_name = get_the_title();
                }
                
                $args = array(
                    'post_type' => 'surgeon',
                    'posts_per_page' => -1, // Get all surgeons
                    'orderby' => 'menu_order', // Use WordPress menu order
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'surgeon_location',
                            'value' => $location_id_for_surgeons,
                            'compare' => '='
                        )
                    )
                );
                
                $surgeons = new WP_Query($args);

                if ($surgeons->have_posts()) :
                    while ($surgeons->have_posts()) : $surgeons->the_post(); ?>
                        
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="surgeon-card text-center">
                                <?php
                                $surgeon_headshot_id = get_field('surgeon_headshot');
                                if($surgeon_headshot_id && is_numeric($surgeon_headshot_id)) : ?>
                                    <img src="<?php echo esc_url(wp_get_attachment_image_url($surgeon_headshot_id, 'medium')); ?>"
                                         alt="<?php the_title(); ?> Headshot" />
                                <?php elseif (has_post_thumbnail()): ?>
                                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" />
                                <?php endif; ?>
                                
                                <div class="surgeon-info">
                                    <h3><?php the_title(); ?></h3>
                                    <p>Plastic Surgeon</p>
                                    <a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold" data-size="sm">
                                        View Profile <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                    <?php endwhile;
                    wp_reset_postdata();
                else: ?>
                    <div class="col-12">
                        <div class="text-center">
                            <p>Our team information will be available soon. In the meantime, feel free to reach out to learn more about working with us.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">            
            <?php echo display_page_faqs(); ?>           
        </div>
    </section>

</main>

<?php get_footer(); ?>
