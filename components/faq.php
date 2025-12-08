<?php
/**
 * FAQ Component
 *
 * Displays FAQ accordion using Bootstrap components.
 * This component is called via get_template_part() and expects
 * FAQ data to be passed through $args.
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get FAQ data from args.
$show_heading = $args['show_heading'] ?? true;
$faq_section  = $args['faq_section'] ?? array();
$valid_faqs   = $args['valid_faqs'] ?? array();
$accordion_id = $args['accordion_id'] ?? 'faq-accordion';

// Exit early if no valid FAQs to display.
if ( ! is_array( $valid_faqs ) || 0 === count( $valid_faqs ) ) {
	return;
}
?>

<section class="faqs-section my-5"
<?php
if ( $show_heading ) {
	echo 'aria-labelledby="faq-heading-' . esc_attr( (string) get_the_ID() ) . '"';
}
?>
>
	<?php if ( $show_heading ) : ?>
		<?php
			$section_title = ! isset( $faq_section['title'] ) || '' === $faq_section['title']
				? 'Frequently Asked Questions'
				: $faq_section['title'];
		?>
		<h2 id="faq-heading-<?php echo esc_attr( (string) get_the_ID() ); ?>" class="mb-4">
			<?php echo esc_html( $section_title ); ?>
		</h2>

		<?php if ( isset( $faq_section['description'] ) && '' !== $faq_section['description'] ) : ?>
			<div class="faq-description mb-4">
				<?php echo wp_kses_post( $faq_section['description'] ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="accordion" id="<?php echo esc_attr( $accordion_id ); ?>">
		<?php foreach ( $valid_faqs as $index => $faq ) : ?>
			<?php
			$item_id     = 'faq-' . get_the_ID() . '-' . $index;
			$heading_id  = 'heading-' . $item_id;
			$collapse_id = 'collapse-' . $item_id;
			?>
			<div class="accordion-item">
				<h3 class="accordion-header" id="<?php echo esc_attr( $heading_id ); ?>">
					<button class="accordion-button collapsed"
							type="button"
							data-bs-toggle="collapse"
							data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
						<?php echo esc_html( $faq['question'] ); ?>
					</button>
				</h3>
				<div id="<?php echo esc_attr( $collapse_id ); ?>"
					class="accordion-collapse collapse">
					<div class="accordion-body">
						<?php echo wp_kses_post( $faq['answer'] ); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>