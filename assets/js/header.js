/**
 * Header-specific JavaScript functionality
 * Handles mobile CTA visibility when offcanvas menu is toggled
 */

document.addEventListener('DOMContentLoaded', function() {
    const cta = document.getElementById('mobileCta');
    const offcanvas = document.getElementById('navbarOffcanvas');
    
    if (cta && offcanvas) {
        // Hide mobile CTA when offcanvas menu opens
        offcanvas.addEventListener('show.bs.offcanvas', function() {
            cta.classList.add('d-none');
        });
        
        // Show mobile CTA when offcanvas menu closes
        offcanvas.addEventListener('hidden.bs.offcanvas', function() {
            cta.classList.remove('d-none');
        });
    }
});