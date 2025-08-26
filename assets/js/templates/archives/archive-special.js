/**
 * Archive Special JavaScript
 * Handles language toggle functionality
 */

document.addEventListener("DOMContentLoaded", function () {
  // Language toggle functionality
  const languageButtons = document.querySelectorAll(".specials-language-toggle .btn");

  if (languageButtons.length > 0) {
    languageButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const lang = this.getAttribute("data-lang");
        
        // Update button states
        languageButtons.forEach((btn) => {
          btn.classList.remove("btn-primary", "active");
          btn.classList.add("btn-outline-primary");
        });
        
        this.classList.remove("btn-outline-primary");
        this.classList.add("btn-primary", "active");

        // Custom content switching will be handled by your implementation
      });
    });
  }

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
