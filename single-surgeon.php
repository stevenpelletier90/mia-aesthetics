<?php
/**
 * Template for displaying single surgeon
 */
get_header(); ?>
<main tabindex="0">
<?php mia_breadcrumbs(); ?>

    <div class="surgeon-hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 col-lg-2">
                    <?php 
                    // Get surgeon headshot
                    $headshot_id = get_field('surgeon_headshot');
                    if($headshot_id): 
                        // Get image URL from ID
                        $headshot_url = wp_get_attachment_image_url($headshot_id, 'full');
                        ?>
                        <div class="surgeon-headshot">
                            <img 
                                src="<?php echo esc_url($headshot_url); ?>" 
                                alt="<?php echo esc_attr(get_the_title()); ?>" 
                                class="img-fluid rounded-circle"
                                fetchpriority="high"
                                width="200"
                                height="200"
                                sizes="(max-width: 767px) 120px, 200px"
                            >
                        </div>
                    <?php else: ?>
                        <div class="surgeon-headshot surgeon-headshot-placeholder">
                            <div class="placeholder-circle">
                                <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-9 col-lg-10">
                    <div class="surgeon-hero-content">
                        <h1><?php echo get_the_title(); ?></h1>
                        <?php 
                        // Get surgeon location
                        $location = get_field('surgeon_location');
                        if($location): 
                            // Get location title and remove state abbreviation (e.g., ", IL")
                            $location_title = get_the_title($location);
                            $location_title = preg_replace('/, [A-Z]{2}$/', '', $location_title);
                            // Get location URL
                            $location_url = get_permalink($location);
                        ?>
                            <p class="surgeon-location">Plastic Surgeon at <a href="<?php echo esc_url($location_url); ?>"> <?php echo $location_title; ?></a></p>
                        <?php endif; ?>
                        
                        <!-- Instagram Link Placeholder -->
                        <div class="surgeon-social mt-3">
                            <a href="#" class="social-link" target="_blank" rel="noopener" aria-label="Follow <?php echo esc_attr(get_the_title()); ?> on Instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Follow on Instagram</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Surgeon Navigation - Only visible on mobile -->
    <div id="surgeon-tabs" class="surgeon-mobile-nav d-md-none sticky-top">
        <div class="surgeon-nav-buttons">
            <a href="#surgeon-about" class="surgeon-nav-btn">About</a>
            <a href="#surgeon-specialities" class="surgeon-nav-btn">Specialities</a>
            <a href="#surgeon-before-after" class="surgeon-nav-btn">Before & After</a>
        </div>
    </div>

    <div class="surgeon-content-section">
        <div class="container">
            <?php 
            // Get video fields from the "video_details" group
            $video_details = get_field('video_details');
            $video_id = '';
            $video_thumbnail = '';
            $thumbnail_url = '';
            $embed_url = '';
            
            // Check if there's a video_id in the ACF field
            if(!empty($video_details) && isset($video_details['video_id'])) {
                $video_id = $video_details['video_id'];
                
                // Get YouTube embed URL from video ID
                $embed_url = $video_id ? 'https://www.youtube.com/embed/' . $video_id : '';
                
                // Get video thumbnail
                if (isset($video_details['video_thumbnail'])) {
                    $video_thumbnail = $video_details['video_thumbnail'];
                    
                    // Handle the video_thumbnail which returns an array with all image data
                    if ($video_thumbnail && is_array($video_thumbnail)) {
                        // The URL is directly accessible in the array
                        $thumbnail_url = $video_thumbnail['url'];
                    } elseif ($video_thumbnail && is_numeric($video_thumbnail)) {
                        // Handle the case where it might be just an ID
                        $thumbnail_url = wp_get_attachment_image_url($video_thumbnail, 'full');
                    }
                }
            }
            
            // Only display video section if we have a video ID
            if($video_id): 
            ?>
            <!-- Video Section (visible on mobile before content) -->
            <div class="row d-lg-none">
                <div class="col-12">
                    <div class="sidebar-section" style="border-radius: 0;">
                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="<?php echo esc_url($embed_url); ?>" 
                                title="<?php echo esc_attr(get_the_title()); ?> Video"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                allowfullscreen
                                loading="lazy"
                            ></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- About Section -->
                    <section id="surgeon-about">
                        <h2 class="section-title">About <?php echo get_the_title(); ?></h2>
                        <?php the_content(); ?>
                    </section>

                    <!-- Specialities Section -->
                    <section id="surgeon-specialities">
                        <h2 class="section-title">Specialities</h2>
                        <?php 
                        // Get specialties from the ACF relationship field
                        $specialties = get_field('specialties');
                        
                        // Only show specialties if they exist
                        if($specialties && !empty($specialties)): 
                        ?>
                        <div class="row">
                            <?php foreach($specialties as $specialty): ?>
                                <div class="col-md-6 specialty-card-wrapper">
                                    <a href="<?php echo get_permalink($specialty->ID); ?>" class="card cta-card text-decoration-none text-dark d-block h-100">
                                        <div class="card-body">
                                            <h3 class="h5 card-title"><?php echo get_the_title($specialty->ID); ?></h3>
                                            <?php 
                                            // Get the excerpt, fallback to a portion of the content if no excerpt exists
                                            $excerpt = get_the_excerpt($specialty->ID);
                                            if(empty($excerpt)) {
                                                $content = get_post_field('post_content', $specialty->ID);
                                                $excerpt = wp_trim_words($content, 20, '...');
                                            }
                                            ?>
                                            <p class="card-text small"><?php echo $excerpt; ?></p>
                                            <div class="text-end">
                                                <span>Learn More <i class="fa-solid fa-arrow-right procedure-arrow" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <p>No specialities available for this surgeon.</p>
                        <?php endif; ?>
                    </section>
                </div>
                
                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    <div class="surgeon-sidebar">
                        <div class="sidebar-ctas">
                        <?php if($video_id && $thumbnail_url): ?>
                        <!-- Video Section (visible only on desktop) -->
                        <div class="sidebar-section d-none d-lg-block" style="border-radius: 0;">
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

                        <!-- Before & After Gallery Section -->
                        <div class="cta-card mb-0" id="surgeon-before-after">
                            <div class="cta-card__content">
                                <div class="before-after-preview mb-3">
                                    <img 
                                        src="https://placehold.co/400x200/f8f9fa/cccccc?text=Before+%26+After" 
                                        class="img-fluid rounded" 
                                        alt="Before and After Gallery Preview"
                                        loading="lazy"
                                        width="400" 
                                        height="200"
                                    >
                                </div>
                                <h3 class="cta-card__title">Before & After Gallery</h3>
                                <p class="cta-card__text">View real patient results from procedures performed by <?php echo get_the_title(); ?>.</p>
                                <a href="#" class="mia-button" data-variant="gold" data-size="sm">
                                    View Gallery <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                            
                            <!-- Additional CTA Cards -->
                            <div class="cta-card">
                                <div class="cta-card__content">
                                    <h3 class="cta-card__title">Your Surgical Journey</h3>
                                    <p class="cta-card__text">Learn about the consultation process and what to expect.</p>
                                    <a href="#" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        Learn More <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="cta-card">
                                <div class="cta-card__content">
                                    <h3 class="cta-card__title">Out of Town Patients</h3>
                                    <p class="cta-card__text">Special accommodations for patients traveling for surgery.</p>
                                    <a href="#" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        Travel Info <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="cta-card">
                                <div class="cta-card__content">
                                    <h3 class="cta-card__title">Financing Options</h3>
                                    <p class="cta-card__text">Flexible payment plans to make your procedure affordable.</p>
                                    <a href="/financing" class="mia-button" data-variant="gold-outline" data-size="sm">
                                        View Options <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
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
        <div class="container">
            <?php echo display_page_faqs(); ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
