<?php
/**
 * Spanish Consent Modal Component
 *
 * Modal for displaying consultation consent information in Spanish
 *
 * @package Mia_Aesthetics
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- Consent Modal (Spanish) -->
<div class="modal fade" id="consent-popup-spanish" tabindex="-1" aria-labelledby="consentModalLabelSpanish" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content consent-modal">
			<div class="modal-header">
				<h5 class="modal-title" id="consentModalLabelSpanish">Consentimiento de Comunicación</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
			</div>
			<div class="modal-body">
				<div class="consent-content">
					<p class="mb-0"><sup class="text-mia-gold">**</sup>Al marcar esta casilla y enviar este formulario, doy mi consentimiento mediante firma electrónica para que Mia Aesthetics se comunique conmigo a través de un agente en vivo, correo electrónico y/o marcador telefónico automático para obtener información, ofertas o anuncios por correo electrónico/llamada telefónica/mensaje de texto al número y correo electrónico proporcionado. Doy mi consentimiento para la grabación de todas las llamadas telefónicas hacia y con Mia Aesthetics. No estoy obligado a firmar este documento como condición para comprar bienes o servicios. Entiendo que puedo revocar este consentimiento en cualquier momento mediante notificación a Mia Aesthetics. Se pueden aplicar tarifas de datos de mensajes. La frecuencia de los mensajes puede variar. Para obtener más información, consulte nuestra <a href="/website-privacy-policy/">Política de Privacidad</a>, <a href="/website-sms-terms-and-conditions/">Términos y Condiciones de SMS</a> y <a href="/terms-of-use/">Términos de Uso</a>.</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
