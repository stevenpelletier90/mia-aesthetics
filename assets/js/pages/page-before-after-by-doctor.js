/**
 * Gallery JS for Before & After by Doctor
 * Only loaded on the /before-after/before-after-by-doctor/ page template.
 * Handles surgeon filtering and Bootstrap carousel modal.
 * Accessibility: ensures keyboard navigation and ARIA compliance.
 */
document.addEventListener("DOMContentLoaded", function () {
  // Surgeon filter
  const select = document.getElementById("doctorSelect");
  const galleries = document.querySelectorAll(".gallery");

  const show = (val) => {
    if (!val || "" === val) {
      // Hide all galleries when no surgeon is selected
      galleries.forEach((g) => g.classList.add("d-none"));
    } else {
      // Show only the selected doctor's gallery
      galleries.forEach((g) => {
        if (g.dataset.doctor === val) {
          g.classList.remove("d-none");
        } else {
          g.classList.add("d-none");
        }
      });
    }
    if (history && history.replaceState) {
      history.replaceState({}, "", val ? `?doctor=${val}` : location.pathname);
    }
  };

  if (select) {
    select.addEventListener("change", (e) => show(e.target.value));
    const urlParams = new URLSearchParams(location.search);
    const doctorParam = urlParams.get("doctor");
    if (doctorParam) {
      select.value = doctorParam;
      show(doctorParam);
    } else {
      // Default to showing all doctors
      select.value = "";
      show("");
    }
  }

  // Bootstrap Carousel Modal
  const modalEl = document.getElementById("lightboxModal");
  const carouselInner = document.getElementById("carouselInner");
  const modalTitle = document.getElementById("lightboxModalLabel");
  let currentGalleryImages = [];

  if (modalEl) {
    // Handle modal show event
    modalEl.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      if (!button) {
        return;
      }

      // Get gallery info
      const galleryId = button.getAttribute("data-bs-gallery");
      const clickedIndex = parseInt(button.getAttribute("data-bs-index"));

      // Find all images in the same gallery
      const galleryButtons = document.querySelectorAll(`[data-bs-gallery="${galleryId}"]`);
      currentGalleryImages = [];

      // Clear carousel
      carouselInner.innerHTML = "";

      // Build carousel items
      galleryButtons.forEach((btn, index) => {
        const img = btn.querySelector("img");
        if (img) {
          currentGalleryImages.push({
            src: img.src,
            alt: img.alt,
          });

          const carouselItem = document.createElement("div");
          carouselItem.className = `carousel-item${index === clickedIndex ? " active" : ""}`;
          carouselItem.innerHTML = `<img src="${img.src}" class="d-block w-100" alt="${img.alt}">`;
          carouselInner.appendChild(carouselItem);
        }
      });

      // Set modal title
      const clickedImg = button.querySelector("img");
      if (clickedImg && clickedImg.alt) {
        const altParts = clickedImg.alt.split(" • ");
        if (2 <= altParts.length) {
          modalTitle.textContent = `${altParts[0]} - ${altParts[1]}`;
        } else {
          modalTitle.textContent = clickedImg.alt;
        }
      }

      // Initialize carousel to the clicked image
      const carousel = bootstrap.Carousel.getInstance(document.getElementById("galleryCarousel"));
      if (carousel) {
        carousel.to(clickedIndex);
      }
    });

    // Clear carousel when modal is hidden
    modalEl.addEventListener("hidden.bs.modal", function () {
      carouselInner.innerHTML = "";
      modalTitle.textContent = "";
      currentGalleryImages = [];
    });
  }

  // Make all gallery images clickable
  const galleryButtons = document.querySelectorAll(
    '[data-bs-toggle="modal"][data-bs-target="#lightboxModal"]'
  );
  galleryButtons.forEach((button) => {
    button.style.cursor = "pointer";
  });
});
