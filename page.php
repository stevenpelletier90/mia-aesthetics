<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Mia_Aesthetics
 */

get_header();

// Check for custom PHP content file.
$mia_page_content_map = mia_get_page_content_map();
$mia_page_id          = get_the_ID();
$mia_content_file     = null;

if ( false !== $mia_page_id && isset( $mia_page_content_map[ $mia_page_id ] ) ) {
	$mia_content_file = get_template_directory() . '/content/pages/' . $mia_page_content_map[ $mia_page_id ];
}
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	<?php
	while ( have_posts() ) :
		the_post();

		// If custom content file exists, use it.
		if ( null !== $mia_content_file && file_exists( $mia_content_file ) ) :
			include $mia_content_file;
		else :
			// Default page structure.
			?>
			<!-- Page Header -->
			<section class="post-header py-5">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<h1 class="mb-2"><?php the_title(); ?></h1>
						</div>
					</div>
				</div>
			</section>

			<!-- Content -->
			<article class="py-5">
				<div class="container">
					<div class="row">
						<div class="col">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="mb-4">
									<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
								</div>
							<?php endif; ?>

							<div class="content">
								<?php the_content(); ?>
							</div>

							<?php
							// Display FAQs if available.
							// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							echo wp_kses_post( mia_aesthetics_display_faqs() );
							?>
						</div>
					</div>
				</div>
			</article>
			<?php
		endif;
	endwhile;
	?>
</main>

<?php get_footer(); ?>
