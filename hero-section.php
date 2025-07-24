<?php
/**
 * Hero Section Template
 * 
 * Clean implementation with proper aspect ratios:
 * - Carousel: 1920x1080 (16:9)
 * - Boxes: 1600x900 (16:9)
 * 
 * Uses ACF custom fields:
 * - banner_1: First carousel slide image (image array)
 * - banner_2: Second carousel slide image (image array) 
 * - ba_image: Before & After box image (image array)
 * - financing_image: Financing box image (image array)
 */
?>

<section class="hero-section">
  <div class="hero-container">
    <!-- Carousel Section (1920x1080) -->
    <div class="hero-carousel">
      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-touch="true" data-bs-interval="5000">
        <!-- Indicators -->
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>
        
        <!-- Slides -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <?php 
            $banner_1 = get_field('banner_1');
            if ($banner_1 && !empty($banner_1['url'])): ?>
            <img src="<?php echo esc_url($banner_1['url']); ?>" 
                 srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($banner_1['ID'], 'full')); ?>"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="<?php echo esc_attr($banner_1['width']); ?>"
                 height="<?php echo esc_attr($banner_1['height']); ?>"
                 class="d-block w-100" 
                 alt="<?php echo esc_attr($banner_1['alt']); ?>" 
                 fetchpriority="high">
            <?php else: ?>
            <div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center">
              <span class="text-white">Slide 1 Image Not Found</span>
            </div>
            <?php endif; ?>
          </div>
          <div class="carousel-item">
            <?php 
            $banner_2 = get_field('banner_2');
            if ($banner_2 && !empty($banner_2['url'])): ?>
            <img src="<?php echo esc_url($banner_2['url']); ?>"
                 srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($banner_2['ID'], 'full')); ?>"
                 sizes="(max-width: 480px) 100vw, (max-width: 767px) 100vw, 66vw"
                 width="<?php echo esc_attr($banner_2['width']); ?>"
                 height="<?php echo esc_attr($banner_2['height']); ?>"
                 class="d-block w-100" 
                 alt="<?php echo esc_attr($banner_2['alt']); ?>"
                 >
            <?php else: ?>
            <div class="carousel-placeholder bg-secondary d-flex align-items-center justify-content-center">
              <span class="text-white">Slide 2 Image Not Found</span>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar with Two Boxes (1600x900 each) -->
    <div class="hero-sidebar">
      <!-- Before & After Box -->
      <div class="hero-box hero-box-top">
        <?php 
        $ba_image = get_field('ba_image');
        if ($ba_image && !empty($ba_image['url'])): ?>
        <img src="<?php echo esc_url($ba_image['url']); ?>"
             srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($ba_image['ID'], 'full')); ?>"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="<?php echo esc_attr($ba_image['width']); ?>"
             height="<?php echo esc_attr($ba_image['height']); ?>"
             class="hero-box-image" 
             alt="<?php echo esc_attr($ba_image['alt']); ?>"
             loading="lazy">
        <?php else: ?>
        <div class="hero-box-placeholder bg-secondary d-flex align-items-center justify-content-center">
          <span class="text-white">Before & After Image Not Found</span>
        </div>
        <?php endif; ?>
        <div class="hero-box-overlay">
          <div class="hero-box-heading">Before & After Gallery</div>
          <a href="<?php echo esc_url(home_url('/before-after/')); ?>" class="mia-button" data-variant="hero">
            View Results <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>

      <!-- Financing Box -->
      <div class="hero-box hero-box-bottom">
        <?php 
        $financing_image = get_field('financing_image');
        if ($financing_image && !empty($financing_image['url'])): ?>
        <img src="<?php echo esc_url($financing_image['url']); ?>"
             srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($financing_image['ID'], 'full')); ?>"
             sizes="(max-width: 480px) 50vw, (max-width: 767px) 50vw, 33vw"
             width="<?php echo esc_attr($financing_image['width']); ?>"
             height="<?php echo esc_attr($financing_image['height']); ?>"
             class="hero-box-image" 
             alt="<?php echo esc_attr($financing_image['alt']); ?>"
             >
        <?php else: ?>
        <div class="hero-box-placeholder bg-secondary d-flex align-items-center justify-content-center">
          <span class="text-white">Financing Image Not Found</span>
        </div>
        <?php endif; ?>
        <div class="hero-box-overlay">
          <div class="hero-box-heading">Affordable Financing</div>
          <a href="<?php echo esc_url(home_url('/financing/')); ?>" class="mia-button" data-variant="hero">
            Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
