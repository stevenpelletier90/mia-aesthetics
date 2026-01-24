<?php
/**
 * Template for displaying single procedure
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
		<section class="py-4 py-lg-5" aria-labelledby="faq-heading-<?php echo esc_attr( (string) get_the_ID() ); ?>">
			<div class="container">
				<?php echo wp_kses_post( mia_aesthetics_display_faqs() ); ?>
			</div>
		</section>
		<?php endif; ?>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>