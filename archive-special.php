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
				Free Virtual Consultation <i class="fas fa-arrow-right"></i>
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

			<?php
			// Query all specials
			$specials_args = array(
				'post_type'      => 'special',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
				'post_status'    => 'publish',
			);

			$specials_query = new WP_Query( $specials_args );

			if ( $specials_query->have_posts() ) :
				?>
				<!-- Category 1 Specials (English/Default) -->
				<div id="english-specials" class="specials-content">
					<div class="row">
						<?php
						while ( $specials_query->have_posts() ) :
							$specials_query->the_post();
							
							// Get the special categories for this post
							$categories = get_the_terms( get_the_ID(), 'special-category' );
							$has_category = false;
							
							if ( $categories && ! is_wp_error( $categories ) ) {
								// If post has any category, check if it's category 2
								foreach ( $categories as $category ) {
									$has_category = true;
									break;
								}
								// Check if this belongs to category 2 (spanish, español, espanol, or category-2)
								foreach ( $categories as $category ) {
									if ( in_array( $category->slug, array( 'spanish', 'español', 'espanol', 'category-2' ), true ) ) {
										continue 2; // Skip this post in category 1 section
									}
								}
							}
							
							// Display posts with no category or category-1/english
							?>
							<div class="col-lg-4 col-md-6 mb-4 special-item" data-category="category-1">
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
											Learn More <i class="fas fa-arrow-right"></i>
										</a>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				</div>

				<!-- Category 2 Specials (Spanish/Secondary) -->
				<div id="spanish-specials" class="specials-content" style="display: none;">
					<div class="row">
						<?php
						// Reset the query to loop through again for category 2 specials
						$specials_query->rewind_posts();
						
						while ( $specials_query->have_posts() ) :
							$specials_query->the_post();
							
							// Get the special categories for this post
							$categories = get_the_terms( get_the_ID(), 'special-category' );
							$is_category_2 = false;
							
							if ( $categories && ! is_wp_error( $categories ) ) {
								foreach ( $categories as $category ) {
									// Check if this belongs to category 2
									if ( in_array( $category->slug, array( 'spanish', 'español', 'espanol', 'category-2' ), true ) ) {
										$is_category_2 = true;
										break;
									}
								}
							}
							
							// Only show category 2 specials in this section
							if ( ! $is_category_2 ) {
								continue;
							}
							?>
							<div class="col-lg-4 col-md-6 mb-4 special-item" data-category="category-2">
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
											Learn More <i class="fas fa-arrow-right"></i>
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