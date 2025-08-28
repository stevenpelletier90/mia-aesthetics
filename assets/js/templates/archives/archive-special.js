/**
 * Archive Special JavaScript
 * Handles language toggle functionality
 */

document.addEventListener("DOMContentLoaded", function () {
  // Category toggle functionality
  const languageButtons = document.querySelectorAll(".specials-language-toggle .btn");
  const englishSpecials = document.getElementById("english-specials");
  const spanishSpecials = document.getElementById("spanish-specials");

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

        // Toggle content based on selection
        if (lang === "english") {
          // Show category 1 (English/Default)
          if (englishSpecials) {
            englishSpecials.style.display = "block";
          }
          if (spanishSpecials) {
            spanishSpecials.style.display = "none";
          }
        } else if (lang === "spanish") {
          // Show category 2 (Spanish/Secondary)
          if (englishSpecials) {
            englishSpecials.style.display = "none";
          }
          if (spanishSpecials) {
            spanishSpecials.style.display = "block";
          }
        }
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
