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
                        
                        if ($location_map):
                            $street = ($location_map['street_number'] ?? '') . ' ' . ($location_map['street_name'] ?? '');
                            $city = $location_map['city'] ?? '';
                            $state = $location_map['state_short'] ?? '';
                            $zip = $location_map['post_code'] ?? '';
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
                        <?php endif; ?>

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
            <div class="row gx-5">
                <div class="col-md-7">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="location-content">
                            <h2>Career Opportunities</h2>
                            <?php the_content(); ?>
                            
                            <!-- Job Listings Section -->
                            <div class="job-listings mt-5">
                                <h3>Current Openings</h3>
                                
                                <?php
                                // Check if there are any job listings (can be added via ACF or custom fields)
                                $job_listings = get_field('job_listings');
                                if ($job_listings && have_rows('job_listings')):
                                ?>
                                    <div class="job-list">
                                        <?php while (have_rows('job_listings')): the_row(); 
                                            $job_title = get_sub_field('job_title');
                                            $job_type = get_sub_field('job_type');
                                            $job_description = get_sub_field('job_description');
                                            $apply_link = get_sub_field('apply_link');
                                        ?>
                                            <div class="job-item mb-4 p-4 border rounded">
                                                <h4><?php echo esc_html($job_title); ?></h4>
                                                <?php if ($job_type): ?>
                                                    <span class="job-type badge bg-primary mb-2"><?php echo esc_html($job_type); ?></span>
                                                <?php endif; ?>
                                                
                                                <?php if ($job_description): ?>
                                                    <p><?php echo esc_html($job_description); ?></p>
                                                <?php endif; ?>
                                                
                                                <?php if ($apply_link): ?>
                                                    <a href="<?php echo esc_url($apply_link); ?>" class="mia-button" data-variant="gold" data-size="sm">
                                                        Apply Now <i class="fa-solid fa-arrow-right"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-jobs-message p-4 bg-light rounded">
                                        <p>We don't have any open positions at this location right now, but we're always looking for talented individuals to join our team. Please check back regularly or submit your resume for future opportunities.</p>
                                        <a href="/careers" class="mia-button" data-variant="gold" data-size="sm">
                                            General Application <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="col-md-5">
                    <div class="sidebar-ctas">
                        <!-- Apply CTA -->
                        <div class="cta-card cta-card--gold mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Ready to Apply?</h3>
                                <p class="cta-card__text">Submit your application and join our mission of transforming lives.</p>
                                <a href="/careers" class="mia-button" data-variant="white" data-size="sm">
                                    Apply Now <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Benefits CTA -->
                        <div class="cta-card cta-card--primary mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Employee Benefits</h3>
                                <p class="cta-card__text">Discover our comprehensive benefits package and career growth opportunities.</p>
                                <a href="/careers#benefits" class="mia-button" data-variant="gold-outline" data-size="sm">
                                    Learn More <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Company Culture CTA -->
                        <div class="cta-card cta-card--dark mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Our Culture</h3>
                                <p class="cta-card__text">Learn about our values, mission, and what makes Mia Aesthetics special.</p>
                                <a href="/careers#culture" class="mia-button" data-variant="gold" data-size="sm">
                                    Our Values <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                
                                <h3><?php the_title(); ?></h3>
                                <p>Plastic Surgeon</p>
                                <a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold" data-size="sm">
                                    View Profile <i class="fa-solid fa-arrow-right"></i>
                                </a>
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

<?php get_footer(); ?>
