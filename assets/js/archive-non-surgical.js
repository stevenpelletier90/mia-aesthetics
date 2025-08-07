/**
 * Non-Surgical Archive Template JavaScript
 * Handles analytics tracking for the non-surgical procedures archive
 */

document.addEventListener("DOMContentLoaded", function () {
  // Track procedure card clicks for analytics
  const procedureLinks = document.querySelectorAll(".procedure-card a");

  procedureLinks.forEach((link) => {
    link.addEventListener("click", function () {
      const procedureCard = this.closest(".procedure-card");
      const procedureTitle = procedureCard.querySelector(".procedure-title a").textContent.trim();

      // Track with Google Analytics if available
      if ("undefined" !== typeof gtag) {
        gtag("event", "procedure_click", {
          procedure_name: procedureTitle,
          page_location: window.location.href,
          page_template: "archive_non_surgical",
        });
      }
    });
  });

  // Optional: Simple fade-in animation using Intersection Observer
  const cards = document.querySelectorAll(".procedure-card");

  if (0 < cards.length) {
    const animationObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animate-in");
            animationObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    cards.forEach((card) => {
      card.classList.add("animate-ready");
      animationObserver.observe(card);
    });
  }
});
