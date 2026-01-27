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

	<?php
	// Reset to main post after case query for content display.
	wp_reset_postdata();
	$post_id_int = 0 !== $current_post_id && false !== $current_post_id ? (int) $current_post_id : 0;

	/*
	-----------------------------------------------------------------
	 * BEFORE & AFTER GALLERY
	 * ----------------------------------------------------------------
	 */
	$gallery_images = $post_id_int > 0 ? get_field( 'gallery_images', $post_id_int ) : null;
	if ( is_array( $gallery_images ) && count( $gallery_images ) > 0 ) :
		?>
		<!-- Before & After Gallery -------------------------------------->
		<section class="ba-gallery-section py-5">
			<div class="container">
				<div class="row g-4">
					<?php
					foreach ( $gallery_images as $image_pair ) :
						if ( ! is_array( $image_pair ) ) {
							continue;
						}

						$before_id = isset( $image_pair['before_image'] ) ? $image_pair['before_image'] : null;
						$after_id  = isset( $image_pair['after_image'] ) ? $image_pair['after_image'] : null;
						$caption   = isset( $image_pair['caption'] ) && is_string( $image_pair['caption'] ) ? $image_pair['caption'] : '';

						// Skip if either image is missing.
						if ( ! is_numeric( $before_id ) || ! is_numeric( $after_id ) ) {
							continue;
						}

						$before_url  = wp_get_attachment_image_url( (int) $before_id, 'medium_large' );
						$after_url   = wp_get_attachment_image_url( (int) $after_id, 'medium_large' );
						$before_full = wp_get_attachment_image_url( (int) $before_id, 'full' );
						$after_full  = wp_get_attachment_image_url( (int) $after_id, 'full' );

						if ( false === $before_url || false === $after_url ) {
							continue;
						}
						?>
						<div class="col-md-6 col-lg-4">
							<div class="ba-card">
								<div class="ba-card-images">
									<div class="ba-image">
										<span class="ba-label">Before</span>
										<img
											src="<?php echo esc_url( $before_url ); ?>"
											alt="Before procedure"
											class="ba-img"
											role="button"
											tabindex="0"
											data-bs-toggle="modal"
											data-bs-target="#baGalleryModal"
											data-full-src="<?php echo esc_url( false !== $before_full ? $before_full : $before_url ); ?>"
										>
									</div>
									<div class="ba-image">
										<span class="ba-label">After</span>
										<img
											src="<?php echo esc_url( $after_url ); ?>"
											alt="After procedure"
											class="ba-img"
											role="button"
											tabindex="0"
											data-bs-toggle="modal"
											data-bs-target="#baGalleryModal"
											data-full-src="<?php echo esc_url( false !== $after_full ? $after_full : $after_url ); ?>"
										>
									</div>
								</div>
								<?php if ( '' !== $caption ) : ?>
									<p class="ba-caption"><?php echo esc_html( $caption ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- Gallery Modal ----------------------------------------------->
		<div class="modal fade" id="baGalleryModal" tabindex="-1" aria-label="Image preview" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content bg-transparent border-0">
					<div class="modal-body p-0 text-center">
						<button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
						<img src="" alt="Full size preview" class="ba-modal-img img-fluid" id="baModalImage">
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php
	$content = $post_id_int > 0 ? get_the_content( null, false, $post_id_int ) : '';
	if ( is_string( $content ) && '' !== trim( $content ) ) :
		?>
		<!-- Page Content ------------------------------------------------>
		<section class="case-category-content py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-10 mx-auto">
						<?php
						// Apply filters and output the content.
						echo wp_kses_post( apply_filters( 'the_content', $content ) );
						?>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	/*
	-----------------------------------------------------------------
	 * CTA SECTION
	 * ----------------------------------------------------------------
	 */
	$cta_url      = $post_id_int > 0 ? get_field( 'cta_button_url', $post_id_int ) : null;
	$cta_bg_id    = $post_id_int > 0 ? get_field( 'cta_background_image', $post_id_int ) : null;
	$cta_heading  = $post_id_int > 0 ? get_field( 'cta_heading', $post_id_int ) : null;
	$cta_btn_text = $post_id_int > 0 ? get_field( 'cta_button_text', $post_id_int ) : null;

	// Set defaults.
	$cta_heading  = is_string( $cta_heading ) && '' !== $cta_heading ? $cta_heading : 'Explore More Results';
	$cta_btn_text = is_string( $cta_btn_text ) && '' !== $cta_btn_text ? $cta_btn_text : 'View More Results';

	// Get background image URL if set.
	$cta_bg_url   = is_numeric( $cta_bg_id ) ? wp_get_attachment_image_url( (int) $cta_bg_id, 'full' ) : false;
	$cta_bg_style = false !== $cta_bg_url ? 'background-image: url(' . esc_url( $cta_bg_url ) . ');' : '';

	if ( is_string( $cta_url ) && '' !== $cta_url ) :
		?>
		<!-- View More Results CTA --------------------------------------->
		<section class="case-category-cta position-relative" <?php echo '' !== $cta_bg_style ? 'style="' . esc_attr( $cta_bg_style ) . '"' : ''; ?>>
			<div class="case-category-cta-overlay position-absolute top-0 start-0 w-100 h-100"></div>
			<div class="container position-relative">
				<div class="row justify-content-center text-center">
					<div class="col-lg-8 py-5">
						<h2 class="text-white mb-4"><?php echo esc_html( $cta_heading ); ?></h2>
						<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn-outline-primary-alt2 btn-lg">
							<?php echo esc_html( $cta_btn_text ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
						</a>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
