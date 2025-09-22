<?php
/**
 * Template for displaying single special posts
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" role="main">
<?php mia_aesthetics_breadcrumbs(); ?>
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
					
					<?php get_template_part( 'components/consultation-form' ); ?>
					
					<div class="special-disclaimer">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
