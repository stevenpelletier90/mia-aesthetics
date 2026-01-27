/**
 * Case Category Template Scripts
 *
 * Handles the before/after gallery lightbox functionality.
 *
 * @package Mia_Aesthetics
 */

(function () {
	'use strict';

	/**
	 * Initialize gallery modal functionality.
	 */
	function initGalleryModal() {
		const modalImage = document.getElementById('baModalImage');
		const galleryImages = document.querySelectorAll('.ba-img');

		if (!modalImage || galleryImages.length === 0) {
			return;
		}

		// Handle click events on gallery images.
		galleryImages.forEach(function (img) {
			img.addEventListener('click', function () {
				const fullSrc = this.getAttribute('data-full-src');
				if (fullSrc) {
					modalImage.src = fullSrc;
					modalImage.alt = this.alt || 'Full size preview';
				}
			});

			// Handle keyboard navigation (Enter/Space to open).
			img.addEventListener('keydown', function (event) {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					this.click();
				}
			});
		});

		// Clear modal image when closed to prevent flash of old image.
		const modal = document.getElementById('baGalleryModal');
		if (modal) {
			modal.addEventListener('hidden.bs.modal', function () {
				modalImage.src = '';
				modalImage.alt = '';
			});
		}
	}

	// Initialize when DOM is ready.
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initGalleryModal);
	} else {
		initGalleryModal();
	}
})();
