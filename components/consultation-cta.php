<?php
/**
 * Free Virtual Consultation Sticky CTA Component
 *
 * @package Mia_Aesthetics
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- Free Virtual Consultation Sticky CTA - Mobile Only -->
<div id="consultationCta" class="consultation-cta-container d-flex d-sm-none justify-content-center align-items-center position-fixed bottom-0 start-0 w-100 p-3">
	<a href="<?php echo esc_url( home_url( '/free-plastic-surgery-consultation/' ) ); ?>" class="btn btn-primary consultation-cta-btn d-inline-block text-center text-decoration-none" aria-label="Schedule free virtual consultation">
		Free Virtual Consultation <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
	</a>
</div>
