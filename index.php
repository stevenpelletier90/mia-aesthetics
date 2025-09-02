<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<!-- Page Header -->
		<section class="post-header py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<h1><?php the_title(); ?></h1>
					</div>
				</div>
			</div>
		</section>

		<!-- Content -->
		<article class="py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="mb-4">
								<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
							</div>
						<?php endif; ?>
						
						<div class="content">
							<?php the_content(); ?>
						</div>
					</div>
					
					<div class="col-lg-4">
						<!-- Sidebar content can go here if needed -->
					</div>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
