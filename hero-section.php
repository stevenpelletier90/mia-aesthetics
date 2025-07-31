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
            <!-- Desktop: 2560x720, Mobile: 1080x405 -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/1080x405/1b1b1b/c8b273?text=Mobile+Carousel+1+(1080x405)">
              <img src="https://placehold.co/2560x720/1b1b1b/c8b273?text=Desktop+Carousel+1+(2560x720)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 1" 
                   fetchpriority="high">
            </picture>
            <?php else: ?>
            <!-- Fallback with placehold.co -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/1080x405/1b1b1b/c8b273?text=Mobile+Carousel+1+(1080x405)">
              <img src="https://placehold.co/2560x720/1b1b1b/c8b273?text=Desktop+Carousel+1+(2560x720)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 1" 
                   fetchpriority="high">
            </picture>
            <?php endif; ?>
          </div>
          <div class="carousel-item">
            <?php 
            $banner_2 = get_field('banner_2');
            if ($banner_2 && !empty($banner_2['url'])): ?>
            <!-- Desktop: 2560x720, Mobile: 1080x405 -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/1080x405/333333/c8b273?text=Mobile+Carousel+2+(1080x405)">
              <img src="https://placehold.co/2560x720/333333/c8b273?text=Desktop+Carousel+2+(2560x720)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 2">
            </picture>
            <?php else: ?>
            <!-- Fallback with placehold.co -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/1080x405/333333/c8b273?text=Mobile+Carousel+2+(1080x405)">
              <img src="https://placehold.co/2560x720/333333/c8b273?text=Desktop+Carousel+2+(2560x720)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 2">
            </picture>
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
        <!-- Desktop: 1000x600, Mobile: 800x800 -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://placehold.co/800x1000/c8b273/1b1b1b?text=Mobile+Before+After+(800x1000)">
          <img src="https://placehold.co/1000x400/c8b273/1b1b1b?text=Desktop+Before+After+(1000x400)" 
               class="hero-box-image" 
               alt="Before & After Gallery"
               loading="lazy">
        </picture>
        <?php else: ?>
        <!-- Fallback with placehold.co -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://placehold.co/800x1000/c8b273/1b1b1b?text=Mobile+Before+After+(800x1000)">
          <img src="https://placehold.co/1000x400/c8b273/1b1b1b?text=Desktop+Before+After+(1000x400)" 
               class="hero-box-image" 
               alt="Before & After Gallery"
               loading="lazy">
        </picture>
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
        <!-- Desktop: 1000x600, Mobile: 800x800 -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://placehold.co/800x1000/1b1b1b/c8b273?text=Mobile+Financing+(800x1000)">
          <img src="https://placehold.co/1000x400/1b1b1b/c8b273?text=Desktop+Financing+(1000x400)" 
               class="hero-box-image" 
               alt="Affordable Financing">
        </picture>
        <?php else: ?>
        <!-- Fallback with placehold.co -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://placehold.co/800x1000/1b1b1b/c8b273?text=Mobile+Financing+(800x1000)">
          <img src="https://placehold.co/1000x400/1b1b1b/c8b273?text=Desktop+Financing+(1000x400)" 
               class="hero-box-image" 
               alt="Affordable Financing">
        </picture>
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
