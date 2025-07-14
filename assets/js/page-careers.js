/**
 * Careers Page JavaScript
 * 
 * Handles animations and interactions for the careers landing page
 * 
 * @package Mia_Aesthetics
 */

(function() {
  'use strict';

  // Stats Counter Animation
  document.addEventListener('DOMContentLoaded', function () {
    const statsCounters = document.querySelectorAll('[data-count]');

    // Counter animation function
    function animateCounter(element, targetValue, suffix = '') {
      const startValue = 0;
      const duration = 2000; // 2 seconds
      const startTime = performance.now();

      function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function for smooth animation
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        const currentValue = Math.floor(startValue + (targetValue - startValue) * easeOutCubic);

        // Format the number based on its value
        let displayValue;
        if (targetValue >= 2000 && targetValue < 3000) {
          // Years (like 2018)
          displayValue = currentValue.toString();
        } else if (targetValue >= 100) {
          // Large numbers like 150 (for 150,000+)
          displayValue = currentValue.toString();
        } else {
          // Regular numbers
          displayValue = currentValue.toString();
        }

        element.textContent = displayValue + suffix;

        if (progress < 1) {
          requestAnimationFrame(updateCounter);
        }
      }

      requestAnimationFrame(updateCounter);
    }

    // Intersection Observer to trigger animations when in view
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const element = entry.target;
            const targetValue = parseInt(element.getAttribute('data-count'));
            const suffix = element.getAttribute('data-suffix') || '';

            // Start the animation
            animateCounter(element, targetValue, suffix);

            // Stop observing this element
            observer.unobserve(element);
          }
        });
      },
      {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px',
      }
    );

    // Observe all counter elements
    statsCounters.forEach((counter) => {
      observer.observe(counter);
    });
  });

  // Core Values Animation
  document.addEventListener('DOMContentLoaded', function () {
    const valueCards = document.querySelectorAll('.value-card');

    const valuesObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            // Add staggered animation delay
            setTimeout(() => {
              entry.target.classList.add('animate-in');
            }, index * 150); // 150ms delay between each card

            valuesObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px',
      }
    );

    valueCards.forEach((card) => {
      valuesObserver.observe(card);
    });
  });

})();