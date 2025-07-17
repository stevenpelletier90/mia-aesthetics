<?php
/**
 * Template Name: Careers
 * 
 * This is a custom page template for the careers landing page
 * It includes hardcoded content to allow for PHP functionality
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<!-- Careers Landing Page HTML -->
<section class="careers-hero">
  <div class="careers-hero__background">
    <img
      src="<?php echo esc_url(get_site_url()); ?>/wp-content/uploads/2024/careers-team-hero.jpg"
      alt="Mia Aesthetics Team - Join Our Mission"
      class="careers-hero__image"
    />
    <div class="careers-hero__overlay"></div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-xl-7">
        <div class="careers-hero__content">
          <h1 class="careers-hero__title">Join Mia Aesthetics</h1>
          <div class="careers-hero__text">
            <p>
              At Mia Aesthetics, passion, and purpose meet to transform lives every day. We believe confidence is
              powerful — and so are the people behind it. As a nationwide leader in plastic surgery and aesthetic care,
              we're on a mission to empower individuals through transformative experiences that enhance both inner and
              outer beauty.
            </p>
          </div>
          <div class="careers-hero__cta">
            <a href="#careers-application" class="mia-button" data-variant="hero"> Click to Apply </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Trusted Excellence Section -->
<section class="stats-section py-4 py-md-5">
  <div class="container">
    <!-- Top Content -->
    <div class="row mb-4 mb-md-5">
      <!-- Left Column: Tagline and Heading -->
      <div class="col-lg-6 mb-3 mb-lg-0">
        <div class="stats-heading">
          <p class="section-tagline mb-1 mb-md-2">Our Commitment</p>
          <h2 class="stats-heading-title">Why Work at Mia</h2>
        </div>
      </div>

      <!-- Right Column: Paragraph and Buttons -->
      <div class="col-lg-6">
        <div class="stats-content">
          <p class="mb-3 mb-md-4 fs-6">
            Whether you're in the clinic, behind the scenes, or leading operations, your work directly supports
            life-changing moments for our patients. If you're passionate about helping others, committed to delivering
            outstanding care, and ready to grow with a purpose-driven organization, we'd love to meet you.
          </p>
          <div class="d-flex">
            <a href="<?php echo esc_url(home_url('/locations/')); ?>" class="mia-button me-3" data-variant="black" role="button">Our Locations</a>
            <a href="<?php echo esc_url(home_url('/plastic-surgeons/')); ?>" class="mia-button" data-variant="black" role="button">Our Surgeons</a>
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

      <!-- Stat Item 2: Number of Surgeons -->
      <div class="col">
        <div class="position-relative ps-3 ps-md-4 mb-4">
          <div class="position-absolute start-0 top-0 stat-line-gold"></div>
          <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="25">0</h3>
          <p class="text-white opacity-75 mb-0 fs-6">Expert Surgeons</p>
        </div>
      </div>

      <!-- Stat Item 3: Number of Locations -->
      <div class="col">
        <div class="position-relative ps-3 ps-md-4 mb-4">
          <div class="position-absolute start-0 top-0 stat-line-gold"></div>
          <h3 class="display-5 fw-bold text-white mb-2 lh-1" data-count="15">0</h3>
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

<!-- Core Values Section -->
<section class="core-values-section">
  <div class="container">
    <div class="core-values__header">
      <p class="section-tagline">What Drives Us</p>
      <h2 class="core-values__title">Our Core Values</h2>
      <p class="core-values__subtitle">
        These fundamental principles guide everything we do and shape the exceptional experience we create for both our
        team and our patients.
      </p>
    </div>

    <div class="values-grid">
      <!-- Value 1: Patients First -->
      <div class="value-card" data-value="1">
        <div class="value-icon">❤️</div>
        <h3 class="value-title">Patients First</h3>
        <p class="value-description">
          We are committed to the highest quality of patient care. We understand our patients and support them in their
          journey to achieve their dreams.
        </p>
      </div>

      <!-- Value 2: Execute With Purpose -->
      <div class="value-card" data-value="2">
        <div class="value-icon">💡</div>
        <h3 class="value-title">Execute With Purpose</h3>
        <p class="value-description">
          We are purposeful in everything we do by demonstrating enthusiasm, best intentions, and commitment to
          delivering results.
        </p>
      </div>

      <!-- Value 3: Respect For Others -->
      <div class="value-card" data-value="3">
        <div class="value-icon">🤝</div>
        <h3 class="value-title">Respect For Others</h3>
        <p class="value-description">
          We value and drive mutual respect for our customers, partners and teammates. In doing so, we benefit from an
          agreeable work environment and increased job satisfaction.
        </p>
      </div>

      <!-- Value 4: Collaborate Openly -->
      <div class="value-card" data-value="4">
        <div class="value-icon">🧩</div>
        <h3 class="value-title">Collaborate Openly</h3>
        <p class="value-description">
          When we work together, we bring new ideas forward, create solutions and learn from each other.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Mia Foundation Parallax Section -->
<section class="foundation-parallax">
  <div class="container">
    <div class="foundation-content">
      <h2>Mia Aesthetics Foundation</h2>
      <p>
        We are proud to give back through the Mia Aesthetics Foundation, a non- profit organization. Our talented
        surgical team provides reconstructive plastic surgery to those who live in underserved communities. The Mia
        Foundation teams up with like-minded partners across the globe who share our mission and values. Click below to
        learn more about how the Mia Foundation continues to help those in need.
      </p>
      <div class="foundation-cta">
        <a href="<?php echo esc_url(home_url('/mia-foundation/')); ?>" class="mia-button" data-variant="gold">Learn More</a>
      </div>
    </div>
  </div>
</section>

<!-- Find Location Section -->
<section class="find-location-section">
  <div class="container">
    <div class="find-location__header">    
      <h2 class="find-location__title">Our Locations</h2>
      <p class="find-location__subtitle">
        Enter your location information to find the nearest opportunity for you
      </p>
    </div>

    <?php 
    // Include the careers location search component
    include get_template_directory() . '/inc/location-search-careers.php'; 
    ?>

    <!-- View All Locations Button -->
    <div class="text-center mt-4" style="margin-top: 3rem">
      <a href="<?php echo esc_url(home_url('/locations/')); ?>" class="mia-button" data-variant="gold-outline">View All Locations</a>
    </div>
  </div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
  <div class="container">
    <div class="benefits-header text-center mb-5">
      <p class="section-tagline">What We Offer</p>
      <h2 class="benefits-title">Benefits</h2>
      <p class="benefits-subtitle">At Mia Aesthetics, we support our team and their families with benefits that care for their physical, mental, and financial well-being.</p>
      <blockquote class="founders-quote mt-4 mb-5">
        <p class="quote-text">"Our growth has taught us many things, and we have learned that building a strong brand stands on the execution of excellent customer service driven by employee happiness."</p>
        <footer class="quote-attribution">Our Founders</footer>
      </blockquote>
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
      <a href="#careers-application" class="mia-button" data-variant="gold" data-size="lg">
        Join Our Team <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- Additional content sections can be added below -->
<div class="careers-content">
  <div class="container">
    <!-- Future content will go here -->
  </div>
</div>

<?php get_footer(); ?>