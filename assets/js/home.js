// assets/js/home.js
// -----------------------------------------------------------------------------
// Home template JavaScript for Mia Aesthetics theme.
// This file is loaded only on the blog home (index of posts) via inc/enqueue.php.
// Keep it lightweight – defer-loaded, no jQuery dependency.
// -----------------------------------------------------------------------------

(() => {
  "use strict";

  // Helper: Safe DOM ready (in case script loads before end of body)
  const onReady = (fn) => {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", fn, { once: true });
    } else {
      fn();
    }
  };

  // Main
  onReady(() => {
    // Example interaction: collapse hero on scroll (replace/remove as needed)
    const hero = document.querySelector(".home-hero");
    if (!hero) return;

    const toggleHero = () => {
      const collapsed = window.scrollY > 50;
      hero.classList.toggle("is-collapsed", collapsed);
    };

    toggleHero();
    window.addEventListener("scroll", toggleHero, { passive: true });
  });
})();
