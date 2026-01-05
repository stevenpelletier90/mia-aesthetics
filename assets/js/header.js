/**
 * Header-specific JavaScript functionality
 *
 * @package Mia_Aesthetics
 */

document.addEventListener('DOMContentLoaded', function () {
  initializeMegaMenus();
  initializeDesktopDropdowns();
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

/**
 * Initialize desktop dropdown behavior
 * Click on parent nav item navigates to its page
 */
function initializeDesktopDropdowns() {
  const isDesktop = function () {
    return window.innerWidth >= 1200;
  };

  const dropdownToggles = document.querySelectorAll('.navbar-nav .dropdown-toggle');

  dropdownToggles.forEach(function (toggle) {
    toggle.addEventListener('click', function (event) {
      if (isDesktop()) {
        // Navigate to the href instead of toggling dropdown
        const href = toggle.getAttribute('href');
        if (href && href !== '#') {
          window.location.href = href;
          event.preventDefault();
          event.stopPropagation();
        }
      }
    });
  });
}
