/* Archive Fat Transfer Template JavaScript */
// Template: archive-fat-transfer.php
// Fat transfer archive-specific functionality

/**
 * JavaScript specific to the "Fat Transfer" archive template
 * Based on condition layout pattern for consistent functionality
 */

document.addEventListener("DOMContentLoaded", function () {
  // Generate table of contents and handle scrolling
  setupTableOfContents();
});

/**
 * Setup Table of Contents and scrolling behavior
 */
function setupTableOfContents() {
  // Only run on pages with TOC
  const tocContainer = document.getElementById("tableOfContents");
  const tocList = tocContainer?.querySelector(".toc-list");

  if (!tocContainer || !tocList) {
    return;
  }

  // Get all h2 elements from the content section (not the overview)
  const contentSection = document.querySelector(".content");
  if (!contentSection) {
    return;
  }

  const headings = contentSection.querySelectorAll("h2");
  if (0 === headings.length) {
    return;
  }

  // Clear existing TOC items
  tocList.innerHTML = "";

  // Get navbar height for offset calculations
  const navbar =
    document.querySelector("header.position-sticky") || document.querySelector("header");
  const navbarHeight = navbar?.offsetHeight || 65;
  const scrollOffset = navbarHeight + 20; // Extra offset for better positioning

  // Add headings to TOC
  const tocLinks = [];
  headings.forEach((heading, index) => {
    // Ensure each heading has an ID
    if (!heading.id) {
      heading.id = "section-" + index;
    }

    // Create TOC list item
    const listItem = document.createElement("li");
    listItem.className = "nav-item";

    // Create link
    const link = document.createElement("a");
    link.className = "nav-link";
    link.href = "#" + heading.id;
    link.textContent = heading.textContent;

    // Add to TOC
    listItem.appendChild(link);
    tocList.appendChild(listItem);
    tocLinks.push(link);

    // Add click handler
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Get target element and scroll to it
      const targetElement = document.getElementById(heading.id);
      if (targetElement) {
        // Calculate precise offset position for perfect header clearance
        const offsetPosition = targetElement.offsetTop - scrollOffset;

        // Scroll to the calculated position
        window.scrollTo({
          top: offsetPosition,
          behavior: "smooth",
        });
      }
    });
  });

  // Simple scroll handler that highlights the correct TOC item
  window.addEventListener("scroll", function () {
    const scrollPosition = window.scrollY + scrollOffset;

    // Find the appropriate section
    let activeIndex = 0;

    // Determine which section we're currently in
    for (let i = 0; i < headings.length; i++) {
      // If we've scrolled past this heading but not the next one, this is active
      if (scrollPosition >= headings[i].offsetTop) {
        activeIndex = i;
      } else {
        // We haven't reached this heading yet, so the previous one is active
        break;
      }
    }

    // Update active classes
    tocLinks.forEach((link, i) => {
      if (i === activeIndex) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  });

  // Trigger scroll event once to set initial active state
  window.dispatchEvent(new Event("scroll"));
}