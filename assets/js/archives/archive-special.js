/**
 * Archive Special JavaScript
 * Bootstrap 5 handles tab functionality natively
 */

document.addEventListener("DOMContentLoaded", function () {
  // Tab functionality is now handled by Bootstrap 5 natively
  // No custom JavaScript needed for language toggle

  // Smooth scroll for sticky specials
  const stickySpecials = document.querySelectorAll(".sticky-special");
  if (stickySpecials.length > 0) {
    // Add animation on scroll
    const observerOptions = {
      threshold: 0.1,
      rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver(function (entries) {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("animate-in");
        }
      });
    }, observerOptions);

    stickySpecials.forEach((special) => {
      observer.observe(special);
    });
  }
});
