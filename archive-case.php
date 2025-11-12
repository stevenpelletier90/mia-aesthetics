<?php
/**
 * Case Archive Template
 * Organizes case studies into categories with procedure archive styling
 *
 * @package Mia_Aesthetics
 */

get_header();

// Preload the hero background image for faster LCP.
add_action(
	'wp_head',
	function () {
		echo '<link rel="preload" as="image" href="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=1600&h=600&fit=crop" fetchpriority="high">';
	},
	1
);
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<!-- Browse by Surgeon Header -->
	<section class="browse-by-surgeon position-relative">
		<div class="browse-by-surgeon-overlay"></div>
		<div class="container position-relative">
			<div class="row justify-content-center">
				<div class="col-lg-8 text-center browse-by-surgeon-content">
					<!-- Stacked Surgeon Avatars -->
					<div class="surgeon-avatars mb-4">
						<?php
						// Get all surgeon IDs and randomize in PHP to bypass caching.
						$all_surgeon_ids = get_posts(
							array(
								'post_type'        => 'surgeon',
								'posts_per_page'   => -1,
								'post_status'      => 'publish',
								'fields'           => 'ids',
								'orderby'          => 'none',
								'suppress_filters' => true,
							)
						);

						// Shuffle the array in PHP for true randomization.
						if ( ! empty( $all_surgeon_ids ) && is_array( $all_surgeon_ids ) ) :
							shuffle( $all_surgeon_ids );
							// Get only the first 6 surgeons.
							$random_surgeon_ids = array_slice( $all_surgeon_ids, 0, 6 );

							foreach ( $random_surgeon_ids as $surgeon_id ) :
								$headshot_id   = get_field( 'surgeon_headshot', $surgeon_id );
								$surgeon_title = get_the_title( $surgeon_id );

								// Display headshot if ID exists.
								if ( null !== $headshot_id && '' !== $headshot_id && is_numeric( $headshot_id ) ) :
									$alt_text = get_post_meta( (int) $headshot_id, '_wp_attachment_image_alt', true );
									echo wp_get_attachment_image(
										(int) $headshot_id,
										'thumbnail',
										false,
										array(
											'class'   => 'surgeon-avatar',
											'alt'     => ( ! empty( $alt_text ) && is_string( $alt_text ) ) ? $alt_text : $surgeon_title,
											'loading' => 'eager',
											'fetchpriority' => 'high',
										)
									);
									// Fallback to post thumbnail if headshot field is empty but thumbnail exists.
								elseif ( has_post_thumbnail( $surgeon_id ) ) :
									$thumbnail_id = get_post_thumbnail_id( $surgeon_id );
									$alt_text     = get_post_meta( (int) $thumbnail_id, '_wp_attachment_image_alt', true );
									echo get_the_post_thumbnail(
										$surgeon_id,
										'thumbnail',
										array(
											'class'   => 'surgeon-avatar',
											'alt'     => ( ! empty( $alt_text ) && is_string( $alt_text ) ) ? $alt_text : $surgeon_title,
											'loading' => 'eager',
											'fetchpriority' => 'high',
										)
									);
								endif;
							endforeach;
						endif;
						?>
					</div>

					<h1 class="mb-3">Before & After Results by Surgeon</h1>
					<p class="lead mb-4">Explore transformations organized by our expert surgical team members to see their specialized work and artistic approach.</p>
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
								<li><a href="/before-after/bbl/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Brazilian Butt Lift (BBL) Cases</span></a></li>
								<li><a href="/before-after/lipo-360/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Lipo 360 Results</span></a></li>
								<li><a href="/before-after/mommy-makeover/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Mommy Makeover Cases</span></a></li>
								<li><a href="/before-after/tummy-tuck/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Tummy Tuck Results</span></a></li>
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
								<li><a href="/before-after/breast-augmentation/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Augmentation Cases</span></a></li>
								<li><a href="/before-after/breast-reduction/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Reduction Results</span></a></li>
								<li><a href="/before-after/breast-lift/" class="text-decoration-none"><i class="fas fa-chevron-right me-2" aria-hidden="true"></i><span>Breast Lift Transformations</span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
