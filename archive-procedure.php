<?php
/**
 * Procedure Archive Template
 * Organizes procedures into categories: Body, Breast, Face, and Men
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	
	<!-- Archive Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1 class="mb-2">Our Procedures</h1>
					<p class="lead mb-0">Our highly skilled surgical team is actualizing dreams, facilitating one transformation at a time.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Procedure Categories -->
	<section class="procedure-categories py-5">
		<div class="container-fluid px-4">
			<div class="row g-4">
				<!-- Body Procedures -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-12">
					<div class="card h-100 procedure-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/body-home.jpg?w=400&h=300&crop=1' ) ); ?>"
										width="400" height="300">
								<source media="(max-width: 768px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/body-home.jpg?w=600&h=400&crop=1' ) ); ?>"
										width="600" height="400">
								<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/body-home.jpg?w=800&h=600&crop=1' ) ); ?>"
									alt="Body Procedures - Transform your body with our comprehensive surgical options"
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">11 Procedures</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3"><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/' ) ); ?>" class="procedure-category-heading">Body Procedures</a></h2>
							<p class="card-text mb-3">Sculpt and contour your body with our advanced surgical techniques.</p>
							<ul class="list-unstyled procedure-links">
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/mia-corset/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Mia Waist Corset&trade;</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/awake-liposuction/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Awake Lipo</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/circumferential-body-lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Body Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Brazilian Butt Lift (BBL)</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/lipo-360/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Lipo 360</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/liposuction/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Liposuction</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/tummy-tuck/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Tummy Tuck</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/mommy-makeover/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Mommy Makeover</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/arm-lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Arm Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/thigh-lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Thigh Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/labiaplasty-labia-reduction-vaginal-rejuvenation/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Vaginal Rejuvenation</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/' ) ); ?>" class="text-decoration-none view-all-link"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>View All Body Procedures</span></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- Breast Procedures -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-12">
					<div class="card h-100 procedure-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/breast-home.jpg?w=400&h=300&crop=1' ) ); ?>"
										width="400" height="300">
								<source media="(max-width: 768px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/breast-home.jpg?w=600&h=400&crop=1' ) ); ?>"
										width="600" height="400">
								<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/breast-home.jpg?w=800&h=600&crop=1' ) ); ?>"
									alt="Breast Procedures - Enhance and reshape with our expert breast surgery options"
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">4 Procedures</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3"><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/' ) ); ?>" class="procedure-category-heading">Breast Procedures</a></h2>
							<p class="card-text mb-3">Enhance, reduce, or lift with our comprehensive breast surgery options.</p>
							<ul class="list-unstyled procedure-links">
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/augmentation-implants/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Augmentation</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/reduction/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Reduction</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/implant-revision-surgery/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Implant Revision</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/' ) ); ?>" class="text-decoration-none view-all-link"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>View All Breast Procedures</span></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- Face Procedures -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-12">
					<div class="card h-100 procedure-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg?w=400&h=300&crop=1' ) ); ?>"
										width="400" height="300">
								<source media="(max-width: 768px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg?w=600&h=400&crop=1' ) ); ?>"
										width="600" height="400">
								<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/face-2-home.jpg?w=800&h=600&crop=1' ) ); ?>"
									alt="Face Procedures - Rejuvenate and refine your facial features with precision surgery"
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">9 Procedures</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3"><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/' ) ); ?>" class="procedure-category-heading">Face Procedures</a></h2>
							<p class="card-text mb-3">Rejuvenate and refine your facial features with precision techniques.</p>
							<ul class="list-unstyled procedure-links">
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/brow-lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Brow Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Buccal Fat Removal</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Blepharoplasty</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/chin-lipo/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Chin Lipo</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/facelift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Facelift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/mini-facelift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Mini Facelift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/neck-lift/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Neck Lift</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/ear-pinning-otoplasty/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Otoplasty</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/nose-job-rhinoplasty/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Rhinoplasty</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/face/' ) ); ?>" class="text-decoration-none view-all-link"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>View All Face Procedures</span></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- Men's Procedures -->
				<div class="col-xl-3 col-lg-6 col-md-6 col-12">
					<div class="card h-100 procedure-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/men-home.jpg?w=400&h=300&crop=1' ) ); ?>"
										width="400" height="300">
								<source media="(max-width: 768px)"
										srcset="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/men-home.jpg?w=600&h=400&crop=1' ) ); ?>"
										width="600" height="400">
								<img src="<?php echo esc_url( home_url( '/wp-content/uploads/2025/04/men-home.jpg?w=800&h=600&crop=1' ) ); ?>"
									alt="Male Procedures - Specialized cosmetic surgery options designed for men"
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">4 Procedures</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3"><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/' ) ); ?>" class="procedure-category-heading">Men's Procedures</a></h2>
							<p class="card-text mb-3">Specialized cosmetic surgery options designed specifically for men.</p>
							<ul class="list-unstyled procedure-links">
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-bbl/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Male BBL</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/breast/male-breast-procedures/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Male Breast Procedures</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-liposuction/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Male Liposuction</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/body/male-tummy-tuck/' ) ); ?>" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Male Tummy Tuck</span></a></li>
								<li><a href="<?php echo esc_url( home_url( '/cosmetic-plastic-surgery/' ) ); ?>" class="text-decoration-none view-all-link"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>View All Male Procedures</span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Featured Video Section -->
	<section class="procedure-videos py-5 bg-light">
		<div class="container">
			<div class="row mb-5">
				<div class="col-12 text-center">
					<h2 class="section-heading mb-3">Watch Our Featured Videos</h2>
					<p class="lead text-muted">Learn more about our procedures, results, and patient experiences.</p>
				</div>
			</div>
			<?php
			// All testimonial videos loaded in a single array for consistency.
			$video_data = array(
				array(
					'id'    => 'OxigXlYTqH8',
					'title' => 'Featured Video 1',
				),
				array(
					'id'    => 'sb8Kapy8mzU',
					'title' => 'Featured Video 2',
				),
				array(
					'id'    => '4-B_ISCne28',
					'title' => 'Featured Video 3',
				),
				array(
					'id'    => 'ykz9Z8Kh3Yo',
					'title' => 'Featured Video 4',
				),
				array(
					'id'    => 'oxp3W3KY0hc',
					'title' => 'Featured Video 5',
				),
				array(
					'id'    => 'b6b0KaW2cXE',
					'title' => 'Featured Video 6',
				),
			);
			?>
			<div class="row g-4">
				<?php foreach ( $video_data as $video ) : ?>
					<div class="col-lg-4 col-md-6 col-12">
						<div class="card h-100 procedure-video-card">
							<div class="ratio ratio-16x9 rounded-top overflow-hidden">
								<iframe 
									src="https://www.youtube.com/embed/<?php echo esc_attr( $video['id'] ); ?>" 
									title="<?php echo esc_attr( $video['title'] ); ?>" 
									allowfullscreen
									loading="lazy"
									tabindex="0"
									aria-label="<?php echo esc_attr( $video['title'] ); ?> video"></iframe>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>


</main>

<?php get_footer(); ?>
