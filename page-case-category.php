<?php
/**
 * Template Name: Case Category
 * Template Post Type: case
 * Description: Displays a grid of Case posts that share the same “case-category” terms
 *              attached to this Page. Mirrors the default page.php layout (breadcrumbs,
 *              hero header, featured image, content) and then injects the dynamic grid.
 *
 * @package Mia_Aesthetics
 */

get_header();
?>

<main id="primary">
	<!-- Breadcrumbs ---------------------------------------------------->
<?php mia_aesthetics_breadcrumbs(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<!-- Page Hero / Title ----------------------------------------->
		<section class="post-header py-5">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1><?php echo esc_html( get_the_title() ); ?></h1>
					</div>
				</div>
			</div>
		</section>

	<?php endwhile; ?>

	<?php
	/*
	-----------------------------------------------------------------
	 * CASE GRID QUERY
	 * ----------------------------------------------------------------
	 */
	$current_post_id = get_the_ID();
	$term_ids        = 0 !== $current_post_id && false !== $current_post_id ? wp_get_post_terms( $current_post_id, 'case-category', array( 'fields' => 'ids' ) ) : array();

	if ( ! is_array( $term_ids ) || count( $term_ids ) === 0 ) {
		$case_query = null; // No grid.
	} else {
		$current_page = max( 1, get_query_var( 'paged' ) );

		$case_query = new WP_Query(
			array(
				'post_type'              => 'case',
				'post_status'            => 'publish',
				'posts_per_page'         => 12,
				'post__not_in'           => array( get_the_ID() ),
				'paged'                  => $current_page,
				'orderby'                => 'title',
				'order'                  => 'ASC',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'tax_query'              => array(
					array(
						'taxonomy' => 'case-category',
						'field'    => 'term_id',
						'terms'    => $term_ids,
					),
				),
			)
		);
	}
	?>

	<?php if ( $case_query instanceof \WP_Query ) : ?>
		<section class="py-5">
			<div class="container">
				<?php if ( $case_query->have_posts() ) : ?>
					<div id="cases-grid" class="row g-4">
						<?php
						while ( $case_query->have_posts() ) :
							$case_query->the_post();
							?>
							<div class="col-md-6 col-lg-4">
								<?php include get_template_directory() . '/components/case-card.php'; ?>
							</div>
						<?php endwhile; ?>
					</div>

					<!-- Pagination --------------------------------------->
					<?php if ( $case_query->max_num_pages > 1 ) : ?>
						<div class="row mt-5">
							<div class="col">
								<?php
								the_posts_pagination(
									array(
										'prev_text' => '&laquo;',
										'next_text' => '&raquo;',
										'class'     => 'pagination justify-content-center',
									)
								);
								?>
							</div>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<p class="lead text-center mb-0">
						<?php esc_html_e( 'No cases found for the selected category.', 'mia-aesthetics' ); ?>
					</p>
					<?php
				endif;
				wp_reset_postdata();
				?>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
