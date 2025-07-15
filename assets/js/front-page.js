/**
 * Front Page JavaScript
 * Handles video interactions, stats animations, tab navigation, and procedure dropdown
 */

document.addEventListener("DOMContentLoaded", function () {
  // ===== VIDEO GLIDE.JS FUNCTIONALITY =====
  function initVideoGlide() {
    const glideContainer = document.getElementById("videoGlide");
    
    if (!glideContainer) return;

    // Initialize Glide.js
    const glide = new Glide('#videoGlide', {
      // Basic settings
      type: 'carousel',
      startAt: 0,
      perView: 1,
      focusAt: 'center',
      gap: 0,
      
      // Animation settings
      animationDuration: 400,
      animationTimingFunc: 'ease',
      
      // Touch and drag settings
      touchRatio: 1,
      touchAngle: 45,
      dragThreshold: 120,
      
      // Keyboard navigation
      keyboard: true,
      
      // Autoplay disabled for video content
      autoplay: false,
      
      // Responsive settings
      breakpoints: {
        // Mobile and tablet - same settings for consistency
        768: {
          perView: 1,
          gap: 0,
        },
        480: {
          perView: 1,
          gap: 0,
        }
      },
      
      // Direction (ltr/rtl)
      direction: 'ltr',
      
      // Peek settings (show partial next/prev slides)
      peek: 0,
      
      // Bound mode (don't go beyond first/last slide)
      bound: true,
      
      // Rewind to first slide when reaching the end
      rewind: false,
    });

    // Event listeners for better control
    glide.on('mount.after', function() {
      // Add custom class for styling
      glideContainer.classList.add('glide-initialized');
      
      // Ensure arrows are properly positioned
      const arrows = glideContainer.querySelectorAll('.glide__arrow');
      arrows.forEach(arrow => {
        arrow.style.display = 'flex';
      });
    });

    glide.on('run.before', function(move) {
      // Optional: Add custom logic before slide changes
      // console.log('Moving to:', move.direction, move.steps);
    });

    glide.on('run.after', function(move) {
      // Update navigation states
      updateNavigationStates();
    });

    // Function to update navigation button states
    function updateNavigationStates() {
      const prevArrow = glideContainer.querySelector('.glide__arrow--left');
      const nextArrow = glideContainer.querySelector('.glide__arrow--right');
      const bullets = glideContainer.querySelectorAll('.glide__bullet');
      
      if (prevArrow && nextArrow) {
        // Update arrow states based on current index
        prevArrow.disabled = glide.index === 0;
        nextArrow.disabled = glide.index === glide.settings.bound ? bullets.length - 1 : false;
      }
    }

    // Mount the glide instance
    glide.mount();

    // Store glide instance for potential later use
    window.videoGlide = glide;
    
    // Initial navigation state update
    updateNavigationStates();
  }

  // ===== STATS COUNT-UP ANIMATION =====
  function initStatsAnimation() {
    // Get all stat number elements - updated selector for Bootstrap structure
    const statNumbers = document.querySelectorAll(".display-5[data-count]");

    if (statNumbers.length === 0) return;

    // Function to animate counting up
    function animateCountUp(element, target, duration, steps, suffix = "") {
      // Start from 0
      let start = 0;
      let currentStep = 0;

      // Get the element's parent to identify the patients counter and year
      const parentText = element.closest(".position-relative").querySelector("p").textContent;
      const isPatients = parentText.includes("Patient");
      const isYear = parentText.includes("Year");

      // Calculate the value increment per step
      const valueIncrement = target / steps;

      // Set initial text to prevent wrapping on two lines
      if (isPatients) {
        element.textContent = "0";
        element.style.minWidth = "8ch"; // Reserve more space for "150,000+"
      }

      const timer = setInterval(() => {
        currentStep++;

        // If we've reached the final step, set the final value and clear the interval
        if (currentStep >= steps) {
          clearInterval(timer);

          // Format as "150,000+" for patients counter
          if (isPatients) {
            element.textContent = "150,000+";
            // Keep the min-width to prevent layout shifts
          } else if (isYear) {
            // Don't add commas to years
            element.textContent = target + suffix;
          } else {
            element.textContent = target.toLocaleString("en-US") + suffix;
          }
        } else {
          // Otherwise, update with the current count based on step
          start = valueIncrement * currentStep;
          const currentValue = Math.floor(start);

          // Format differently based on the type of counter
          if (isPatients) {
            if (currentValue >= target) {
              element.textContent = "150,000+";
            } else {
              element.textContent = currentValue.toLocaleString("en-US") + suffix;
            }
          } else if (isYear) {
            // Don't add commas to years
            element.textContent = currentValue + suffix;
          } else {
            element.textContent = currentValue.toLocaleString("en-US") + suffix;
          }
        }
      }, duration / steps);
    }

    // Create an Intersection Observer
    const observer = new IntersectionObserver(
      (entries) => {
        // If any stat number is in the viewport, animate all of them
        if (entries.some((entry) => entry.isIntersecting)) {
          // Fixed duration for all animations
          const duration = 2000; // 2 seconds for all numbers
          const steps = 50; // Number of steps in the animation

          // Start animation for all stat numbers
          statNumbers.forEach((statNumber) => {
            const target = parseInt(statNumber.getAttribute("data-count"), 10);
            const suffix = statNumber.getAttribute("data-suffix") || "";

            // Start the animation
            animateCountUp(statNumber, target, duration, steps, suffix);

            // Unobserve all elements so the animation only happens once
            observer.unobserve(statNumber);
          });
        }
      },
      { threshold: 0.5 }
    ); // Trigger when at least 50% of the element is visible

    // Observe each stat number element
    statNumbers.forEach((statNumber) => {
      observer.observe(statNumber);
    });
  }

  // ===== PROCEDURE DROPDOWN FOR MOBILE =====
  function initProcedureDropdown() {
    const procedureDropdown = document.getElementById("procedureDropdown");

    // Function to show the selected tab content
    function showSelectedTabContent(selectedTabId) {
      // Hide all tab panes
      const tabPanes = document.querySelectorAll(".tab-pane");
      tabPanes.forEach((pane) => {
        pane.classList.remove("show", "active");
      });

      // Show the selected tab pane
      const selectedPane = document.getElementById(selectedTabId);
      if (selectedPane) {
        selectedPane.classList.add("show", "active");
      }

      // Update the desktop tabs to match (for when switching back to desktop view)
      const tabLinks = document.querySelectorAll(".procedure-tabs .nav-link");
      tabLinks.forEach((link) => {
        link.classList.remove("active");
        link.setAttribute("aria-selected", "false");

        // If this tab corresponds to the selected dropdown option
        if (link.getAttribute("data-bs-target") === "#" + selectedTabId) {
          link.classList.add("active");
          link.setAttribute("aria-selected", "true");
        }
      });
    }

    // Initialize the dropdown change event
    if (procedureDropdown) {
      // Set initial state - ensure the first tab content is visible
      const initialTabId = procedureDropdown.value;
      showSelectedTabContent(initialTabId);

      // Handle dropdown change event
      procedureDropdown.addEventListener("change", function () {
        const selectedTabId = this.value;
        showSelectedTabContent(selectedTabId);
      });

      // Force a change event on page load to ensure content is visible
      const event = new Event("change");
      procedureDropdown.dispatchEvent(event);
    }

    // Also handle tab clicks for desktop view
    const tabLinks = document.querySelectorAll(".procedure-tabs .nav-link");
    tabLinks.forEach((link) => {
      link.addEventListener("click", function () {
        const targetId = this.getAttribute("data-bs-target").substring(1); // Remove the # character

        // Update the dropdown value to match (for when switching to mobile view)
        if (procedureDropdown) {
          procedureDropdown.value = targetId;
        }
      });
    });
  }

  // Initialize all functionality
  initVideoGlide();
  initStatsAnimation();
  initProcedureDropdown();
});

