/**
 * Front Page JavaScript
 *
 * @package Mia_Aesthetics
 */

document.addEventListener('DOMContentLoaded', function () {
  initStatsAnimation();
});

/**
 * Stats count-up animation
 */
function initStatsAnimation() {
  const statNumbers = document.querySelectorAll('.stat__number[data-count]');

  if (0 === statNumbers.length) {
    return;
  }

  function animateCountUp(element, target, duration, suffix = '') {
    const steps = 50;
    const increment = target / steps;
    let current = 0;
    let step = 0;

    // Check if this is a year (no formatting needed)
    const label = element.nextElementSibling;
    const isYear = label && label.textContent.toLowerCase().includes('year');

    const timer = setInterval(() => {
      step++;

      if (step >= steps) {
        clearInterval(timer);
        element.textContent = isYear ? target + suffix : target.toLocaleString('en-US') + suffix;
      } else {
        current = Math.floor(increment * step);
        element.textContent = isYear ? current + suffix : current.toLocaleString('en-US') + suffix;
      }
    }, duration / steps);
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const element = entry.target;
          const target = parseInt(element.getAttribute('data-count'), 10);
          const suffix = element.getAttribute('data-suffix') || '';

          animateCountUp(element, target, 2000, suffix);
          observer.unobserve(element);
        }
      });
    },
    { threshold: 0.5 },
  );

  statNumbers.forEach((stat) => observer.observe(stat));
}
