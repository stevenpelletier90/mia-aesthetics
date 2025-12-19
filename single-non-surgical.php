<?php
/**
 * Template for displaying single non-surgical treatment
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<!-- Page Header -->
		<section class="post-header py-5">
			<div class="container">
				<h1><?php the_title(); ?></h1>
			</div>
		</section>

		<!-- Main Content -->
		<section class="page-content py-5">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</section>

		<!-- FAQ Section (if exists) -->
		<?php
		$faq_section = get_field( 'faq_section' );
		if ( null !== $faq_section && is_array( $faq_section ) && isset( $faq_section['faqs'] ) && is_array( $faq_section['faqs'] ) && count( $faq_section['faqs'] ) > 0 ) :
			?>
		<section class="faq-section py-5 bg-light">
			<div class="container">
				<h2 class="text-center mb-4">Frequently Asked Questions</h2>
				<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>
			</div>
		</section>
		<?php endif; ?>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>