// ===== TAB NAVIGATION (Clean & Efficient) =====
document.addEventListener("DOMContentLoaded", function () {
  // Run arrow logic only ≥ md (≥ 768px). Early-exit on mobile.
  if (window.matchMedia("(min-width: 768px)").matches) {
    initTabArrows();
  }

  function initTabArrows() {
    const tabs = document.querySelector(".procedure-tabs");
    const prevArrow = document.querySelector(".prev-arrow");
    const nextArrow = document.querySelector(".next-arrow");

    if (!tabs || !prevArrow || !nextArrow) return;

    let currentTabIndex = 0;
    const tabLinks = tabs.querySelectorAll(".nav-link");

    // Helper – keep arrows live but disable when useless
    function updateArrows() {
      const maxScroll = tabs.scrollWidth - tabs.clientWidth;

      // Debug logging (remove in production)
      console.log("Debug arrows:", {
        scrollWidth: tabs.scrollWidth,
        clientWidth: tabs.clientWidth,
        maxScroll: maxScroll,
        scrollLeft: tabs.scrollLeft,
        tabCount: tabLinks.length,
        currentTabIndex: currentTabIndex,
      });

      // Always enable arrows - they work regardless of overflow
      prevArrow.disabled = false;
      nextArrow.disabled = false;
    }

    // Enhanced click handlers that work with or without overflow
    function handleArrowClick(direction) {
      const maxScroll = tabs.scrollWidth - tabs.clientWidth;

      if (maxScroll > 0) {
        // Normal scroll behavior when overflow exists
        const step = 150;
        tabs.scrollBy({ left: direction * step, behavior: "smooth" });
      } else {
        // Tab cycling when no overflow
        if (direction > 0) {
          // Next tab
          currentTabIndex = (currentTabIndex + 1) % tabLinks.length;
        } else {
          // Previous tab
          currentTabIndex = (currentTabIndex - 1 + tabLinks.length) % tabLinks.length;
        }

        // Activate the new tab
        tabLinks[currentTabIndex].click();
      }
    }

    // Click handlers
    prevArrow.addEventListener("click", () => handleArrowClick(-1));
    nextArrow.addEventListener("click", () => handleArrowClick(1));

    // Track active tab changes
    tabLinks.forEach((link, index) => {
      link.addEventListener("click", () => {
        currentTabIndex = index;
      });
    });

    // Keep arrows in sync
    tabs.addEventListener("scroll", updateArrows);
    window.addEventListener("resize", updateArrows, { passive: true });

    // Initial call
    updateArrows();

    // Call after fonts are ready
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(updateArrows);
    }

    // Call after first paint
    requestAnimationFrame(() => requestAnimationFrame(updateArrows));

    // Force re-calc after everything settles
    window.addEventListener("load", () => {
      // All images, web-fonts, etc. are done
      updateArrows();
    });
  }
});
