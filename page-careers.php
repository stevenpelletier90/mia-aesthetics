<?php
/**
 * Template Name: Careers
 *
 * This is a custom page template for the careers landing page
 * It includes hardcoded content to allow for PHP functionality
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
<!-- Careers Landing Page HTML -->
<section class="careers-hero py-5">
	<div class="container">
	<div class="row align-items-center g-4">
		<!-- Left Column: Content -->
		<div class="col-lg-6">
		<div class="careers-hero__content">
			<h1 class="careers-hero__title">Join <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span></h1>
			<div class="careers-hero__text">
			<p>
				At <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span>, passion, and purpose meet to transform lives every day.
			We believe confidence is
				powerful — and so are the people behind it. As a nationwide leader in plastic surgery and aesthetic care,
				we're on a mission to empower individuals through transformative experiences that enhance both inner and
				outer beauty.
			</p>
			</div>
			<div class="careers-hero__cta">
			<a href="https://recruiting.paylocity.com/Recruiting/Jobs/All/450b9702-9d0a-45f8-9f93-340cc0200bab/Mia-Aesthetics-Services" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg"> Join Our Team </a>
			</div>
		</div>
		</div>
	  
		<!-- Right Column: Image -->
		<div class="col-lg-6">
		<div class="careers-hero__image-container">
			<img
			src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/09/employees.jpg' ) ); ?>"
			alt="Mia Aesthetics™ Team - Join Our Mission"
			class="careers-hero__image img-fluid rounded"
			/>
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
		</div>
		</div>
	</div>

	<!-- Stats Row -->
	<div class="row row-cols-2 row-cols-md-4 g-4 mt-4 mt-md-5">
		<!-- Stat Item 1: Founded Year -->
		<div class="col">
		<div class="position-relative ps-3 ps-md-4 mb-4">
			<div class="position-absolute start-0 top-0 stat-line-gold"></div>
			<h3 class="display-5 text-white mb-2 lh-1" data-count="2017">0</h3>
			<p class="text-white opacity-75 mb-0 fs-6">Year Founded</p>
		</div>
		</div>

		<!-- Stat Item 2: Number of Surgeons (Dynamic) -->
		<div class="col">
		<div class="position-relative ps-3 ps-md-4 mb-4">
			<div class="position-absolute start-0 top-0 stat-line-gold"></div>
			<h3 class="display-5 text-white mb-2 lh-1" data-count="
			<?php
			$stats = mia_aesthetics_get_site_stats();
			echo esc_attr( (string) $stats['surgeons'] );
			?>
			">0</h3>
			<p class="text-white opacity-75 mb-0 fs-6">Expert Surgeons</p>
		</div>
		</div>

		<!-- Stat Item 3: Number of Locations (Dynamic) -->
		<div class="col">
		<div class="position-relative ps-3 ps-md-4 mb-4">
			<div class="position-absolute start-0 top-0 stat-line-gold"></div>
			<h3 class="display-5 text-white mb-2 lh-1" data-count="
			<?php
			echo esc_attr( (string) $stats['locations'] );
			?>
			">0</h3>
			<p class="text-white opacity-75 mb-0 fs-6">Clinic Locations</p>
		</div>
		</div>

		<!-- Stat Item 4: Total Patients -->
		<div class="col">
		<div class="position-relative ps-3 ps-md-4 mb-4">
			<div class="position-absolute start-0 top-0 stat-line-gold"></div>
			<h3 class="display-5 text-white mb-2 lh-1" data-count="150" data-suffix=",000 +">0</h3>
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
		<div class="value-icon">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/core-values.svg' ) ); ?>" alt="Patients First Icon" class="value-svg-icon">
		</div>
		<h3 class="value-title">Patients First</h3>
		<p class="value-description">
			We are committed to the highest quality of patient care. We understand our patients and support them in
			their
			journey to achieve their dreams.
		</p>
		</div>

		<!-- Value 2: Execute With Purpose -->
		<div class="value-card" data-value="2">
		<div class="value-icon">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/core-values2.svg' ) ); ?>" alt="Execute With Purpose Icon" class="value-svg-icon">
		</div>
		<h3 class="value-title">Execute With Purpose</h3>
		<p class="value-description">
			We are purposeful in everything we do by demonstrating enthusiasm, best intentions, and commitment to
			delivering results.
		</p>
		</div>

		<!-- Value 3: Respect For Others -->
		<div class="value-card" data-value="3">
		<div class="value-icon">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/core-values3.svg' ) ); ?>" alt="Respect For Others Icon" class="value-svg-icon">
		</div>
		<h3 class="value-title">Respect For Others</h3>
		<p class="value-description">
			We value and drive mutual respect for our customers, partners and teammates. In doing so, we benefit from an
			agreeable work environment and increased job satisfaction.
		</p>
		</div>

		<!-- Value 4: Collaborate Openly -->
		<div class="value-card" data-value="4">
		<div class="value-icon">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/core-values4.svg' ) ); ?>" alt="Collaborate Openly Icon" class="value-svg-icon">
		</div>
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
		<h2><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Foundation</h2>
		<p>
		We are proud to give back through the <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Foundation, a non- profit organization. Our talented
		surgical team provides reconstructive plastic surgery to those who live in underserved communities. The Mia
		Foundation teams up with like-minded partners across the globe who share our mission and values. Click below to
		learn more about how the Mia Foundation continues to help those in need.
		</p>
		<div class="foundation-cta">
		<a href="<?php echo esc_url( home_url( '/mia-foundation/' ) ); ?>" class="btn btn-primary">Learn More</a>
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
	// Include the careers location search component.
	require get_template_directory() . '/inc/location-search-careers.php';
	?>
	</div>
</section>

<!-- Benefits Section -->
<section class="benefits-section">
	<div class="container">
	<div class="benefits-top-content mb-5">
		<div class="row align-items-center">
		<!-- Left Column: Headings and Paragraphs -->
		<div class="col-lg-6">
			<div class="benefits-header">
			<p class="section-tagline">What We Offer</p>
			<h2 class="benefits-title">Benefits</h2>
			<p class="benefits-subtitle">At <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span>, we support our team and their families with benefits that care for their physical, mental, and financial well-being.</p>
			</div>
		</div>
		
		<!-- Right Column: Founders Quote -->
		<div class="col-lg-6">
			<div class="founders-quote-wrapper">
			<blockquote class="founders-quote">
				<div class="quote-icon">
				<i class="fas fa-quote-left" aria-hidden="true"></i>
				</div>
				<p class="quote-text">"Our growth has taught us many things, and we have learned that building a strong brand stands on the execution of excellent customer service driven by employee happiness."</p>
				<footer class="quote-attribution">
				<span class="attribution-line"></span>
				<span class="attribution-text">Our Founders</span>
				</footer>
			</blockquote>
			</div>
		</div>
		</div>
	</div>

	<div class="row g-4">
		<!-- Health & Wellness -->
		<div class="col-lg-3 col-md-6">
		<div class="benefit-card h-100 p-4 text-center">
			<div class="benefit-icon mb-3">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Health-Wellness.svg' ) ); ?>" alt="Health & Wellness" class="benefit-svg-icon">
			</div>
			<h3 class="benefit-title">Health & Wellness</h3>
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
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/PTO.svg' ) ); ?>" alt="Time To Unwind" class="benefit-svg-icon">
			</div>
			<h3 class="benefit-title">Time To Unwind</h3>
			<ul class="benefit-list">
			<li><strong>Paid Time Off (PTO):</strong> Accrue from date of hire; PTO for vacation, personal appointments, illness, and other events</li>
			<li><strong>Paid Holidays:</strong> Recognized company holidays throughout the calendar year</li>
			<li><strong>Paid Leave:</strong> As dictated by applicable state law</li>
			</ul>
		</div>
		</div>

		<!-- Financial Well-Being -->
		<div class="col-lg-3 col-md-6">
		<div class="benefit-card h-100 p-4 text-center">
			<div class="benefit-icon mb-3">
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Financial.svg' ) ); ?>" alt="Financial Well-Being" class="benefit-svg-icon">
			</div>
			<h3 class="benefit-title">Financial Well-Being</h3>
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
			<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/08/Other-Perks.svg' ) ); ?>" alt="Other Perks" class="benefit-svg-icon">
			</div>
			<h3 class="benefit-title">Other Perks</h3>
			<ul class="benefit-list">
			<li><strong>Discounts on service offerings</strong></li>
			<li><strong>Celebratory company-wide event(s)</strong></li>
			</ul>
		</div>
		</div>
	</div>

	<!-- Benefits CTA -->
	<div class="text-center mt-5">
		<a href="https://recruiting.paylocity.com/Recruiting/Jobs/All/450b9702-9d0a-45f8-9f93-340cc0200bab/Mia-Aesthetics-Services" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
		Join Our Team <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
		</a>
	</div>
	</div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section">
	<div class="container">
	<div class="testimonials-header text-center mb-5">
		<p class="section-tagline">Patient Excellence</p>
		<h2 class="testimonials-title">What Our Patients Say</h2>
		<p class="testimonials-subtitle">See why patients choose our exceptional team and experience the <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> difference.</p>
	</div>

	<!-- Bootstrap Carousel -->
	<div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
		<div class="carousel-inner">
		<!-- Slide 1 - Patient Reviews 1-3 -->
		<div class="carousel-item active">
			<div class="row g-4 justify-content-center">
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"Dr. Valauri, Ana and Mo are Mia's Magic Trio! Mo will make you feel safe and welcome with her amazing customer service etiquette. Dr. Valauri did an amazing job. He delivered exactly what I asked for. My results exceeded my expectations."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Tampa Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"From my pre-op, Tajh was AWESOME, she made me very comfortable & gave reassurance. The nurses were AWESOME, but VANESSA, she is THE GOAT!! She's honest & has a gift of calmness. 10/10 definitely recommend."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Atlanta Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"The experience at <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> was awesome from Yesi my pre-op coordinator. Talking to Yesi was like talking to a friend, she is so nice and welcoming. Best person in the building!"
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Miami Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>

		<!-- Slide 2 - Patient Reviews 4-6 -->
		<div class="carousel-item">
			<div class="row g-4 justify-content-center">
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"I can't say enough good things about my experience at this location—everything from the care I received to the amazing results. Nurse Vanessa was an absolute angel! She took such wonderful care of me after my procedure."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Atlanta Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"I had Lipo 360 and my experience has been a great one. When I came in Gabi answered all my questions, she joked around with me and just truly made me feel comfortable. My experience has been so smooth and well organized."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Miami Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"This is my second time having a procedure done with the amazing Dr. Wright at MIA. I cannot be more content with my choice. Dr Wright and her staff are all extremely helpful and supportive through it all."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Miami Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>

		<!-- Slide 3 - Patient Reviews 7-9 -->
		<div class="carousel-item">
			<div class="row g-4 justify-content-center">
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"I had my bbl with lipo 360 surgery this morning with Dr. Charepoo. He's super informative and delightful. After I woke up, Julia was the best recovery nurse. She spoke to me so gentle and reassuring."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Austin Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"Amazing experience from my first interaction with the staff. I couldn't have asked for better customer service. The staff, specifically Audrey have been great to me by answering all questions and concerns."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Miami Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"Vanessa was an excellent nurse. She cared for me during the recovery process and was very attentive to my needs. She was very kind and compassionate. She made my recovery run with ease."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Atlanta Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>

		<!-- Slide 4 - Patient Review 10 -->
		<div class="carousel-item">
			<div class="row g-4 justify-content-center">
			<div class="col-lg-4 col-md-6 col-12 d-flex">
				<div class="testimonial-card card h-100">
				<div class="testimonial-content card-body d-flex flex-column">
					<blockquote class="testimonial-quote flex-grow-1">
					"I cannot express enough gratitude for the incredible care and compassion Nurse Vanessa provided to me following my surgery. She was kind, professional and very attentive. Dr. Fasusi was also exceptional! He embodies everything a great plastic surgeon should be."
					</blockquote>
					<div class="testimonial-author mt-auto">
					<p class="author-name h5"><span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> Patient</p>
					<p class="author-location">Atlanta Location</p>
					</div>
					<div class="testimonial-stars mt-2">
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					<i class="fas fa-star" aria-hidden="true"></i>
					</div>
				</div>
				</div>
			</div>
			</div>
		</div>
		</div>

		<!-- Carousel Controls -->
		<button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
		</button>

		<!-- Carousel Indicators -->
		<div class="carousel-indicators">
		<button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
		<button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
		<button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
		<button type="button" data-bs-target="#testimonialsCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
		</div>
	</div>

	<!-- Testimonials CTA -->
	<div class="text-center mt-5">
		<a href="https://recruiting.paylocity.com/Recruiting/Jobs/All/450b9702-9d0a-45f8-9f93-340cc0200bab/Mia-Aesthetics-Services" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-lg">
		Join Our Team <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
		</a>
	</div>
	</div>
</section>
</main>

<!-- Custom Careers Footer -->
<footer class="careers-footer">
	<div class="container">
	<div class="row g-4">
		<!-- Column 1: Mission Statement -->
		<div class="col-lg-4 col-md-6">
		<div class="footer-column">
			<h3 class="footer-heading">Our Mission</h3>
			<p class="footer-text">
			<span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span> provides a unique opportunity for surgeons to champion our mission of delivering high-quality, safe plastic surgery at affordable prices, proving that beauty and cost-effectiveness can coexist.
			</p>
		</div>
		</div>

		<!-- Column 2: Surgeon Opportunities -->
		<div class="col-lg-4 col-md-6">
		<div class="footer-column">
			<h3 class="footer-heading">For Surgeons</h3>
			<p class="footer-text">
			Backed by a robust and experienced team, our surgeons are able to focus fully on providing exceptional patient care. Join our network of professionals dedicated to transforming lives.
			</p>
			<div class="contact-info">
			<div class="contact-item">
				<strong>Surgeon Inquiries:</strong>
			</div>
			<div class="contact-item">
				<a href="mailto:maggie@miaaesthetics.com" class="contact-link">
				<i class="fas fa-envelope" aria-hidden="true"></i> maggie@miaaesthetics.com
				</a>
			</div>
			<div class="contact-item mt-3">
				<small class="text-muted">Please send your curriculum vitae for consideration.</small>
			</div>
			</div>
		</div>
		</div>

		<!-- Column 3: Contact Information -->
		<div class="col-lg-4 col-md-12">
		<div class="footer-column">
			<h3 class="footer-heading">Join Our Team</h3>
			<p class="footer-text">
			Ready to make a difference in aesthetic medicine? We'd love to hear from you.
			</p>
			<div class="footer-links">
			<a href="<?php echo esc_url( home_url( '/locations/' ) ); ?>" class="footer-link">
				<i class="fas fa-map-marker-alt" aria-hidden="true"></i> Our Locations
			</a>
			<a href="<?php echo esc_url( home_url( '/plastic-surgeons/' ) ); ?>" class="footer-link">
				<i class="fas fa-user-md" aria-hidden="true"></i> Meet Our Team
			</a>
			</div>
		</div>
		</div>
	</div>

	<!-- Footer Bottom -->
	<div class="footer-bottom">
		<div class="row align-items-center">
		<div class="col-md-6">
			<div class="footer-logo">
			<?php
			$logo_url = mia_aesthetics_get_logo_url();
			if ( false !== $logo_url ) :
				?>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="footer-logo-img">
			<?php else : ?>
				<h3 class="footer-brand-text"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h3>
			<?php endif; ?>
			</div>
		</div>
		<div class="col-md-6 text-md-end">
			<div class="social-links">
				<?php mia_social_media_links( 'social-link' ); ?>
			</div>
		</div>
		</div>
		<hr class="footer-divider">
		<div class="copyright">
		<div class="text-center mb-3">
			<p class="mb-1">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <span class="mia-brand">Mia Aesthetics<span class="trademark">™</span></span>. All rights reserved.</p>
			<p class="disclaimer mb-0">The pictures on this website consist of both models and actual patients.</p>
		</div>
		
		<!-- Legal Links Section -->
		<div class="text-center">
			<nav aria-label="Legal and policy navigation">
			<div class="legal-links">
				<a href="<?php echo esc_url( home_url( '/website-privacy-policy/' ) ); ?>">Privacy Policy</a>
				<span class="link-separator">|</span>
				<a href="<?php echo esc_url( home_url( '/patient-privacy-practices/' ) ); ?>">Patient Privacy Practices</a>
				<span class="link-separator">|</span>
				<a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>">Terms & Conditions</a>
				<span class="link-separator">|</span>
				<a href="<?php echo esc_url( home_url( '/terms-of-use/' ) ); ?>">Terms of Use</a>
				<span class="link-separator">|</span>
				<a href="<?php echo esc_url( home_url( '/website-sms-terms-and-conditions/' ) ); ?>">SMS Terms & Conditions</a>
			</div>
			</nav>
		</div>
		</div>
	</div>
	</div>
</footer>

<?php
// Add careers CTA component.
require get_template_directory() . '/components/careers-cta.php';
?>

<?php wp_footer(); ?>
</body>
</html>
