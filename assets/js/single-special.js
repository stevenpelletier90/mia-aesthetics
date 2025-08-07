/* Single Special Template JavaScript */
// Template: single-special.php
// Special offer-specific functionality

// Dropdown icon rotation for consultation card selects
document.addEventListener("DOMContentLoaded", function () {
  const consultationSelects = document.querySelectorAll(".consultation-card .gform_wrapper select");

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
});
