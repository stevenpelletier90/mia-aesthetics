/**
 * Header-specific JavaScript functionality
 *
 * @package Mia_Aesthetics
 */

document.addEventListener('DOMContentLoaded', function () {
  initializeMegaMenus();
});

/**
 * Initialize mega-menu dropdowns to prevent closing when clicking background
 */
function initializeMegaMenus() {
  const megaMenus = document.querySelectorAll('.dropdown-menu.mega-menu');

  megaMenus.forEach(function (menu) {
    menu.addEventListener('click', function (event) {
      // Only prevent closing if clicking on the menu background, not on links
      if (event.target.tagName !== 'A' && !event.target.closest('a')) {
        event.stopPropagation();
      }
    });
  });
}
