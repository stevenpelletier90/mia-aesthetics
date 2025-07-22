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

  // Testimonials Carousel Initialization
  document.addEventListener('DOMContentLoaded', function () {
    const testimonialCarousel = document.getElementById('testimonialsCarousel');
    
    if (testimonialCarousel) {
      // Initialize Bootstrap carousel with custom options
      const carousel = new bootstrap.Carousel(testimonialCarousel, {
        interval: 5000, // 5 seconds between slides
        ride: 'carousel',
        pause: 'hover',
        wrap: true,
        keyboard: true
      });

      // Animate testimonial cards on slide change
      testimonialCarousel.addEventListener('slide.bs.carousel', function (event) {
        // Remove animation from all cards
        const allCards = testimonialCarousel.querySelectorAll('.testimonial-card');
        allCards.forEach(card => {
          card.classList.remove('animate-in');
        });
      });

      testimonialCarousel.addEventListener('slid.bs.carousel', function (event) {
        // Add animation to visible cards with stagger
        const activeSlide = event.relatedTarget;
        const cards = activeSlide.querySelectorAll('.testimonial-card');
        
        cards.forEach((card, index) => {
          setTimeout(() => {
            card.classList.add('animate-in');
          }, index * 150); // 150ms delay between each card
        });
      });

      // Animate initial testimonial cards
      const initialCards = testimonialCarousel.querySelectorAll('.carousel-item.active .testimonial-card');
      initialCards.forEach((card, index) => {
        setTimeout(() => {
          card.classList.add('animate-in');
        }, index * 150);
      });
    }

    // Testimonials Section Animation Observer
    const testimonialsSection = document.querySelector('.testimonials-section');
    if (testimonialsSection) {
      const testimonialsObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              // Trigger initial animation when section comes into view
              const activeCards = entry.target.querySelectorAll('.carousel-item.active .testimonial-card');
              activeCards.forEach((card, index) => {
                setTimeout(() => {
                  card.classList.add('animate-in');
                }, index * 150);
              });
              
              testimonialsObserver.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.2,
          rootMargin: '0px 0px -100px 0px',
        }
      );

      testimonialsObserver.observe(testimonialsSection);
    }
  });

})();