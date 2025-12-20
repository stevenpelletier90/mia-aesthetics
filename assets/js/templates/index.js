// assets/js/index.js
// -----------------------------------------------------------------------------
// Index (fallback) JavaScript for Mia Aesthetics theme.
// Loads on generic index.php pages when no other more-specific template applies.
// Keep footprint minimal – vanilla JS, defer-loaded.
// -----------------------------------------------------------------------------

(() => {
  'use strict';

  const ready = (cb) => ('loading' === document.readyState ? document.addEventListener('DOMContentLoaded', cb, { once: true }) : cb());

  ready(() => {
    // Simple focus style for keyboard users
    document.addEventListener(
      'keyup',
      (e) => {
        if ('Tab' === e.key) {
          document.body.classList.add('show-focus-outline');
        }
      },
      { once: true },
    );

    // Example: smooth scroll for any in-page anchors
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
      anchor.addEventListener('click', (e) => {
        const target = document.querySelector(anchor.getAttribute('href'));
        if (!target) {
          return;
        }
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth' });
      });
    });
  });
})();
