<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * This template is used when WordPress cannot find a page or post
 * that matches the requested URL.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" class="site-main error-404">
<?php mia_aesthetics_breadcrumbs(); ?>
	<!-- Page Header -->
	<section class="post-header py-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8 text-center">
					<h1 class="display-1 fw-bold mb-4 error-number">404</h1>
					<h2 class="h3 mb-4">Page Not Found</h2>
					<p class="lead mb-0">
						The page you are looking for might have been removed, had its name changed,
						or is temporarily unavailable.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Content -->
	<section class="py-5">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6">
					<!-- Search Form -->
					<div class="search-form-404 text-center">
						<h3 class="h5 mb-4">Search Our Website</h3>
						<div class="search-wrapper mb-5">
							<?php get_search_form(); ?>
						</div>
						
						<!-- Back Button -->
						<div class="back-button">
							<button onclick="history.back()" class="back-link">
								<i class="fas fa-arrow-left me-2" aria-hidden="true"></i> Go Back to Previous Page
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
