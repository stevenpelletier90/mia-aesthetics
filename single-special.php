<?php
/**
 * Template for displaying single special posts
 */
get_header(); ?>

<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<main id="main-content" role="main">
    <?php while (have_posts()) : the_post(); ?>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <!-- Banner Image -->
                    <?php 
                    $banner_image = get_field('banner_image');
                    if ($banner_image): ?>
                        <div class="special-hero-image">
                            <img src="<?php echo esc_url($banner_image['url']); ?>" 
                                 srcset="<?php echo esc_attr($banner_image['sizes']['medium']); ?> 300w,
                                         <?php echo esc_attr($banner_image['sizes']['large']); ?> 1024w,
                                         <?php echo esc_url($banner_image['url']); ?> <?php echo $banner_image['width']; ?>w"
                                 sizes="(max-width: 576px) 100vw, (max-width: 992px) 90vw, 800px"
                                 alt="<?php echo esc_attr($banner_image['alt'] ?: get_the_title()); ?>" 
                                 class="img-fluid w-100" 
                                 loading="lazy">
                        </div>
                    <?php else: ?>
                        <div class="special-hero-image">
                            1200x200 Hero Image Placeholder
                        </div>
                    <?php endif; ?>
                    
                    <div class="card shadow-sm consultation-card" role="complementary" aria-label="Free Virtual Consultation">
                        <div class="card-body p-4">                            
                            <div class="gf-wrapper" aria-label="Free Virtual Consultation Form">
                                <?php gravity_form(1, false, false, false, false, true); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="special-disclaimer">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>