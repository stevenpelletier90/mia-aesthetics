/**
 * single-case-modal-sync.js
 * --------------------------------------------------------------------
 * Synchronises the Bootstrap 5 carousel inside #imageModal
 * with the thumbnail clicked on single-case templates.
 *
 * Depends on:
 * • Bootstrap 5 bundle (carousel + modal)
 * • HTML structure with #imageModal + #caseCarousel IDs
 *
 * Loaded only on single-case templates via wp_enqueue_script in PHP.
 *
 * @package Mia_Aesthetics
 */

(function () {
  'use strict';

  const imageModal = document.getElementById('imageModal');
  if (!imageModal) {
    return;
  }

  imageModal.addEventListener('show.bs.modal', (event) => {
    const trigger = event.relatedTarget;
    const slideName = trigger?.getAttribute('data-bs-title');
    const carousel = bootstrap.Carousel.getInstance(document.getElementById('caseCarousel'));

    if (carousel && slideName) {
      carousel.to('Before Treatment' === slideName ? 0 : 1);
    }
  });

  // Improve affordance: show pointer cursor on modal triggers
  document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#imageModal"]').forEach((el) => {
    el.style.cursor = 'pointer';
  });
})();
