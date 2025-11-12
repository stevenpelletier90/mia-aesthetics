<?php
/**
 * The front page template file
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>
<main id="primary" class="site-main">
<?php
// Include hero section.
require __DIR__ . '/hero-section.php';
?>

<!-- About Section - Centered Layout -->
<section class="about-section">
	<div class="container">
	<!-- Centered Content -->
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-8">
		<div class="about-content text-center">
			<h1 class="page-heading">Why Mia Aesthetics?</h1>
		</div>
		</div>
	</div>

	<!-- Custom Statistics Grid -->
	<div class="stats-grid">
		<!-- Founded Year -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="2017">0</div>
		<div class="homepage-stat-label">YEAR FOUNDED</div>
		</div>

		<!-- Expert Surgeons (Dynamic) -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="
		<?php
			$stats = mia_aesthetics_get_site_stats();
			echo esc_html( (string) $stats['surgeons'] );
		?>
		">0</div>
		<div class="homepage-stat-label">EXPERT SURGEONS</div>
		</div>

		<!-- Clinic Locations (Dynamic) -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="<?php echo esc_attr( (string) $stats['locations'] ); ?>">0</div>
		<div class="homepage-stat-label">CLINIC LOCATIONS</div>
		</div>

		<!-- Satisfied Patients -->
		<div class="stat-box text-center">
		<div class="homepage-stat-number" data-count="150" data-suffix="k+">0</div>
		<div class="homepage-stat-label">SATISFIED PATIENTS</div>
		</div>
	</div>

	<!-- Section Description -->
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-8">
		<div class="about-content text-center">
			<p class="section-description">Our mission at <strong>Mia Aesthetics</strong> is to deliver the highest quality of plastic surgery at affordable prices, demonstrating that beauty and cost-effectiveness can coexist harmoniously.</p>
		</div>
		</div>
	</div>
	</div>
</section>

<!-- Excellence Section -->
<section class="excellence-section">
	<div class="container">
	<div class="row align-items-center">
		<div class="col-lg-6  mb-lg-0">
		<div class="excellence-image">
			<img src="/wp-content/uploads/2025/05/surgery-1.jpg" 
				alt="Trusted Surgical Excellence" 
				class="img-fluid">
		</div>
		</div>
		<div class="col-lg-6">
		<div class="excellence-content">
			<p class="excellence-tagline">Our Commitment</p>
			<h2 class="text-white section-heading">Trusted Surgical Excellence</h2>
			<p class="section-description section-description--white">Delivering life-changing results with expert care at every step. Our team of highly skilled specialists, years of experience, and thousands of satisfied patients set us apart in the industry.</p>
			<div class="excellence-buttons">
			<a href="/locations/" class="btn btn-primary me-3" role="button">Our Locations</a>
			<a href="/plastic-surgeons/" class="btn btn-outline-primary-alt2" role="button">Our Surgeons</a>
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
		<source src="/wp-content/uploads/2025/09/home-video.mp4" type="video/mp4">
		Your browser does not support the video tag.
	</video>
	</div>
  
	<!-- Content Overlay -->
	<div class="video-content-overlay position-absolute top-0 start-0 w-100 h-100 d-none d-md-flex align-items-center">
	<div class="container">
		<div class="row justify-content-center">
		<div class="col-lg-8 text-center">
			<h2 class="text-white  section-heading">Experience Excellence</h2>
			<p class="lead text-white ">Join thousands of satisfied patients who have transformed their lives at Mia Aesthetics</p>
			<a href="/free-plastic-surgery-consultation/" class="btn btn-primary btn-lg" role="button">Free Virtual Consultation</a>
		</div>
		</div>
	</div>
	</div>
</section>

<!-- Procedures Tabbed Section -->
<section class="procedures-section">
	<div class="container">
	<div class="row mb-5 text-center">
		<div class="col-12">
		<h2 class="section-heading">Our Procedures</h2>
		<p class="section-description">Discover the perfect procedure for your aesthetic goals</p>
		</div>
	</div>

	<!-- Procedure Navigation Tabs -->
	<div class="row">
		<div class="col-12">
		<!-- Desktop: tabs with arrows (≥768px) -->
		<div class="procedure-tabs-shell d-none d-md-flex align-items-center">
			<!-- Arrow – Previous -->
			<button class="procedure-nav-arrow prev-arrow me-2" type="button" aria-label="Previous procedure category">
			<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
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
			<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
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
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$body_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/04/body-home.jpg' );
			if ( 0 !== $body_img_id ) {
				echo wp_get_attachment_image(
					$body_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Body Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="/wp-content/uploads/2025/04/body-home.jpg" alt="Body Procedures" class="img-fluid rounded shadow">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline ">SCULPT YOUR IDEAL FIGURE</p>
			<h3 class=" section-heading">Body Contouring</h3>
			<p class="section-description ">Our body contouring procedures help you achieve the silhouette you desire. Whether you're looking to remove excess fat, tighten loose skin, or enhance your curves, our specialists can help you reach your aesthetic goals.</p>
			
			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/body/awake-liposuction/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Awake Lipo
					</a>
					<a href="/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brazilian Butt Lift (BBL)
					</a>
					<a href="/cosmetic-plastic-surgery/body/lipo-360/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Lipo 360
					</a>
				</div>
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/body/mommy-makeover/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mommy Makeover
					</a>
					<a href="/cosmetic-plastic-surgery/body/tummy-tuck/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Tummy Tuck
					</a>
					<a href="/cosmetic-plastic-surgery/body/arm-lift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Arm Lift
					</a>
				</div>
				</div>
				<a href="/cosmetic-plastic-surgery/body/" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Body Procedures
				</a>
			</div>
			</div>
		</div>
		</div>
	  
		<!-- Breast Content -->
		<div class="tab-pane fade" id="breast-content" role="tabpanel" aria-labelledby="breast-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$breast_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/04/breast-home.jpg' );
			if ( 0 !== $breast_img_id ) {
				echo wp_get_attachment_image(
					$breast_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Breast Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="/wp-content/uploads/2025/04/breast-home.jpg" alt="Breast Procedures" class="img-fluid rounded shadow">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline ">ACHIEVE YOUR DESIRED LOOK</p>
			<h3 class=" section-heading">Breast Enhancement</h3>
			<p class="section-description ">Our breast procedures are designed to enhance, reduce, or lift your breasts to achieve your desired appearance. Our board-certified surgeons use the latest techniques to deliver natural-looking results with minimal scarring.</p>
			
			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/breast/augmentation-implants/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Augmentation
					</a>
					<a href="/cosmetic-plastic-surgery/breast/lift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Lift
					</a>
					<a href="/cosmetic-plastic-surgery/breast/reduction/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Reduction
					</a>
				</div>
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/breast/implant-revision-surgery/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Breast Implant Revision
					</a>                  
					<a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures
					</a>
				</div>
				</div>
				<a href="/cosmetic-plastic-surgery/breast/" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Breast Procedures
				</a>
			</div>
			</div>
		</div>
		</div>
	  
		<!-- Face Content -->
		<div class="tab-pane fade" id="face-content" role="tabpanel" aria-labelledby="face-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$face_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/04/face-2-home.jpg' );
			if ( 0 !== $face_img_id ) {
				echo wp_get_attachment_image(
					$face_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Facial Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="/wp-content/uploads/2025/04/face-2-home.jpg" alt="Facial Procedures" class="img-fluid rounded shadow">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline ">ENHANCE YOUR NATURAL BEAUTY</p>
			<h3 class=" section-heading">Facial Rejuvenation</h3>
			<p class="section-description ">Our facial procedures are designed to enhance your natural features and restore youthful appearance. From facelifts to rhinoplasty, our board-certified surgeons use the latest techniques to deliver exceptional results.</p>
			
			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/face/brow-lift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Brow Lift
					</a>
					<a href="/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Buccal Fat Removal
					</a>
					<a href="/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Eyelid Lift
					</a>
				</div>
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/face/facelift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Facelift
					</a>
					<a href="/cosmetic-plastic-surgery/face/mini-facelift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Mini Facelift
					</a>
					<a href="/cosmetic-plastic-surgery/face/neck-lift/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Neck Lift
					</a>
				</div>
				</div>
				<a href="/cosmetic-plastic-surgery/face/" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Facial Procedures
				</a>
			</div>
			</div>
		</div>
		</div>
	  
		<!-- Non-Surgical Content -->
		<div class="tab-pane fade" id="nonsurgical-content" role="tabpanel" aria-labelledby="nonsurgical-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$nonsurg_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/04/face-home.jpg' );
			if ( 0 !== $nonsurg_img_id ) {
				echo wp_get_attachment_image(
					$nonsurg_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => 'Non-Surgical Procedures',
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="/wp-content/uploads/2025/04/face-home.jpg" alt="Non-Surgical Procedures" class="img-fluid rounded shadow">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline ">REJUVENATE WITHOUT SURGERY</p>
			<h3 class=" section-heading">Non-Surgical Treatments</h3>
			<p class="section-description ">Our non-surgical treatments offer remarkable results with minimal downtime. From injectables to laser therapies, we provide a range of options to address your concerns without the need for surgery.</p>
			
			<div class="procedure-links ">
				<div class="row">
		
				<div class="col-md-6">
				<a href="/non-surgical/j-plasma-skin-tightening/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> J-Plasma
					</a>
					<a href="/weight-loss/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Weight Loss
					</a>
				</div>
				</div>
				<a href="/non-surgical/" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Non-Surgical Treatments
				</a>
			</div>
			</div>
		</div>
		</div>
	  
		<!-- Men Content -->
		<div class="tab-pane fade" id="men-content" role="tabpanel" aria-labelledby="men-tab">
		<div class="row align-items-center">
			<div class="col-12 col-xl-6 order-xl-2  mb-xl-0">
			<?php
			$men_img_id = attachment_url_to_postid( '/wp-content/uploads/2025/04/men-home.jpg' );
			if ( 0 !== $men_img_id ) {
				echo wp_get_attachment_image(
					$men_img_id,
					'large',
					false,
					array(
						'class' => 'img-fluid rounded shadow',
						'alt'   => "Men's Procedures",
						'sizes' => '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 600px',
					)
				);
			} else {
				// Fallback if image ID can't be found.
				echo '<img src="/wp-content/uploads/2025/04/men-home.jpg" alt="Men\'s Procedures" class="img-fluid rounded shadow">';
			}
			?>
			</div>
			<div class="col-12 col-xl-6 order-xl-1">
			<p class="tagline ">TAILORED SOLUTIONS FOR MEN</p>
			<h3 class=" section-heading">Men's Procedures</h3>
			<p class="section-description ">Our men's procedures are specifically designed to address the unique concerns and aesthetic goals of our male patients. From body contouring to facial rejuvenation, we offer a range of treatments to help you look and feel your best.</p>
			
			<div class="procedure-links ">
				<div class="row">
				<div class="col-md-6">
					<a href="/cosmetic-plastic-surgery/body/male-bbl/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Brazilian Butt Lift (BBL)
					</a>
					<a href="/cosmetic-plastic-surgery/breast/male-breast-procedures/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Breast Procedures
					</a>
				 
				</div>
				<div class="col-md-6">
				<a href="/cosmetic-plastic-surgery/body/male-liposuction/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Liposuction
					</a>
					<a href="/cosmetic-plastic-surgery/body/male-tummy-tuck/" class="procedure-link">
					<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> Male Tummy Tuck
					</a>
			   
				</div>
				</div>
				<a href="/cosmetic-plastic-surgery/men/" class="procedure-link">
				<i class="fa-solid fa-arrow-right" aria-hidden="true"></i> View All Men's Procedures
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
	<div class="foundation-bg d-none d-md-block"></div>
  
	<!-- Regular image for mobile -->
	<div class="foundation-mobile-img d-md-none">
	<img src="/wp-content/uploads/2025/04/miaf6.jpg" alt="Mia Aesthetics Foundation" class="img-fluid w-100">
	</div>
  
	<div class="container position-relative">
	<div class="row">
		<div class="col-12">
		<div class="foundation-content text-center">
			<p class="foundation-tagline ">GIVING BACK TO OUR COMMUNITY</p>
			<h2 class=" section-heading">The Mia Aesthetics Foundation</h2>
			<p class="section-description section-description--white ">We're committed to making a positive impact in our communities through charitable initiatives, education, and outreach programs that help those in need.</p>
			<a href="/mia-foundation/" class="btn btn-primary" role="button" aria-label="Discover Mia Aesthetics Foundation charitable work"> See Our Impact <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
		</div>
		</div>
	</div>
	</div>
</section>


</main>
<?php get_footer(); ?>
