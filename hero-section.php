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
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        
        <!-- Slides -->
        <div class="carousel-inner">
          <div class="carousel-item active">
            <?php 
            $banner_1 = get_field('banner_1');
            if ($banner_1 && !empty($banner_1['url'])): ?>
            <!-- Wide Desktop: 2560x800, Desktop: 1920x600, Mobile: 800x600 -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/Heat-Banner-MOB.jpg">
              <source media="(max-width: 1920px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/Heat-Banner-Desktop.jpg">
              <img src="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/Heat-Banner-WIDESCREEN.jpg" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 1" 
                   fetchpriority="high">
            </picture>
            <?php else: ?>
            <!-- Fallback with placehold.co -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/800x600/1b1b1b/c8b273?text=Mobile+Carousel+1+(800x600)">
              <source media="(max-width: 1920px)" srcset="https://placehold.co/1920x600/1b1b1b/c8b273?text=Desktop+Carousel+1+(1920x600)">
              <img src="https://placehold.co/2560x800/1b1b1b/c8b273?text=Wide+Desktop+Carousel+1+(2560x800)" 
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
            <!-- Wide Desktop: 2560x800, Desktop: 1920x600, Mobile: 800x600 -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/800x600/333333/c8b273?text=Mobile+Carousel+2+(800x600)">
              <source media="(max-width: 1920px)" srcset="https://placehold.co/1920x600/333333/c8b273?text=Desktop+Carousel+2+(1920x600)">
              <img src="https://placehold.co/2560x800/333333/c8b273?text=Wide+Desktop+Carousel+2+(2560x800)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 2">
            </picture>
            <?php else: ?>
            <!-- Fallback with placehold.co -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/800x600/333333/c8b273?text=Mobile+Carousel+2+(800x600)">
              <source media="(max-width: 1920px)" srcset="https://placehold.co/1920x600/333333/c8b273?text=Desktop+Carousel+2+(1920x600)">
              <img src="https://placehold.co/2560x800/333333/c8b273?text=Wide+Desktop+Carousel+2+(2560x800)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 2">
            </picture>
            <?php endif; ?>
          </div>
          <div class="carousel-item">
            <?php 
            $banner_3 = get_field('banner_3');
            if ($banner_3 && !empty($banner_3['url'])): ?>
            <!-- Wide Desktop: 2560x800, Desktop: 1920x600, Mobile: 800x600 -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/800x600/555555/c8b273?text=Mobile+Carousel+3+(800x600)">
              <source media="(max-width: 1920px)" srcset="https://placehold.co/1920x600/555555/c8b273?text=Desktop+Carousel+3+(1920x600)">
              <img src="https://placehold.co/2560x800/555555/c8b273?text=Wide+Desktop+Carousel+3+(2560x800)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 3">
            </picture>
            <?php else: ?>
            <!-- Fallback with placehold.co -->
            <picture>
              <source media="(max-width: 767px)" srcset="https://placehold.co/800x600/555555/c8b273?text=Mobile+Carousel+3+(800x600)">
              <source media="(max-width: 1920px)" srcset="https://placehold.co/1920x600/555555/c8b273?text=Desktop+Carousel+3+(1920x600)">
              <img src="https://placehold.co/2560x800/555555/c8b273?text=Wide+Desktop+Carousel+3+(2560x800)" 
                   class="d-block w-100" 
                   alt="Hero Carousel Slide 3">
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
        <!-- Desktop: 600x240 (2.5:1), Mobile: 600x720 (5:6) -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/results-Mob.jpg">
          <img src="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/results-desktop.jpg" 
               class="hero-box-image" 
               alt="Before & After Gallery"
               loading="lazy">
        </picture>
        <?php else: ?>
        <!-- Fallback with placehold.co -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/results-Mob.jpg">
          <img src="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/results-desktop.jpg" 
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
        <!-- Desktop: 600x240 (2.5:1), Mobile: 600x720 (5:6) -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/financingbox-mobile.jpg">
          <img src="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/financingbox-desktop.jpg" 
               class="hero-box-image" 
               alt="Affordable Financing">
        </picture>
        <?php else: ?>
        <!-- Fallback with placehold.co -->
        <picture>
          <source media="(max-width: 767px)" srcset="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/financingbox-mobile.jpg">
          <img src="https://miaprod.wpenginepowered.com/wp-content/uploads/2025/08/financingbox-desktop.jpg" 
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
