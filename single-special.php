<?php
/**
 * Template for displaying single special posts
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<a href="#main-content" class="visually-hidden-focusable skip-link">Skip to main content</a>

<main id="main-content" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="special-container">
			<div class="special-content-wrapper">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="mb-4">
							<?php the_post_thumbnail( 'full', array( 'class' => 'img-fluid w-100' ) ); ?>
						</div>
					<?php endif; ?>
					
					<div class="card shadow-sm consultation-card" role="complementary" 
						aria-label="Free Virtual Consultation">
						<div class="card-body p-4">                            
							<div class="gf-wrapper" aria-label="Free Virtual Consultation Form">
								<?php gravity_form( '1', false, false, false, null, true ); ?>
							</div>
						</div>
					</div>
					
					<div class="special-disclaimer">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>