<?php
/**
 * The front page template file
 */
get_header(); ?>
<main id="primary" class="site-main">
<?php 
// Include hero section
include 'hero-section.php'; 
?>

<!-- About Section with Video Carousel -->
<section class="about-section py-5">
  <div class="container">
    <div class="row align-items-center">
      <!-- Left Column: About Content -->
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="about-content">
          <p class="tagline mb-2">Professional Care You Can Trust</p>
          <h1 class="mb-4">About Mia Aesthetics</h1>
          <p class="mb-4">Our mission at Mia Aesthetics is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
        </div>
      </div>
      
      <!-- Right Column: Video Carousel -->
      <div class="col-lg-6">
        <div class="video-carousel-wrapper">
          <!-- Glide.js Container -->
          <div class="glide video-glide" id="videoGlide">
            <div class="glide__track" data-glide-el="track">
              <ul class="glide__slides">
                <!-- Video 1 -->
                <li class="glide__slide">
                  <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/b6b0KaW2cXE" title="Mia Aesthetics Video 1" allowfullscreen></iframe>
                  </div>                 
                </li>
                <!-- Video 2 -->
                <li class="glide__slide">
                   <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/ykz9Z8Kh3Yo" title="Mia Aesthetics Video 2" allowfullscreen></iframe>
                  </div>                 
                </li>
                <!-- Video 3 -->
                <li class="glide__slide">
                   <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/MI9qpDPGuD4" title="Mia Aesthetics Video 3" allowfullscreen></iframe>
                  </div>                  
                </li>
                <!-- Video 4 -->
                <li class="glide__slide">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/OxigXlYTqH8" title="Mia Aesthetics Video 5" allowfullscreen></iframe>
                  </div>                  
                </li>
                <!-- Video 5 -->
                <li class="glide__slide">
                  <div class="ratio ratio-16x9">
                    <iframe src="https://www.youtube.com/embed/sb8Kapy8mzU" title="Mia Aesthetics Video 4" allowfullscreen></iframe>
                  </div>
                </li>
              </ul>
            </div>
            
            <!-- Navigation arrows -->
            <div class="glide__arrows" data-glide-el="controls">
              <button class="glide__arrow glide__arrow--left" data-glide-dir="<" aria-label="Previous video">
                <i class="fa-solid fa-chevron-left"></i>
              </button>
              <button class="glide__arrow glide__arrow--right" data-glide-dir=">" aria-label="Next video">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            </div>
            
            <!-- Pagination bullets -->
            <div class="glide__bullets" data-glide-el="controls[nav]">
              <button class="glide__bullet" data-glide-dir="=0" aria-label="Go to video 1"></button>
              <button class="glide__bullet" data-glide-dir="=1" aria-label="Go to video 2"></button>
              <button class="glide__bullet" data-glide-dir="=2" aria-label="Go to video 3"></button>
              <button class="glide__bullet" data-glide-dir="=3" aria-label="Go to video 4"></button>
              <button class="glide__bullet" data-glide-dir="=4" aria-label="Go to video 5"></button>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-4 py-md-5">
  <div class="container">
    <!-- Top Content -->
    <div class="row mb-4 mb-md-5">
      <!-- Left Column: Tagline and Heading -->
      <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="stats-heading">
          <p class="tagline mb-1 mb-md-2">Our Commitment</p>
          <h2 class="stats-heading-title">Trusted Surgical Excellence</h2>
        </div>
      </div>
      
      <!-- Right Column: Paragraph and Buttons -->
      <div class="col-lg-6">
        <div class="stats-content">          <p class="mb-3 mb-md-4 fs-6"> Delivering life-changing results with expert care at every step. Our team of highly skilled specialists, years of experience, and thousands of satisfied patients set us apart in the industry.</p>
          <div class="d-flex">
            <a href="/locations/" class="mia-button me-3" data-variant="black" role="button">Our Locations</a>
            <a href="/plastic-surgeons/" class="mia-button" data-variant="black" role="button">Our Surgeons</a>
          </div>
        </div>
      </div>
    </div>
    
