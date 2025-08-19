/**
 * Careers Page JavaScript
 *
 * Handles animations and interactions for the careers landing page
 *
 * @package Mia_Aesthetics
 */

(function () {
  "use strict";

  // Stats Counter Animation
  document.addEventListener("DOMContentLoaded", function () {
    const statsCounters = document.querySelectorAll("[data-count]");

    // Counter animation function
    function animateCounter(element, targetValue, suffix = "") {
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
        if (2000 <= targetValue && 3000 > targetValue) {
          // Years (like 2017)
          displayValue = currentValue.toString();
        } else if (100 <= targetValue) {
          // Large numbers like 150 (for 150,000+)
          displayValue = currentValue.toString();
        } else {
          // Regular numbers
          displayValue = currentValue.toString();
        }

        element.textContent = displayValue + suffix;

        if (1 > progress) {
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
            const targetValue = parseInt(element.getAttribute("data-count"));
            const suffix = element.getAttribute("data-suffix") || "";

            // Start the animation
            animateCounter(element, targetValue, suffix);

            // Stop observing this element
            observer.unobserve(element);
          }
        });
      },
      {
        threshold: 0.5,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    // Observe all counter elements
    statsCounters.forEach((counter) => {
      observer.observe(counter);
    });
  });

  // Core Values Animation
  document.addEventListener("DOMContentLoaded", function () {
    const valueCards = document.querySelectorAll(".value-card");

    const valuesObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, index) => {
          if (entry.isIntersecting) {
            // Add staggered animation delay
            setTimeout(() => {
              entry.target.classList.add("animate-in");
            }, index * 150); // 150ms delay between each card

            valuesObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
      }
    );

    valueCards.forEach((card) => {
      valuesObserver.observe(card);
    });
  });

  // Testimonials Carousel Initialization (Bootstrap 5.3 compliant)
  document.addEventListener("DOMContentLoaded", function () {
    const testimonialCarousel = document.getElementById("testimonialsCarousel");

    if (testimonialCarousel && "undefined" !== typeof bootstrap) {
      // Function to restructure carousel based on screen size
      function restructureCarousel() {
        const screenWidth = window.innerWidth;
        const isMobile = 767.98 >= screenWidth;
        const isTablet = 767.98 < screenWidth && 991.98 >= screenWidth;
        const carouselInner = testimonialCarousel.querySelector(".carousel-inner");
        const indicators = testimonialCarousel.querySelector(".carousel-indicators");

        // Extract all testimonial cards from original structure
        const allCards = Array.from(testimonialCarousel.querySelectorAll(".testimonial-card"));

        // Clear existing content
        carouselInner.innerHTML = "";
        indicators.innerHTML = "";

        if (isMobile) {
          // Mobile: 1 review per slide = 10 slides
          allCards.forEach((card, index) => {
            const slide = document.createElement("div");
            slide.className = `carousel-item${0 === index ? " active" : ""}`;
            slide.innerHTML = `
              <div class="row g-4 justify-content-center">
                <div class="col-12">
                  ${card.outerHTML}
                </div>
              </div>
            `;
            carouselInner.appendChild(slide);

            // Create indicator
            const indicator = document.createElement("button");
            indicator.type = "button";
            indicator.setAttribute("data-bs-target", "#testimonialsCarousel");
            indicator.setAttribute("data-bs-slide-to", index.toString());
            indicator.setAttribute("aria-label", `Slide ${index + 1}`);
            if (0 === index) {
              indicator.className = "active";
              indicator.setAttribute("aria-current", "true");
            }
            indicators.appendChild(indicator);
          });
        } else if (isTablet) {
          // Tablet: 2 reviews per slide = 5 slides (2+2+2+2+2)
          for (let i = 0; i < allCards.length; i += 2) {
            const slideIndex = Math.floor(i / 2);
            const slide = document.createElement("div");
            slide.className = `carousel-item${0 === slideIndex ? " active" : ""}`;

            const firstCard = allCards[i];
            const secondCard = allCards[i + 1];

            slide.innerHTML = `
              <div class="row g-4 justify-content-center">
                <div class="col-md-6">
                  ${firstCard.outerHTML}
                </div>
                ${secondCard ? `<div class="col-md-6">${secondCard.outerHTML}</div>` : ""}
              </div>
            `;
            carouselInner.appendChild(slide);

            // Create indicator
            const indicator = document.createElement("button");
            indicator.type = "button";
            indicator.setAttribute("data-bs-target", "#testimonialsCarousel");
            indicator.setAttribute("data-bs-slide-to", slideIndex.toString());
            indicator.setAttribute("aria-label", `Slide ${slideIndex + 1}`);
            if (0 === slideIndex) {
              indicator.className = "active";
              indicator.setAttribute("aria-current", "true");
            }
            indicators.appendChild(indicator);
          }
        } else {
          // Desktop: 3 reviews per slide = 4 slides (3+3+3+1) - restore original structure
          const originalSlides = [
            [0, 1, 2], // Reviews 1-3
            [3, 4, 5], // Reviews 4-6
            [6, 7, 8], // Reviews 7-9
            [9], // Review 10
          ];

          originalSlides.forEach((cardIndices, slideIndex) => {
            const slide = document.createElement("div");
            slide.className = `carousel-item${0 === slideIndex ? " active" : ""}`;

            let slideContent = '<div class="row g-4 justify-content-center">';
            cardIndices.forEach((cardIndex) => {
              if (allCards[cardIndex]) {
                const colClass =
                  1 === cardIndices.length ? "col-lg-4 col-md-6 col-12" : "col-lg-4 col-md-6";
                slideContent += `<div class="${colClass}">${allCards[cardIndex].outerHTML}</div>`;
              }
            });
            slideContent += "</div>";

            slide.innerHTML = slideContent;
            carouselInner.appendChild(slide);

            // Create indicator
            const indicator = document.createElement("button");
            indicator.type = "button";
            indicator.setAttribute("data-bs-target", "#testimonialsCarousel");
            indicator.setAttribute("data-bs-slide-to", slideIndex.toString());
            indicator.setAttribute("aria-label", `Slide ${slideIndex + 1}`);
            if (0 === slideIndex) {
              indicator.className = "active";
              indicator.setAttribute("aria-current", "true");
            }
            indicators.appendChild(indicator);
          });
        }
      }

      // Initial setup
      restructureCarousel();

      // Rebuild on resize (but avoid refreshes on mobile scroll)
      let resizeTimeout;
      let lastWidth = window.innerWidth;

      window.addEventListener("resize", function () {
        const currentWidth = window.innerWidth;

        // Only restructure if width actually changed significantly (not just mobile scroll)
        if (50 > Math.abs(currentWidth - lastWidth)) {
          return;
        }

        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function () {
          // Dispose existing carousel
          const existingCarousel = bootstrap.Carousel.getInstance(testimonialCarousel);
          if (existingCarousel) {
            existingCarousel.dispose();
          }

          // Restructure without reloading
          restructureCarousel();

          // Reinitialize carousel
          new bootstrap.Carousel(testimonialCarousel, {
            interval: 6000,
            keyboard: true,
            pause: "hover",
            ride: false,
            wrap: true,
            touch: true,
          });

          // Update last width
          lastWidth = currentWidth;
        }, 300);
      });

      // Initialize Bootstrap carousel with faster transitions
      const carousel = new bootstrap.Carousel(testimonialCarousel, {
        interval: 6000,
        keyboard: true,
        pause: "hover",
        ride: false, // Don't auto-start to improve responsiveness
        wrap: true,
        touch: true,
      });

      // Improve click responsiveness by stopping any ongoing transitions
      const prevBtn = testimonialCarousel.querySelector(".carousel-control-prev");
      const nextBtn = testimonialCarousel.querySelector(".carousel-control-next");

      if (prevBtn) {
        prevBtn.addEventListener("click", function (e) {
          e.preventDefault();
          carousel.prev();
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener("click", function (e) {
          e.preventDefault();
          carousel.next();
        });
      }

      // Start carousel after initialization
      setTimeout(() => {
        carousel.cycle();
      }, 1000);

      // Simple fade in effect for testimonials when they come into view
      const testimonialsSection = document.querySelector(".testimonials-section");
      if (testimonialsSection) {
        const testimonialsObserver = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                // Simple fade in for all visible cards
                const allCards = entry.target.querySelectorAll(".testimonial-card");
                allCards.forEach((card) => {
                  card.style.opacity = "1";
                });

                testimonialsObserver.unobserve(entry.target);
              }
            });
          },
          {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
          }
        );

        testimonialsObserver.observe(testimonialsSection);
      }
    }
  });
})();
