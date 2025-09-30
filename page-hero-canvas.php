<?php
/**
 * Template Name: Hero Canvas
 * Template Post Type: page, post, procedure, non-surgical, fat-transfer, case, location
 * Description: Full‑width canvas template that keeps the flexibility of a blank
 * canvas but adds structured breadcrumbs and a hero header. Built for
 * Gutenberg or Classic editor content blocks wrapped in Bootstrap containers.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary" class="hero-canvas-main">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<!-- Page Header / Hero ----------------------------------------->
		<section class="post-header py-5">
			<div class="container">
				<h1><?php echo esc_html( get_the_title() ); ?></h1>
			</div>
		</section>

		<?php
		/**
		 * Optional featured hero image. Full‑width and lazy‑loaded.
		 * Comment out this block if your design doesn’t call for a hero image.
		 */
		if ( has_post_thumbnail() ) :
			?>
			<div class="container mb-5">
				<?php
				the_post_thumbnail(
					'full',
					array(
						'class'   => 'img-fluid w-100',
						'loading' => 'lazy',
						'alt'     => esc_attr( get_the_title() ),
					)
				);
				?>
			</div>
		<?php endif; ?>

		<!-- Full‑width Content Canvas ---------------------------------->
		<article <?php post_class( 'entry-content' ); ?>>
			<?php
				/**
				 * The_content() will render exactly what the editor outputs. Encourage
				 * layout control via  <div class="container"> / .container‑fluid
				 * blocks inside the editor content.
				 */
				the_content();

				// Support for Gutenberg/Classic page breaks.
				wp_link_pages(
					array(
						'before' => '<nav class="page-links" aria-label="' .
							esc_attr__( 'Page', 'mia-aesthetics' ) . '">',
						'after'  => '</nav>',
					)
				);
			?>
		</article>

	<?php endwhile; ?>
</main>

<?php get_footer(); ?>