<!-- Stats Row -->
<div class="row row-cols-2 row-cols-md-4 g-4 mt-4 mt-md-5">
  <!-- Stat Item 1: Founded Year -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="2018">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Year Founded</p>
    </div>
  </div>
  
  <!-- Stat Item 2: Number of Surgeons (Dynamic) -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="<?php
        $stats = mia_get_site_stats();
        echo $stats['surgeons'];
        ?>">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Expert Surgeons</p>
    </div>
  </div>
  
  <!-- Stat Item 3: Number of Locations (Dynamic) -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="<?php
        echo $stats['locations'];
        ?>">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Clinic Locations</p>
    </div>
  </div>
  
  <!-- Stat Item 4: Total Patients -->
  <div class="col">
    <div class="position-relative ps-3 ps-md-4 mb-4">
      <div class="position-absolute start-0 top-0 stat-line-gold"></div>
      <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="150" data-suffix=",000 +">0</h3>
      <p class="text-white opacity-75 mb-0 fs-6">Satisfied Patients</p>
    </div>
  </div>
</div>
  </div>
</section>



<!-- How Payments Work Section -->
<section class="how-payments-work-section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <!-- Centered Content -->
      <div class="col-xl-10 col-lg-11">
        <div class="text-center mb-4">
          <h2 class="how-payments-heading mb-2 fs-1 fw-bold">How Payments Work</h2>
          <h3 class="how-payments-subheading mb-4 fs-4">at Mia Aesthetics</h3>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
              <div class="payment-step-card">
                <h4 class="mb-3 fw-semibold">1. Research Surgeons</h4>
                <ul class="mb-0 ps-3">
                  <li>Choose from over 25 experienced surgeons</li>
                  <li>Review surgeon bios on our website</li>
                  <li>Browse our before and after photos</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card">
                <h4 class="mb-3 fw-semibold">2. Check Specials</h4>
                <ul class="mb-0 ps-3">
                  <li>Contact a sales coordinator for specials</li>
                  <li>New surgeons often offer reduced rates</li>
                  <li>Keep an eye on seasonal specials</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card">
                <h4 class="mb-3 fw-semibold">3. Lock in Your Price</h4>
                <ul class="mb-0 ps-3">
                  <li>Call us to secure a special price</li>
                  <li>Booking fee required to lock in price</li>
                  <li>Price valid for 12 months</li>
                </ul>
              </div>
            </div>
            <div class="col">
              <div class="payment-step-card">
                <h4 class="mb-3 fw-semibold">4. Complete Payments</h4>
                <ul class="mb-0 ps-3">
                  <li>Pay at your own pace</li>
                  <li>Contact your patient concierge</li>
                  <li>Ask for contract extensions if needed</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Video Background Section -->
<section class="video-background-section position-relative overflow-hidden">
  <!-- Video Container with 16:9 aspect ratio -->
  <div class="video-container ratio ratio-16x9">
    <video 
      autoplay 
      muted 
      loop 
      playsinline
      class="video-bg">
      <source src="/wp-content/uploads/2025/07/test-bg-1.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
  
  <!-- Content Overlay -->
  <div class="video-content-overlay position-absolute top-0 start-0 w-100 h-100 d-none d-md-flex align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h2 class="display-3 fw-bold text-white mb-4">Experience Excellence</h2>
          <p class="lead text-white mb-4">Join thousands of satisfied patients who have transformed their lives at Mia Aesthetics</p>
          <a href="/consultation/" class="mia-button" data-variant="gold" data-size="lg">Free Virtual Consultation</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Procedures Tabbed Section -->
