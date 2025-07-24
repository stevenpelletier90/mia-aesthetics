<?php
/**
 * Template for displaying single surgeon
 */
get_header(); ?>
<main tabindex="0">
<?php mia_breadcrumbs(); ?>

    <!-- New Layout Design -->
    <div class="surgeon-header-band">
        <div class="surgeon-fluid-container">
            <div class="surgeon-header-content">
                <p class="surgeon-specialty">Plastic Surgeon</p>
                <h1 class="surgeon-name"><?php echo get_the_title(); ?></h1>
            </div>
        </div>
    </div>

    <div class="surgeon-main-content">
        <div class="surgeon-fluid-container">
            <div class="row">
                <!-- Left Column: Photo and Info -->
                <div class="col-lg-4">
                    <div class="surgeon-profile-card">
                        <?php 
                        // Get surgeon headshot
                        $headshot_id = get_field('surgeon_headshot');
                        if($headshot_id): 
                            $headshot_url = wp_get_attachment_image_url($headshot_id, 'large');
                            ?>
                            <div class="surgeon-photo">
                                <img 
                                    src="<?php echo esc_url($headshot_url); ?>" 
                                    alt="<?php echo esc_attr(get_the_title()); ?>" 
                                    class="img-fluid"
                                >
                            </div>
                        <?php else: ?>
                            <div class="surgeon-photo surgeon-photo-placeholder">
                                <div class="placeholder-content">
                                    <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Social Links -->
                        <?php 
                        $instagram_url = get_field('instagram_url');
                        if($instagram_url): ?>
                        <div class="surgeon-social-links">
                            <a href="<?php echo esc_url($instagram_url); ?>" class="mia-button" data-variant="gold-outline" data-size="sm" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                                <span>Follow on Instagram</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Video Section -->
                        <?php 
                        $video_details = get_field('video_details');
                        if(!empty($video_details) && isset($video_details['video_id'])): 
                            $video_id = $video_details['video_id'];
                            $embed_url = 'https://www.youtube.com/embed/' . $video_id;
                            $thumbnail_url = '';
                            
                            if (isset($video_details['video_thumbnail'])) {
                                $video_thumbnail = $video_details['video_thumbnail'];
                                if ($video_thumbnail && is_array($video_thumbnail)) {
                                    $thumbnail_url = $video_thumbnail['url'];
                                } elseif ($video_thumbnail && is_numeric($video_thumbnail)) {
                                    $thumbnail_url = wp_get_attachment_image_url($video_thumbnail, 'full');
                                }
                            }
                        ?>
                        <div class="surgeon-video-sidebar">
                            <div class="video-wrapper">
                                <div class="ratio ratio-16x9">
                                    <?php if($thumbnail_url): ?>
                                    <div class="video-thumbnail" data-embed-url="<?php echo esc_url($embed_url); ?>">
                                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="Video thumbnail" class="img-fluid">
                                        <button class="video-play-button" aria-label="Play video">
                                            <i class="fa-solid fa-play"></i>
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <iframe 
                                        src="<?php echo esc_url($embed_url); ?>" 
                                        title="<?php echo esc_attr(get_the_title()); ?> Video"
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowfullscreen
                                    ></iframe>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <h3>Meet Dr. <?php echo get_the_title(); ?></h3>
                        </div>
                        <?php endif; ?>

                        <!-- Location Info -->
                        <?php 
                        $location = get_field('surgeon_location');
                        if($location): 
                            $location_title = get_the_title($location);
                            $location_title = preg_replace('/, [A-Z]{2}$/', '', $location_title);
                            $location_url = get_permalink($location);
                        ?>
                        <div class="surgeon-location-info">
                            <p><i class="fas fa-map-marker-alt"></i> Located at <a href="<?php echo esc_url($location_url); ?>"><?php echo $location_title; ?></a></p>
                        </div>
                        <?php endif; ?>

                        <!-- Simple Call to Action -->
                        <div class="surgeon-simple-cta">
                            <a href="#consultation" class="mia-button" data-variant="gold" data-size="sm">
                                Free Virtual Consultation <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Main Content -->
                <div class="col-lg-8">
                    <div class="surgeon-content">
                        <!-- About Section -->
                        <section class="surgeon-about">
                            <?php while (have_posts()) : the_post(); ?>
                                <?php the_content(); ?>
                            <?php endwhile; ?>
                        </section>


                        <!-- Specialties Section -->
                        <?php 
                        $specialties = get_field('specialties');
                        if($specialties && !empty($specialties)): 
                        ?>
                        <section class="surgeon-specialties">
                            <h2>Areas of Expertise</h2>
                            <div class="row">
                                <?php foreach($specialties as $specialty): ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="specialty-card">
                                            <h3><?php echo get_the_title($specialty->ID); ?></h3>
                                            <?php 
                                            $excerpt = get_the_excerpt($specialty->ID);
                                            if(empty($excerpt)) {
                                                $content = get_post_field('post_content', $specialty->ID);
                                                $excerpt = wp_trim_words($content, 20, '...');
                                            }
                                            ?>
                                            <p><?php echo $excerpt; ?></p>
                                            <a href="<?php echo get_permalink($specialty->ID); ?>" class="mia-button" data-variant="gold-outline" data-size="sm">
                                                Learn More <i class="fa-solid fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                        <?php endif; ?>


                        <!-- Three Separate CTA Sections -->
                        <div class="surgeon-cta-sections">
                            <!-- Surgical Journey Section -->
                            <section class="surgeon-cta-card">
                                <div class="cta-card-content">
                                    <h3>Your Surgical Journey</h3>
                                    <p>Learn about the consultation process and what to expect from your procedure with Dr. <?php echo get_the_title(); ?>.</p>
                                    <a href="https://miaaesthetics.com/patient-resources/surgical-journey/" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        Learn More <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </section>

                            <!-- Out of Town Patients Section -->
                            <section class="surgeon-cta-card">
                                <div class="cta-card-content">
                                    <h3>Out of Town Patients</h3>
                                    <p>Special accommodations and support for patients traveling from out of town for their procedure.</p>
                                    <a href="https://miaaesthetics.com/out-of-town-patients/" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        Travel Info <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </section>

                            <!-- Financing Section -->
                            <section class="surgeon-cta-card">
                                <div class="cta-card-content">
                                    <h3>Financing Options</h3>
                                    <p>Flexible payment plans and financing solutions to make your aesthetic goals affordable.</p>
                                    <a href="https://miaaesthetics.com/financing/" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        View Options <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    $faq_section = get_field('faq_section');
    if($faq_section && !empty($faq_section['faqs'])): ?>
    <section class="faq-section">
        <div class="surgeon-fluid-container">
            <?php echo display_page_faqs(); ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>