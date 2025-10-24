/**
 * Front Page JavaScript
 * Handles stats animations, tab navigation, and procedure dropdown
 */

document.addEventListener("DOMContentLoaded", function () {
  // ===== STATS COUNT-UP ANIMATION =====
  function initStatsAnimation() {
    // Get all stat number elements - updated selector for both old and new structures
    const statNumbers = document.querySelectorAll(
      ".display-5[data-count], .homepage-stat-number[data-count]"
    );

    if (0 === statNumbers.length) {
      return;
    }

    // Function to animate counting up
    function animateCountUp(element, target, duration, steps, suffix = "") {
      // Start from 0
      let start = 0;
      let currentStep = 0;

      // Get the element's parent to identify the patients counter and year
      let parentText = "";
      const oldStructure = element.closest(".position-relative");
      const newStructure = element.closest(".stat-box");

      if (oldStructure) {
        parentText = oldStructure.querySelector("p").textContent;
      } else if (newStructure) {
        parentText = newStructure.querySelector(".homepage-stat-label").textContent;
      }

      const isPatients = parentText.includes("Patient") || parentText.includes("PATIENT");
      const isYear = parentText.includes("Year") || parentText.includes("YEAR");

      // Calculate the value increment per step
      const valueIncrement = target / steps;

      // Set initial text to prevent wrapping on two lines
      if (isPatients) {
        element.textContent = "0";
        element.style.minWidth = "6ch"; // Reserve space for "150k+"
      }

      const timer = setInterval(() => {
        currentStep++;

        // If we've reached the final step, set the final value and clear the interval
        if (currentStep >= steps) {
          clearInterval(timer);

          // Format the final value based on counter type
          if (isYear) {
            // Don't add commas to years
            element.textContent = target + suffix;
          } else {
            // Use toLocaleString for formatting, then add suffix
            element.textContent = target.toLocaleString("en-US") + suffix;
          }

          // Add suffix with fade-in animation if it exists
          if (suffix) {
            element.classList.add("counting-complete");
          }
        } else {
          // Otherwise, update with the current count based on step
          start = valueIncrement * currentStep;
          const currentValue = Math.floor(start);

          // Format differently based on the type of counter
          if (isYear) {
            // Don't add commas to years
            element.textContent = currentValue + suffix;
          } else {
            // Use toLocaleString for all other counters
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

    if (!tabs || !prevArrow || !nextArrow) {
      return;
    }

    let currentTabIndex = 0;
    const tabLinks = tabs.querySelectorAll(".nav-link");

    // Helper – keep arrows live but disable when useless
    function updateArrows() {
      // Always enable arrows - they work regardless of overflow
      prevArrow.disabled = false;
      nextArrow.disabled = false;
    }

    // Enhanced click handlers that work with or without overflow
    function handleArrowClick(direction) {
      const maxScroll = tabs.scrollWidth - tabs.clientWidth;

      if (0 < maxScroll) {
        // Normal scroll behavior when overflow exists
        const step = 150;
        tabs.scrollBy({ left: direction * step, behavior: "smooth" });
      } else {
        // Tab cycling when no overflow
        if (0 < direction) {
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