<section class="procedures-section py-5">
  <div class="container">
    <div class="row mb-5 text-center">
      <div class="col-12">
        <h2 class="section-heading">Our Procedures</h2>
        <p class="lead">Discover the perfect procedure for your aesthetic goals</p>
      </div>
    </div>

    <!-- Procedure Navigation Tabs -->
    <div class="row">
      <div class="col-12">
        <!-- Desktop: tabs with arrows (≥768px) -->
        <div class="procedure-tabs-shell d-none d-md-flex align-items-center">
          <!-- Arrow – Previous -->
          <button class="procedure-nav-arrow prev-arrow me-2" type="button" aria-label="Previous procedure category">
            <i class="fa-solid fa-chevron-left"></i>
          </button>

          <!-- Scrollable tab strip -->
          <ul class="nav nav-tabs procedure-tabs flex-nowrap overflow-auto" id="procedureTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="body-tab" data-bs-toggle="tab" data-bs-target="#body-content" type="button" role="tab" aria-controls="body-content" aria-selected="true">
                Body
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="breast-tab" data-bs-toggle="tab" data-bs-target="#breast-content" type="button" role="tab" aria-controls="breast-content" aria-selected="false">
                Breast
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="face-tab" data-bs-toggle="tab" data-bs-target="#face-content" type="button" role="tab" aria-controls="face-content" aria-selected="false">
                Face
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="nonsurgical-tab" data-bs-toggle="tab" data-bs-target="#nonsurgical-content" type="button" role="tab" aria-controls="nonsurgical-content" aria-selected="false">
                Non-Surgical
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="men-tab" data-bs-toggle="tab" data-bs-target="#men-content" type="button" role="tab" aria-controls="men-content" aria-selected="false">
                Men
              </button>
            </li>
          </ul>

          <!-- Arrow – Next -->
          <button class="procedure-nav-arrow next-arrow ms-2" type="button" aria-label="Next procedure category">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>

        <!-- Mobile: simple select (<768px) -->
        <select id="procedureDropdown" class="form-select d-block d-md-none mt-3" aria-label="Select a procedure category">
          <option value="body-content" selected>Body</option>
          <option value="breast-content">Breast</option>
          <option value="face-content">Face</option>
          <option value="nonsurgical-content">Non-Surgical</option>
          <option value="men-content">Men</option>
        </select>
      </div>
    </div>

    <!-- Procedure Content -->
    <div class="tab-content mt-4" id="procedureTabsContent">
      <!-- Body Content -->
      <div class="tab-pane fade show active" id="body-content" role="tabpanel" aria-labelledby="body-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
            <?php 
            $body_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/body-home.jpg');
            if ($body_img_id) {
              echo wp_get_attachment_image($body_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Body Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/body-home.jpg" alt="Body Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Body Contouring</h3>
            <p class="section-subheading mb-4">Sculpt Your Ideal Figure</p>
            <p class="mb-4">Our body contouring procedures help you achieve the silhouette you desire. Whether you're looking to remove excess fat, tighten loose skin, or enhance your curves, our specialists can help you reach your aesthetic goals.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/body/awake-liposuction/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Awake Lipo
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Brazilian Butt Lift (BBL)
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/lipo-360/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Lipo 360
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/body/mommy-makeover/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Mommy Makeover
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/tummy-tuck/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Tummy Tuck
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/arm-lift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Arm Lift
                  </a>
                </div>
              </div>
              <a href="/cosmetic-plastic-surgery/body/" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Body Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Breast Content -->
      <div class="tab-pane fade" id="breast-content" role="tabpanel" aria-labelledby="breast-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
            <?php 
            $breast_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/breast-home.jpg');
            if ($breast_img_id) {
              echo wp_get_attachment_image($breast_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Breast Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/breast-home.jpg" alt="Breast Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Breast Enhancement</h3>
            <p class="section-subheading mb-4">Achieve Your Desired Look</p>
            <p class="mb-4">Our breast procedures are designed to enhance, reduce, or lift your breasts to achieve your desired appearance. Our board-certified surgeons use the latest techniques to deliver natural-looking results with minimal scarring.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/breast/augmentation-implants/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Augmentation
                  </a>
                  <a href="/cosmetic-plastic-surgery/breast/lift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Lift
                  </a>
                  <a href="/cosmetic-plastic-surgery/breast/reduction/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Reduction
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/breast/implant-revision-surgery/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Breast Implant Revision
                  </a>                  
                  <a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Breast Procedures
                  </a>
                </div>
              </div>
              <a href="/cosmetic-plastic-surgery/breast/" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Breast Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Face Content -->
      <div class="tab-pane fade" id="face-content" role="tabpanel" aria-labelledby="face-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
            <?php 
            $face_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/face-2-home.jpg');
            if ($face_img_id) {
              echo wp_get_attachment_image($face_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Facial Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/face-2-home.jpg" alt="Facial Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Facial Rejuvenation</h3>
            <p class="section-subheading mb-4">Enhance Your Natural Beauty</p>
            <p class="mb-4">Our facial procedures are designed to enhance your natural features and restore youthful appearance. From facelifts to rhinoplasty, our board-certified surgeons use the latest techniques to deliver exceptional results.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/face/brow-lift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Brow Lift
                  </a>
                  <a href="/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Buccal Fat Removal
                  </a>
                  <a href="/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Eyelid Lift
                  </a>
                </div>
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/face/facelift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Facelift
                  </a>
                  <a href="/cosmetic-plastic-surgery/face/mini-facelift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Mini Facelift
                  </a>
                  <a href="/cosmetic-plastic-surgery/face/neck-lift/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Neck Lift
                  </a>
                </div>
              </div>
              <a href="/cosmetic-plastic-surgery/face/" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Facial Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Non-Surgical Content -->
      <div class="tab-pane fade" id="nonsurgical-content" role="tabpanel" aria-labelledby="nonsurgical-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
            <?php 
            $nonsurg_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/face-home.jpg');
            if ($nonsurg_img_id) {
              echo wp_get_attachment_image($nonsurg_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Non-Surgical Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/face-home.jpg" alt="Non-Surgical Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Non-Surgical Treatments</h3>
            <p class="section-subheading mb-4">Rejuvenate Without Surgery</p>
            <p class="mb-4">Our non-surgical treatments offer remarkable results with minimal downtime. From injectables to laser therapies, we provide a range of options to address your concerns without the need for surgery.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
        
                <div class="col-md-6">
                <a href="/non-surgical/j-plasma-skin-tightening/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> J-Plasma
                  </a>
                  <a href="/weight-loss/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Weight Loss
                  </a>
                </div>
              </div>
              <a href="/non-surgical/" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Non-Surgical Treatments
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Men Content -->
      <div class="tab-pane fade" id="men-content" role="tabpanel" aria-labelledby="men-tab">
        <div class="row align-items-center">
          <div class="col-12 col-xl-6 order-xl-2 mb-4 mb-xl-0">
            <?php 
            $men_img_id = attachment_url_to_postid('/wp-content/uploads/2025/04/men-home.jpg');
            if ($men_img_id) {
              echo wp_get_attachment_image($men_img_id, 'large', false, array(
                'class' => 'img-fluid rounded shadow',
                'alt' => 'Men\'s Procedures',
                'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px'
              ));
            } else {
              // Fallback if image ID can't be found
              echo '<img src="/wp-content/uploads/2025/04/men-home.jpg" alt="Men\'s Procedures" class="img-fluid rounded shadow">';
            }
            ?>
          </div>
          <div class="col-12 col-xl-6 order-xl-1">
            <h3 class="procedure-title mb-3">Men's Procedures</h3>
            <p class="section-subheading mb-4">Tailored Solutions for Men</p>
            <p class="mb-4">Our men's procedures are specifically designed to address the unique concerns and aesthetic goals of our male patients. From body contouring to facial rejuvenation, we offer a range of treatments to help you look and feel your best.</p>
            
            <div class="procedure-links mb-4">
              <div class="row">
                <div class="col-md-6">
                  <a href="/cosmetic-plastic-surgery/body/male-bbl/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Brazilian Butt Lift (BBL)
                  </a>
                  <a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Breast Procedures
                  </a>
                 
                </div>
                <div class="col-md-6">
                <a href="/cosmetic-plastic-surgery/body/male-liposuction/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Liposuction
                  </a>
                  <a href="/cosmetic-plastic-surgery/body/male-tummy-tuck/" class="procedure-link">
                    <i class="fa-solid fa-arrow-right"></i> Male Tummy Tuck
                  </a>
               
                </div>
              </div>
              <a href="/cosmetic-plastic-surgery/men/" class="procedure-link">
                <i class="fa-solid fa-arrow-right"></i> View All Men's Procedures
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Foundation Section with Fixed Background (Desktop) / Regular Image (Mobile) -->
<section class="foundation-section">
  <!-- Background image for desktop -->
  <div class="foundation-bg d-none d-md-block" style="background-image: url('/wp-content/uploads/2025/04/miaf6.jpg');"></div>
  
  <!-- Regular image for mobile -->
  <div class="foundation-mobile-img d-md-none">
    <img src="/wp-content/uploads/2025/04/miaf6.jpg" alt="Mia Aesthetics Foundation" class="img-fluid w-100">
  </div>
  
  <div class="container position-relative">
    <div class="row">
      <div class="col-12">
        <div class="foundation-content text-center">
          <h2 class="section-heading text-white mb-2">The Mia Aesthetics Foundation</h2>
          <h3 class="section-subheading mb-4">Gives Back</h3>
          <p class="text-white mb-4">We're committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
          <a href="/mia-foundation/" class="mia-button" data-variant="black" role="button" aria-label="Discover Mia Aesthetics Foundation charitable work"> See Our Impact <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>


</main>
<?php get_footer(); ?>
