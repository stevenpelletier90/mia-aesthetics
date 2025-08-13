<?php
/**
 * The template for displaying the Specials archive page.
 *
 * @package Mia_Aesthetics
 */

get_header();

// Get current language filter from URL parameter, default to English.
$current_language = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : 'english';
?>

<div class="specials-archive-page">

	<!-- 1. Hero Section -->
	<header class="specials-hero text-center">
		<div class="container">
			<h1 class="display-4 fw-bold">Current Specials</h1>
			<p class="lead mb-4">Limited-time offers on your favorite treatments</p>
			<a href="/free-virtual-consultation/" class="mia-button" data-variant="gold" data-size="lg">
				Free Virtual Consultation <i class="fas fa-arrow-right"></i>
			</a>
		</div>
	</header>

	<!-- 2. Language Toggle -->
	<div class="specials-language-toggle py-3">
		<div class="container">
			<div class="btn-group" role="group" aria-label="Language filter">
				<button type="button" class="btn <?php echo 'english' === $current_language ? 'btn-gold active' : 'btn-outline-gold'; ?>" 
						data-lang="english">English</button>
				<button type="button" class="btn <?php echo 'spanish' === $current_language ? 'btn-gold active' : 'btn-outline-gold'; ?>" 
						data-lang="spanish">Español</button>
				<button type="button" class="btn <?php echo 'all' === $current_language ? 'btn-gold active' : 'btn-outline-gold'; ?>" 
						data-lang="all">All / Todos</button>
			</div>
		</div>
	</div>

	<!-- 3. Specials Archive Grid -->
	<main class="specials-grid py-5">
		<div class="container">

			<?php
			// Build tax query based on language selection.
			$tax_query = array();
			if ( 'english' === $current_language ) {
				$tax_query = array(
					array(
						'taxonomy' => 'special-category',
						'field'    => 'slug',
						'terms'    => 'english-specials',
					),
				);
			} elseif ( 'spanish' === $current_language ) {
				$tax_query = array(
					array(
						'taxonomy' => 'special-category',
						'field'    => 'slug',
						'terms'    => 'spanish-specials',
					),
				);
			}
			// If 'all', no tax query needed.

			// First Query: Sticky Specials.
			$sticky_args  = array(
				'post_type'      => 'special',
				'posts_per_page' => -1,
				'meta_key'       => 'is_sticky',
				'meta_value'     => '1',
				'tax_query'      => $tax_query,
			);
			$sticky_query = new WP_Query( $sticky_args );

			// Second Query: Regular Specials.
			$regular_args  = array(
				'post_type'      => 'special',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'OR',
					array(
						'key'     => 'is_sticky',
						'value'   => '0',
						'compare' => '=',
					),
					array(
						'key'     => 'is_sticky',
						'compare' => 'NOT EXISTS',
					),
				),
				'tax_query'      => $tax_query,
			);
			$regular_query = new WP_Query( $regular_args );

			$has_specials = $sticky_query->have_posts() || $regular_query->have_posts();
			?>

			<?php if ( $has_specials ) : ?>

				<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 g-lg-5">

					<?php
					// Display Sticky Specials First.
					if ( $sticky_query->have_posts() ) :
						while ( $sticky_query->have_posts() ) :
							$sticky_query->the_post();
							$is_spanish = has_term( 'spanish-specials', 'special-category' );
							?>

							<div class="col">
								<article class="special-card h-100 sticky-special">
									<div class="featured-badge">
										<span><?php echo $is_spanish ? 'Destacado' : 'Featured'; ?></span>
									</div>
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="special-card-image">
											<a href="<?php the_permalink(); ?>"
												aria-label="View <?php echo esc_attr( get_the_title() ); ?>">
												<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
											</a>
										</div>
									<?php endif; ?>
									<div class="special-card-body">
										<?php
										$permalink = get_permalink();
										if ( false !== $permalink ) :
											the_title(
												'<h2 class="special-card-title"><a href="' . esc_url( $permalink ) . '">',
												'</a></h2>'
											);
										else :
											the_title( '<h2 class="special-card-title">', '</h2>' );
										endif;
										?>
										<div class="special-card-excerpt">
											<?php the_excerpt(); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold">
											<?php echo $is_spanish ? 'Ver Oferta' : 'View Special'; ?> 
											<i class="fas fa-arrow-right"></i>
										</a>
									</div>
								</article>
							</div>

							<?php
						endwhile;
						wp_reset_postdata();
					endif;

					// Display Regular Specials.
					if ( $regular_query->have_posts() ) :
						while ( $regular_query->have_posts() ) :
							$regular_query->the_post();
							$is_spanish = has_term( 'spanish-specials', 'special-category' );
							?>

							<div class="col">
								<article class="special-card h-100">
									<?php if ( has_post_thumbnail() ) : ?>
										<div class="special-card-image">
											<a href="<?php the_permalink(); ?>"
												aria-label="View <?php echo esc_attr( get_the_title() ); ?>">
												<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
											</a>
										</div>
									<?php endif; ?>
									<div class="special-card-body">
										<?php
										$permalink = get_permalink();
										if ( false !== $permalink ) :
											the_title(
												'<h2 class="special-card-title"><a href="' . esc_url( $permalink ) . '">',
												'</a></h2>'
											);
										else :
											the_title( '<h2 class="special-card-title">', '</h2>' );
										endif;
										?>
										<div class="special-card-excerpt">
											<?php the_excerpt(); ?>
										</div>
										<a href="<?php the_permalink(); ?>" class="mia-button" data-variant="gold-outline">
											<?php echo $is_spanish ? 'Ver Oferta' : 'View Special'; ?> 
											<i class="fas fa-arrow-right"></i>
										</a>
									</div>
								</article>
							</div>

							<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>

				</div> <!-- .row -->

			<?php else : ?>

				<div class="text-center">
					<h2><?php echo 'spanish' === $current_language ? 'No Se Encontraron Ofertas' : 'No Specials Found'; ?></h2>
					<p><?php echo 'spanish' === $current_language ? 'Por favor, vuelva más tarde para nuevas ofertas.' : 'Please check back later for new offers.'; ?></p>
				</div>

			<?php endif; ?>

		</div> <!-- .container -->
	</main>

</div><!-- .specials-archive-page -->

<?php get_footer(); ?>