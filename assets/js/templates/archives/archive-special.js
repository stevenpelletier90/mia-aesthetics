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
        const currentUrl = new URL(window.location.href);

        // Update URL parameter
        currentUrl.searchParams.set("lang", lang);

        // Redirect to updated URL
        window.location.href = currentUrl.toString();
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
