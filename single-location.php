<?php
get_header();
?>

<main>
<?php mia_breadcrumbs(); ?>

    <section class="location-header py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-3"><?php echo get_the_title(); ?></h1>
                    <div class="location-intro mb-4">
                        <p>Welcome to our <?php 
                        $location_title = get_the_title();
                        $location_title = str_replace('Mia Aesthetics', '', $location_title);
                        echo trim($location_title); 
                        ?> location. Our state-of-the-art facility offers a wide range of plastic surgery and aesthetic procedures with a team of experienced surgeons dedicated to helping you achieve your aesthetic goals.</p>
                    </div>
                    <div class="location-info mb-4">
<?php
                        $location_map = get_field('location_map');
                        
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

                        <?php $phone_number = get_field('phone_number'); ?>
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
                        if (have_rows('business_hours')) {
                            while (have_rows('business_hours')): the_row();
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

                        <?php $location_maps_link = get_field('location_maps_link'); ?>
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
                    // Get video details from ACF
                    $video_info = mia_get_video_field();
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
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="col-md-5">
                    <div class="sidebar-ctas">
                        <!-- City Guide CTA -->
                        <div class="cta-card cta-card--dark mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Explore <?php echo str_replace('Mia Aesthetics', '', get_the_title()); ?></h3>
                                <p class="cta-card__text">Discover the best of our city while you're here for your procedure.</p>
                                <a href="#" class="mia-button" data-variant="gold" data-size="sm">
                                    City Guide <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Financing CTA -->
                        <div class="cta-card cta-card--gold mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Financing Options</h3>
                                <p class="cta-card__text">Learn about our flexible payment plans and financing solutions.</p>
                                <a href="/financing" class="mia-button" data-variant="white" data-size="sm">
                                    Learn More <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Procedures CTA -->
                        <div class="cta-card cta-card--primary mb-4">
                            <div class="cta-card__content">
                                <h3 class="cta-card__title">Our Procedures</h3>
                                <p class="cta-card__text">Browse our complete range of plastic surgery and aesthetic treatments.</p>
                                <a href="/procedures" class="mia-button" data-variant="gold-outline" data-size="sm">
                                    View All <i class="fa-solid fa-arrow-right"></i>
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
            <h2 class="section-title text-center mb-5">Our <?php echo get_the_title(); ?> Team</h2>
            <div class="row g-4 justify-content-center">
                <?php
                $args = array(
                    'post_type' => 'surgeon',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => 'surgeon_location',
                            'value' => get_the_ID(),
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
                endif; ?>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">            
            <?php echo display_page_faqs(); ?>           
        </div>
    </section>

<?php get_footer(); ?>
