<?php
/**
 * Case Archive Template
 * Organizes case studies into categories with procedure archive styling
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
					<h1 class="mb-2">Case Studies</h1>
					<p class="lead mb-0">Explore real patient transformations and see the exceptional results achieved by our skilled surgical team.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Browse by Surgeon CTA -->
	<section class="browse-by-surgeon py-5 bg-light">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 text-center">
					<h2 class="h3 mb-3">Browse Results by Surgeon</h2>
					<p class="lead text-muted mb-4">Explore transformations organized by our expert surgical team members to see their specialized work and artistic approach.</p>
					<a href="/before-after/before-after-by-doctor/" class="btn btn-primary btn-lg">
						View Results by Surgeon <i class="fas fa-arrow-right"></i>
					</a>
				</div>
			</div>
		</div>
	</section>
	<!-- Case Categories -->
	<section class="case-categories py-5">
		<div class="container">
			<div class="row g-4 justify-content-center">
				<!-- Body Cases -->
				<div class="col-xl-6 col-lg-6 col-md-6 col-12">
					<div class="card h-100 case-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)" 
										srcset="/wp-content/uploads/2025/05/body-procedures.jpg?w=400&h=300&crop=1" 
										width="400" height="300">
								<source media="(max-width: 768px)" 
										srcset="/wp-content/uploads/2025/05/body-procedures.jpg?w=600&h=400&crop=1" 
										width="600" height="400">
								<img src="/wp-content/uploads/2025/05/body-procedures.jpg?w=800&h=600&crop=1" 
									alt="Body Case Studies - Real patient transformations for body procedures" 
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">25 Cases</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3">Body Cases</h2>
							<p class="card-text mb-3">See real transformations from our body contouring and sculpting procedures.</p>
							<ul class="list-unstyled case-links">
								<li><a href="/before-after/bbl/"><span>Brazilian Butt Lift (BBL) Cases</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
								<li><a href="/before-after/lipo-360/"><span>Lipo 360 Results</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
								<li><a href="/before-after/mommy-makeover/"><span>Mommy Makeover Cases</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
								<li><a href="/before-after/tummy-tuck/"><span>Tummy Tuck Results</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
				
				<!-- Breast Cases -->
				<div class="col-xl-6 col-lg-6 col-md-6 col-12">
					<div class="card h-100 case-category-card">
						<div class="category-image-container position-relative overflow-hidden">
							<picture>
								<source media="(max-width: 576px)" 
										srcset="/wp-content/uploads/2025/05/breast-procedures.jpg?w=400&h=300&crop=1" 
										width="400" height="300">
								<source media="(max-width: 768px)" 
										srcset="/wp-content/uploads/2025/05/breast-procedures.jpg?w=600&h=400&crop=1" 
										width="600" height="400">
								<img src="/wp-content/uploads/2025/05/breast-procedures.jpg?w=800&h=600&crop=1" 
									alt="Breast Case Studies - Real patient transformations for breast procedures" 
									class="card-img-top w-100 h-100 object-fit-cover"
									width="800" height="600"
									loading="lazy">
							</picture>
							<div class="category-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
								<div class="overlay-content p-3 text-white w-100">
									<span class="category-badge px-2 py-1 rounded-pill small">18 Cases</span>
								</div>
							</div>
						</div>
						<div class="card-body p-4">
							<h2 class="h4 mb-3">Breast Cases</h2>
							<p class="card-text mb-3">Explore real results from our comprehensive breast surgery procedures.</p>
							<ul class="list-unstyled case-links">
								<li><a href="/before-after/breast-augmentation/"><span>Breast Augmentation Cases</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
								<li><a href="/before-after/breast-reduction/"><span>Breast Reduction Results</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
								<li><a href="/before-after/breast-lift/"><span>Breast Lift Transformations</span> <i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
