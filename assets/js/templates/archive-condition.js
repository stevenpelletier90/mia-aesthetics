/* Archive Condition Template JavaScript */
// Template: archive-condition.php
// Simple functionality for conditions archive

document.addEventListener('DOMContentLoaded', function () {
  // Add smooth scrolling to condition links if needed
  const conditionLinks = document.querySelectorAll('.condition-link');

  conditionLinks.forEach((link) => {
    link.addEventListener('mouseenter', function () {
      // Optional: Add any hover effects or preloading
    });
  });

  // Optional: Add keyboard navigation support
  document.addEventListener('keydown', function (e) {
    if ('Tab' === e.key) {
      // Ensure focus is visible on condition items
      const focusedElement = document.activeElement;
      if (focusedElement.classList.contains('condition-link')) {
        focusedElement.classList.add('focused');
      }
    }
  });
});
