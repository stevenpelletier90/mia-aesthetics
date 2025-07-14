<?php
/*
 * Template Name: Blank Canvas
 * Template Post Type: post, page, procedure, non-surgical, fat-transfer
 */

get_header(); ?>

<main id="primary" <?php post_class(); ?>>
	<?php
		// The_content prints whatever HTML you drop in the editor
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	?>
</main>

<?php get_footer();
