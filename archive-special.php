<?php
/**
 * The template for displaying the Specials archive page.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<div class="specials-archive-page">

	<!-- 1. Hero Section -->
	<header class="specials-hero text-center">
		<div class="container">
			<h1 class="display-4 fw-bold">Current Specials</h1>
			<p class="lead mb-4">Limited-time offers on your favorite treatments</p>
			<a href="/free-virtual-consultation/" class="btn btn-primary btn-lg">
				Free Virtual Consultation <i class="fas fa-arrow-right" aria-hidden="true"></i>
			</a>
		</div>
	</header>

	<!-- 2. Language Toggle -->
	<div class="specials-language-toggle py-3">
		<div class="container">
			<div class="btn-group" role="group" aria-label="Language filter">
				<button type="button" class="btn btn-primary active" data-lang="english" id="english-tab">English</button>
				<button type="button" class="btn btn-outline-primary" data-lang="spanish" id="spanish-tab">Español</button>
			</div>
		</div>
	</div>

	<!-- 3. Specials Archive Grid -->
	<main class="specials-grid py-5">
		<div class="container">

			<!-- Sticky/Featured Specials - Always Visible -->
			<div class="featured-specials-section mb-5">
				<div class="row">
					<!-- Skinny Shot Special -->
					<div class="col-lg-6 col-md-6 mb-4">
						<div class="special-card sticky-special">
							<div class="featured-badge">
								<span>Featured</span>
							</div>
							<div class="special-card-image">
								<a href="/weight-loss/">
									<img src="/wp-content/uploads/2025/08/Skinny-Shot-1200x630px.jpg" alt="Skinny Shot Special" class="img-fluid">
								</a>
							</div>
							<div class="special-card-body">
								<h3 class="special-card-title">
									<a href="/weight-loss/">Skinny Shot Special</a>
								</h3>
								<div class="special-card-excerpt">
									<p>Transform your weight loss journey with our revolutionary Skinny Shot treatment. Safe, effective, and physician-supervised.</p>
								</div>
								<a href="/weight-loss/" class="btn btn-primary">
									Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
					<!-- J-Plasma Special -->
					<div class="col-lg-6 col-md-6 mb-4">
						<div class="special-card sticky-special">
							<div class="featured-badge">
								<span>Featured</span>
							</div>
							<div class="special-card-image">
								<a href="/non-surgical/j-plasma-skin-tightening/">
									<img src="/wp-content/uploads/2025/08/J-Plasma-1200x630px.jpg" alt="J-Plasma Skin Tightening Special" class="img-fluid">
								</a>
							</div>
							<div class="special-card-body">
								<h3 class="special-card-title">
									<a href="/non-surgical/j-plasma-skin-tightening/">J-Plasma Skin Tightening</a>
								</h3>
								<div class="special-card-excerpt">
									<p>Revolutionary skin tightening technology for dramatic results without invasive surgery. Tighten and rejuvenate your skin today.</p>
								</div>
								<a href="/non-surgical/j-plasma-skin-tightening/" class="btn btn-primary">
									Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php
			// Query all specials.
			$specials_args = array(
				'post_type'      => 'special',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'post_status'    => 'publish',
			);

			$specials_query = new WP_Query( $specials_args );

			if ( $specials_query->have_posts() ) :
				?>
				<!-- English Specials -->
				<div id="english-specials" class="specials-content">
					<div class="row">
						<?php
						while ( $specials_query->have_posts() ) :
							$specials_query->the_post();

							// Get the special categories for this post.
							$current_post_id = get_the_ID();
							$categories      = false !== $current_post_id ? get_the_terms( $current_post_id, 'special-category' ) : false;
							$is_english      = false;

							if ( is_array( $categories ) ) {
								// Check if this special has the english-specials category.
								foreach ( $categories as $category ) {
									if ( 'english-specials' === $category->slug ) {
										$is_english = true;
										break;
									}
								}
							}

							// Only show English specials in this section.
							if ( ! $is_english ) {
								continue;
							}
							?>
							<div class="col-lg-6 col-md-6 mb-4 special-item" data-category="category-1">
								<div class="special-card">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="special-card-image">
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid' ) ); ?>
											</a>
										</div>
									<?php endif; ?>
									<div class="special-card-body">
										<h3 class="special-card-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<?php if ( has_excerpt() ) : ?>
											<div class="special-card-excerpt">
												<?php the_excerpt(); ?>
											</div>
										<?php endif; ?>
										<a href="<?php the_permalink(); ?>" class="btn btn-primary">
											Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
										</a>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>

				<!-- Spanish Specials -->
				<div id="spanish-specials" class="specials-content" style="display: none;">
					<div class="row">
						<?php
						// Reset the query to loop through again for Spanish specials.
						$specials_query->rewind_posts();

						while ( $specials_query->have_posts() ) :
							$specials_query->the_post();

							// Get the special categories for this post.
							$current_post_id = get_the_ID();
							$categories      = ( false !== $current_post_id ) ? get_the_terms( $current_post_id, 'special-category' ) : false;
							$is_spanish      = false;

							if ( is_array( $categories ) ) {
								// Check if this special has the spanish-specials category.
								foreach ( $categories as $category ) {
									if ( 'spanish-specials' === $category->slug ) {
										$is_spanish = true;
										break;
									}
								}
							}

							// Only show Spanish specials in this section.
							if ( ! $is_spanish ) {
								continue;
							}
							?>
							<div class="col-lg-6 col-md-6 mb-4 special-item" data-category="category-2">
								<div class="special-card">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="special-card-image">
											<a href="<?php the_permalink(); ?>">
												<?php the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid' ) ); ?>
											</a>
										</div>
									<?php endif; ?>
									<div class="special-card-body">
										<h3 class="special-card-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h3>
										<?php if ( has_excerpt() ) : ?>
											<div class="special-card-excerpt">
												<?php the_excerpt(); ?>
											</div>
										<?php endif; ?>
										<a href="<?php the_permalink(); ?>" class="btn btn-primary">
											Learn More <i class="fas fa-arrow-right" aria-hidden="true"></i>
										</a>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>

				<?php wp_reset_postdata(); ?>

			<?php else : ?>
				<div class="text-center py-5">
					<h2>No specials available at this time</h2>
					<p>Please check back soon for our latest offers.</p>
				</div>
			<?php endif; ?>

		</div> <!-- .container -->
	</main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>
