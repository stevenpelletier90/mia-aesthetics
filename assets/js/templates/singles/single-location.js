/* Single Location Template JavaScript */
// Template: single-location.php
// Location-specific functionality

/**
 * JavaScript specific to the "Location" single template
 */

document.addEventListener("DOMContentLoaded", function () {
  // Dropdown icon rotation for consultation card selects
  const consultationSelects = document.querySelectorAll(".single-location .gform_wrapper select");

  consultationSelects.forEach((select) => {
    let isOpen = false;

    select.addEventListener("mousedown", function () {
      isOpen = !isOpen;
      const container = this.closest(".ginput_container_select");
      if (container) {
        if (isOpen) {
          container.classList.add("dropdown-open");
        } else {
          container.classList.remove("dropdown-open");
        }
      }
    });

    select.addEventListener("blur", function () {
      isOpen = false;
      const container = this.closest(".ginput_container_select");
      if (container) {
        container.classList.remove("dropdown-open");
      }
    });

    select.addEventListener("change", function () {
      isOpen = false;
      const container = this.closest(".ginput_container_select");
      if (container) {
        container.classList.remove("dropdown-open");
      }
    });
  });
  // Set up video thumbnail functionality
  setupVideoThumbnails();
});

/**
 * Video thumbnail functionality for location videos
 */
function setupVideoThumbnails() {
  // Find all video thumbnails on the page
  const videoThumbnails = document.querySelectorAll(".video-thumbnail");

  // Helper to detect mobile devices
  function isMobileDevice() {
    return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
      navigator.userAgent
    );
  }

  videoThumbnails.forEach((thumbnail) => {
    thumbnail.addEventListener("click", function () {
      // Get the embed URL from the data attribute
      let embedUrl = this.getAttribute("data-embed-url");

      if (!embedUrl) {
        return;
      }

      // Add autoplay and mute parameters to YouTube URL
      if (embedUrl.includes("youtube.com/embed/")) {
        // On mobile, force mute=1 for autoplay to work
        const isMobile = isMobileDevice();
        const muteParam = isMobile ? "mute=1" : "mute=0";
        embedUrl +=
          (embedUrl.includes("?") ? "&" : "?") + "autoplay=1&" + muteParam + "&controls=1&rel=0";
      }

      // Create an iframe element
      const iframe = document.createElement("iframe");
      iframe.src = embedUrl;
      iframe.title = "YouTube Video";
      iframe.frameBorder = "0";
      iframe.setAttribute("allowfullscreen", "");
      iframe.setAttribute(
        "allow",
        "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      );

      // Replace the thumbnail with the iframe
      this.parentNode.replaceChild(iframe, this);

      // Track video play event if analytics is available
      if ("function" === typeof gtag) {
        gtag("event", "play_video", {
          event_category: "Video",
          event_label: embedUrl,
        });
      }
    });
  });
}
