<?php
/**
 * Consent Modal Component
 *
 * Modal for displaying consultation consent information
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- Consent Modal -->
<div class="modal fade" id="consent-popup" tabindex="-1" aria-labelledby="consentModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content consent-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="consentModalLabel">Consultation Consent</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="consent-content">
					<p class="lead mb-4">By requesting a consultation, you acknowledge and agree to the following:</p>

					<ul class="consent-list">
						<li>This consultation is for informational purposes only and does not constitute medical advice.</li>
						<li>A formal medical evaluation is required before any treatment recommendations can be made.</li>
						<li>Your personal information will be kept confidential and used only for consultation purposes.</li>
						<li>You understand that results may vary and no specific outcomes are guaranteed.</li>
						<li>You are 18 years of age or older, or have parental consent if under 18.</li>
					</ul>

					<div class="consent-notice mt-4 p-3">
						<p class="mb-0"><strong>Important:</strong> This virtual consultation does not replace an in-person medical examination. All treatment decisions should be made in consultation with a qualified medical professional.</p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-times me-2" aria-hidden="true"></i>Close
				</button>
				<button type="button" class="btn btn-primary" data-bs-dismiss="modal">
					<i class="fas fa-check me-2" aria-hidden="true"></i>I Understand
				</button>
			</div>
		</div>
	</div>
</div>