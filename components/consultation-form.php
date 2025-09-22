<?php
/**
 * Consultation Form Component
 *
 * Reusable Gravity Forms consultation card for treatment pages
 *
 * Usage:
 * get_template_part( 'components/consultation-form' );
 *
 * Or with custom parameters:
 * get_template_part( 'components/consultation-form', null, [
 *     'form_id' => '2',
 *     'card_title' => 'Custom Title',
 *     'show_description' => false
 * ] );
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Default values - check both $args (WordPress 5.5+) and direct variables.
$form_id            = $args['form_id'] ?? $form_id ?? '1';
$card_title         = $args['card_title'] ?? $card_title ?? 'Free Virtual Consultation';
$card_description   = $args['card_description'] ?? $card_description ?? 'Get personalized treatment recommendations from our expert team.';
$show_title         = $args['show_title'] ?? $show_title ?? true;
$show_description   = $args['show_description'] ?? $show_description ?? false;
$ajax               = $args['ajax'] ?? $ajax ?? true;
$tabindex           = $args['tabindex'] ?? $tabindex ?? null;
$field_values       = $args['field_values'] ?? $field_values ?? null;
$additional_classes = $args['additional_classes'] ?? $additional_classes ?? '';
?>

<div class="card shadow-sm consultation-card <?php echo esc_attr( $additional_classes ); ?>" 
	role="complementary" 
	aria-label="<?php echo esc_attr( $card_title ); ?>">
	<div class="card-body p-4">
		<?php if ( $show_title ) : ?>
			<h3 class="card-title text-center mb-4">
				<?php echo esc_html( $card_title ); ?>
			</h3>
		<?php endif; ?>

		<?php if ( $show_description ) : ?>
			<p class="card-text text-center mb-4">
				<?php echo esc_html( $card_description ); ?>
			</p>
		<?php endif; ?>

		<div class="gf-wrapper" aria-label="<?php echo esc_attr( $card_title . ' Form' ); ?>">
			<?php
			// Check if Gravity Forms is active.
			if ( function_exists( 'gravity_form' ) ) {
				gravity_form(
					$form_id,
					false, // title.
					false, // description.
					false, // display_inactive.
					$field_values, // field_values.
					$ajax, // ajax.
					$tabindex, // tabindex.
					true, // echo.
					'gravity', // form_theme - use Gravity's modern theme.
					null // style_settings.
				);
			} else {
				?>
				<div class="alert alert-warning text-center" role="alert">
					<p class="mb-0">Contact form is temporarily unavailable.</p>
					<small class="text-muted">Please call us directly for consultations.</small>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>
