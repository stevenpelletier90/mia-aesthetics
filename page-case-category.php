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

<main>
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
	$term_ids = 0 !== $current_post_id ? wp_get_post_terms( $current_post_id, 'case-category', array( 'fields' => 'ids' ) ) : array();

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
							<?php
							// Get case information ACF fields.
							$case_info           = get_field( 'case_information' );
							$surgeon             = $case_info['performed_by_surgeon'] ?? null;
							$location            = $case_info['performed_at_location'] ?? null;
							$procedure_performed = $case_info['procedure_performed'] ?? array();
							?>
							<div class="col-md-6 col-lg-4">
								<div class="card h-100">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php
										the_post_thumbnail(
											'medium',
											array(
												'class'   => 'card-img-top',
												'loading' => 'lazy',
												'alt'     => esc_attr( get_the_title() ),
											)
										);
										?>
									<?php endif; ?>

									<div class="card-body d-flex flex-column">
										<h2 class="card-title">
											<?php $case_permalink = get_permalink(); ?>
											<a href="<?php echo esc_url( false !== $case_permalink ? $case_permalink : '#' ); ?>">
												<?php echo esc_html( get_the_title() ); ?>
											</a>
										</h2>

										<?php if ( null !== $surgeon ) : ?>
											<div class="case-meta">
												<i class="fas fa-user-md" aria-hidden="true"></i>
												<?php echo esc_html( get_the_title( $surgeon ) ); ?>
											</div>
										<?php endif; ?>

										<?php if ( is_array( $procedure_performed ) && count( $procedure_performed ) > 0 ) : ?>
											<div class="case-meta">
												<i class="fas fa-procedures" aria-hidden="true"></i>
												<?php
												$procedure_names = array();
												foreach ( $procedure_performed as $procedure_id ) {
													$procedure_names[] = get_the_title( $procedure_id );
												}
												echo esc_html( implode( ', ', $procedure_names ) );
												?>
											</div>
										<?php endif; ?>

										<?php if ( null !== $location ) : ?>
											<div class="case-meta">
												<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
												<?php echo esc_html( get_the_title( $location ) ); ?>
											</div>
										<?php endif; ?>

										<?php $case_permalink_btn = get_permalink(); ?>
										<a href="<?php echo esc_url( false !== $case_permalink_btn ? $case_permalink_btn : '#' ); ?>" class="btn btn-primary btn-view-case mt-auto">
											View Case <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
										</a>
									</div>
								</div>
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
