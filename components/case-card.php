<?php
/**
 * Case Card Component
 *
 * Reusable component for displaying case cards across different templates
 * Expects to be used within a WordPress loop where post data is already set up
 *
 * @package Mia_Aesthetics
 */

// Get case information ACF fields from current post.
$case_info           = get_field( 'case_information' );
$surgeon             = $case_info['performed_by_surgeon'] ?? null;
$location            = $case_info['performed_at_location'] ?? null;
$procedure_performed = $case_info['procedure_performed'] ?? array();

$case_permalink = get_permalink();
if ( false === $case_permalink ) {
	$case_permalink = '#';
}
?>

<div class="case-card">
	<div class="case-card-inner">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="case-card-image">
				<a href="<?php echo esc_url( $case_permalink ); ?>" class="case-image-link">
					<?php
					the_post_thumbnail(
						'medium',
						array(
							'class'   => 'case-card-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( get_the_title() ),
						)
					);
					?>
				</a>
			</div>
		<?php endif; ?>

		<div class="case-card-body">
			<h2 class="case-card-title">
				<a href="<?php echo esc_url( $case_permalink ); ?>" class="case-title-link">
					<?php the_title(); ?>
				</a>
			</h2>

			<div class="case-card-meta">
				<?php if ( null !== $surgeon && false !== $surgeon ) : ?>
					<div class="case-meta-item">
						<i class="fas fa-user-md" aria-hidden="true"></i>
						<?php
						$surgeon_permalink = get_permalink( $surgeon );
						if ( false === $surgeon_permalink ) {
							$surgeon_permalink = '#';
						}
						?>
						<a href="<?php echo esc_url( $surgeon_permalink ); ?>" class="case-surgeon-link">
							<?php echo esc_html( get_the_title( $surgeon ) ); ?>
						</a>
					</div>
				<?php endif; ?>

				<?php if ( is_array( $procedure_performed ) && count( $procedure_performed ) > 0 ) : ?>
					<div class="case-meta-item">
						<i class="fas fa-procedures" aria-hidden="true"></i>
						<span class="case-procedure">
							<?php
							$procedure_names = array();
							foreach ( $procedure_performed as $procedure_id ) {
								$procedure_names[] = get_the_title( $procedure_id );
							}
							echo esc_html( implode( ', ', $procedure_names ) );
							?>
						</span>
					</div>
				<?php endif; ?>

				<?php if ( null !== $location && false !== $location ) : ?>
					<div class="case-meta-item">
						<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
						<?php
						$location_permalink = get_permalink( $location );
						if ( false === $location_permalink ) {
							$location_permalink = '#';
						}
						?>
						<a href="<?php echo esc_url( $location_permalink ); ?>" class="case-location-link">
							<?php echo esc_html( get_the_title( $location ) ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<div class="case-card-action">
				<a href="<?php echo esc_url( $case_permalink ); ?>" class="btn btn-primary">
					View Case <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
				</a>
			</div>
		</div>
	</div>
</div>

