<?php
/**
 * Basic Single Template
 *
 * @package Mia_Aesthetics
 */

get_header(); ?>

<main id="primary" data-bs-spy="scroll" data-bs-target="#tableOfContents" data-bs-offset="100" data-bs-smooth-scroll="true">
<?php mia_aesthetics_breadcrumbs(); ?>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<!-- Page Header -->
		<section class="post-header py-5">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<h1><?php the_title(); ?></h1>
					</div>
				</div>
			</div>
		</section>

		<!-- Content -->
		<article class="py-4">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<?php
						// Check if overview_details has actual content to display.
						$has_overview_content = false;
						$overview_items       = get_field( 'overview_details' );
						if ( null !== $overview_items && is_array( $overview_items ) ) :
							foreach ( $overview_items as $item ) :
								if ( ! in_array( trim( $item['overview_item'] ), array( '', '0' ), true ) ) :
									$has_overview_content = true;
									break;
								endif;
							endforeach;
						endif;

						// Only display overview section if there's actual content.
						if ( $has_overview_content ) :
							?>
							<div class="overview-section mb-4">
								<h2 id="overview">Overview</h2>
<div class="overview-content">
							<?php
							// Loop through the repeater field rows.
							while ( have_rows( 'overview_details' ) ) :
								the_row();
								// Get the overview_item text area value.
								$overview_item = get_sub_field( 'overview_item' );
								if ( ! in_array( trim( $overview_item ), array( '', '0' ), true ) ) :
									?>
			<div class="overview-item">
				<i class="fa-solid fa-check-circle overview-check" aria-hidden="true"></i>
										<?php echo esc_html( $overview_item ); ?>
			</div>
									<?php
		endif;
	endwhile;
							?>
</div>
							</div>
						<?php endif; ?>

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="mb-4">
								<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
							</div>
						<?php endif; ?>
						
						<div class="content">
							<?php the_content(); ?>
						</div>
						<?php
						// Using display_page_faqs(true) to show heading from custom field.
						echo wp_kses_post( mia_aesthetics_display_faqs( true ) );
						?>
					</div>
					
					<div class="col-lg-4">
						<div class="toc-container">
							<h3>Table of Contents</h3>
							<nav id="tableOfContents" class="toc-nav">
								<ul class="toc-list nav flex-column">
									<!-- Table of contents will be dynamically generated from h2 tags -->
								</ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</article>
	<?php endwhile; ?>
	
</main>

<?php get_footer(); ?>
