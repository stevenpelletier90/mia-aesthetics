<?php
/**
 * Spanish Consultation Form Component
 *
 * Spanish version of the Gravity Forms consultation card
 *
 * Usage:
 * get_template_part( 'components/consultation-form-spanish' );
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Default values - Spanish form ID is 21.
$form_id            = $args['form_id'] ?? $form_id ?? '21';
$card_title         = $args['card_title'] ?? $card_title ?? 'Consulta Virtual Gratuita';
$card_description   = $args['card_description'] ?? $card_description ?? 'Obtenga recomendaciones de tratamiento personalizadas de nuestro equipo de expertos.';
$show_title         = $args['show_title'] ?? $show_title ?? true;
$show_description   = $args['show_description'] ?? $show_description ?? false;
$ajax               = $args['ajax'] ?? $ajax ?? true;
$tabindex           = $args['tabindex'] ?? $tabindex ?? null;
$field_values       = $args['field_values'] ?? $field_values ?? null;
$additional_classes = $args['additional_classes'] ?? $additional_classes ?? '';
$heading_level      = $args['heading_level'] ?? $heading_level ?? 3;

// Ensure heading level is valid (2-6).
$heading_level = max( 2, min( 6, (int) $heading_level ) );
$heading_tag   = 'h' . $heading_level;
?>

<div class="card shadow-sm consultation-card <?php echo esc_attr( $additional_classes ); ?>"
	role="complementary"
	aria-label="<?php echo esc_attr( $card_title ); ?>">
	<div class="card-body p-4">
		<?php if ( $show_title ) : ?>
			<<?php echo esc_html( $heading_tag ); ?> class="card-title text-center mb-4">
				<?php echo esc_html( $card_title ); ?>
			</<?php echo esc_html( $heading_tag ); ?>>
		<?php endif; ?>

		<?php if ( $show_description ) : ?>
			<p class="card-text text-center mb-4">
				<?php echo esc_html( $card_description ); ?>
			</p>
		<?php endif; ?>

		<div class="gf-wrapper">
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
					<p class="mb-0">El formulario de contacto no está disponible temporalmente.</p>
					<small>Por favor llámenos directamente para consultas.</small>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php
// Queue the Spanish consent modal to render in footer (outside any containers).
add_action(
	'wp_footer',
	function () {
		get_template_part( 'components/consent-modal-spanish' );
	}
);
?>
