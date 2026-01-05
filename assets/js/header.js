/**
 * Header-specific JavaScript functionality
 *
 * @package Mia_Aesthetics
 */

document.addEventListener('DOMContentLoaded', function () {
  initializeMegaMenus();
  initializeHoverDropdowns();
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
 * Initialize hover-to-open behavior for desktop dropdowns
 * Uses Bootstrap's Dropdown API for proper state management
 */
function initializeHoverDropdowns() {
  // Only apply hover behavior on desktop (matches CSS breakpoint)
  const isDesktop = function () {
    return window.innerWidth >= 1200;
  };

  const dropdownItems = document.querySelectorAll('.navbar-nav .nav-item.dropdown');
  let hoverTimeout = null;

  dropdownItems.forEach(function (item) {
    const toggle = item.querySelector('.dropdown-toggle');
    const menu = item.querySelector('.dropdown-menu');

    if (!toggle || !menu) return;

    // Get or create Bootstrap dropdown instance
    let dropdownInstance = bootstrap.Dropdown.getOrCreateInstance(toggle, {
      autoClose: true,
    });

    // Mouse enter - show dropdown
    item.addEventListener('mouseenter', function () {
      if (!isDesktop()) return;

      clearTimeout(hoverTimeout);
      // Hide any other open dropdowns first
      dropdownItems.forEach(function (otherItem) {
        if (otherItem !== item) {
          const otherToggle = otherItem.querySelector('.dropdown-toggle');
          if (otherToggle) {
            const otherInstance = bootstrap.Dropdown.getInstance(otherToggle);
            if (otherInstance) {
              otherInstance.hide();
            }
          }
        }
      });
      dropdownInstance.show();
    });

    // Mouse leave - hide dropdown with small delay
    item.addEventListener('mouseleave', function () {
      if (!isDesktop()) return;

      hoverTimeout = setTimeout(function () {
        dropdownInstance.hide();
      }, 150);
    });

    // Prevent default click behavior on desktop (allow hover only)
    toggle.addEventListener('click', function (event) {
      if (isDesktop()) {
        // Navigate to the href instead of toggling
        const href = toggle.getAttribute('href');
        if (href && href !== '#') {
          window.location.href = href;
        }
        event.preventDefault();
        event.stopPropagation();
      }
    });
  });
}
