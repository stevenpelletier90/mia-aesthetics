<?php
/**
 * Single blog post template
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary">
<?php mia_aesthetics_breadcrumbs(); ?>
	
	<?php
	while ( have_posts() ) :
		the_post();
		$content      = get_the_content();
		$word_count   = str_word_count( wp_strip_all_tags( $content ) );
		$reading_time = ceil( $word_count / 200 );
		?>

		<section class="post-header py-4">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<h1 class="mb-3"><?php the_title(); ?></h1>

						<div class="post-meta mb-0">
							<div class="d-flex align-items-center">
								<i class="far fa-calendar-alt me-2" aria-hidden="true"></i>
								<span><?php echo get_the_date(); ?></span>
								<span class="mx-2">•</span>
								<i class="far fa-clock me-2" aria-hidden="true"></i>
								<span><?php echo esc_html( (string) $reading_time ); ?> min read</span>
							</div>
						</div>

						<?php if ( has_excerpt() ) : ?>
							<p class="post-excerpt mb-0"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<article class="py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="mb-4">
								<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid mb-2' ) ); ?>
								<?php
								$thumbnail_caption = get_the_post_thumbnail_caption();
								if ( '' !== $thumbnail_caption ) {
									echo '<figcaption class="text-muted small">' . esc_html( $thumbnail_caption ) . '</figcaption>';
								}
								?>
							</figure>
						<?php endif; ?>
						
						<div class="content mb-5">
							<?php the_content(); ?>
						</div>

						<?php
						$tags = get_the_tags();
						if ( is_array( $tags ) && count( $tags ) > 0 ) :
							?>
							<div class="related-topics mb-5">
								<h2 class="h5 mb-3"><?php esc_html_e( 'Related Topics', 'mia-aesthetics' ); ?></h2>
								<div class="d-flex flex-wrap gap-2">
									<?php foreach ( $tags as $post_tag ) : ?>
										<a href="<?php echo esc_url( get_tag_link( $post_tag->term_id ) ); ?>" class="badge badge-mia">#<?php echo esc_html( $post_tag->name ); ?></a>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					
					<div class="col-lg-4 sidebar">
						<div class="sidebar-card">
							<div class="card-body">
								<h2 class="sidebar-card-title mb-3"><?php esc_html_e( 'Patient Resources', 'mia-aesthetics' ); ?></h2>

								<ul class="list-unstyled mb-0">
									<li>
										<a href="/before-after/" class="text-decoration-none">
											<i class="fas fa-images" aria-hidden="true"></i>
											Before & After Gallery
										</a>
									</li>
									<li>
										<a href="/faqs/" class="text-decoration-none">
											<i class="fas fa-question-circle" aria-hidden="true"></i>
											Frequently Asked Questions
										</a>
									</li>
									<li>
										<a href="/conditions/" class="text-decoration-none">
											<i class="fas fa-heart" aria-hidden="true"></i>
											Conditions We Treat
										</a>
									</li>
								</ul>

								<?php
								$categories = get_the_category();
								if ( count( $categories ) > 0 ) {
									$category_ids = array();
									foreach ( $categories as $category ) {
										$category_ids[] = $category->term_id;
									}

									$related_posts = new WP_Query(
										array(
											'category__in' => $category_ids,
											'post__not_in' => array( get_the_ID() ),
											'posts_per_page' => 3,
											'orderby'      => 'rand',
											// Performance optimizations.
											'update_post_meta_cache' => false, // We don't read meta in the loop.
											'update_post_term_cache' => false, // We don't render terms in the loop.
											'no_found_rows' => true,  // Skip count query.
										)
									);

									if ( $related_posts->have_posts() ) :
										?>
										<h3 class="sidebar-card-title mb-3 mt-4"><?php esc_html_e( 'Related Articles', 'mia-aesthetics' ); ?></h3>
										<ul class="list-unstyled related-articles mb-0">
											<?php
											while ( $related_posts->have_posts() ) :
												$related_posts->the_post();
												?>
												<li>
													<a href="<?php the_permalink(); ?>" class="text-decoration-none">
														<?php if ( has_post_thumbnail() ) : ?>
															<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'article-thumb' ) ); ?>
														<?php endif; ?>
														<span><?php the_title(); ?></span>
													</a>
												</li>
											<?php endwhile; ?>
										</ul>
										<?php
									endif;
									wp_reset_postdata();
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</article>

		<?php
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'BlogPosting',
			'headline'      => get_the_title(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author(),
			),
			'publisher'     => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'url'   => get_site_icon_url(),
				),
			),
		);

		if ( has_post_thumbnail() ) {
			$schema['image'] = get_the_post_thumbnail_url( null, 'large' );
		}
		?>
		<script type="application/ld+json"><?php echo wp_json_encode( $schema ); ?></script>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
