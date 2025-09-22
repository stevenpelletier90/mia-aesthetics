<?php
/**
 * Category Archive Template
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	
	<section class="post-header py-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-8">
					<h1><?php single_cat_title(); ?></h1>
					<?php
					the_archive_description( '<div class="archive-description mt-3">', '</div>' );
					?>
				</div>
			</div>
		</div>
	</section>

	<section class="py-5">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="row g-4">
					<?php
					while ( have_posts() ) :
						the_post();
						?>
						<div class="col-md-6 col-lg-4">
							<div class="card h-100">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) ); ?>
								<?php endif; ?>
								
								<div class="card-body">
									<h2 class="h5">
										<a href="<?php the_permalink(); ?>">
											<?php the_title(); ?>
										</a>
									</h2>
									<?php the_excerpt(); ?>
								</div>
								<div class="card-footer bg-white border-top-0">
									<a href="<?php the_permalink(); ?>" class="read-more-link">
										Read Full Article: <?php the_title(); ?> <i class="fas fa-arrow-right" aria-hidden="true"></i>
									</a>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
				</div>
				
				<div class="row mt-5">
					<div class="col">
						<?php the_posts_navigation(); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="row">
					<div class="col text-center py-5">
						<div class="alert alert-info">
							<i class="fas fa-info-circle me-2" aria-hidden="true"></i> No posts found in this category.
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
?>
