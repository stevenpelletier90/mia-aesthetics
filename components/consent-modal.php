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
					<p class="mb-0"><sup class="text-mia-gold">**</sup>By checking this box and submitting this form, I consent by electronic signature to be contacted by Mia Aesthetics by live agent, email & automatic telephone dialer for information, offers or advertisements via email/ phone call/ text message at the number & email provided. I consent to call recording of all phone calls to and with Mia Aesthetics. I am not required to sign this document as a condition to purchase any goods or services. I understand that I can revoke this consent at any time by providing notice to Mia Aesthetics. Message data rates may apply. Message frequency may vary. To learn more, see our <a href="/website-privacy-policy/">Privacy Policy</a>, <a href="/website-sms-terms-and-conditions/">SMS Terms and Conditions</a>, and <a href="/terms-of-use/">Terms of Use</a>.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>