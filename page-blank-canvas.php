<?php
/**
 * Template Name: Blank Canvas
 * Template Post Type: post, page, procedure, non-surgical, fat-transfer, location
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" <?php post_class(); ?>>
<?php mia_aesthetics_breadcrumbs(); ?>
	<?php
		// The_content prints whatever HTML you drop in the editor.
	while ( have_posts() ) :
		the_post();
		the_content();
		endwhile;
	?>
</main>

<?php
get_footer();
