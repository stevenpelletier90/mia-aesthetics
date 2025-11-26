/**
 * Virtual Consultation Form
 * Modern form validation, location auto-selection, and UX enhancements
 *
 * @package Mia_Aesthetics
 */

(function () {
  'use strict';

  const form = document.getElementById('virtual-consultation-form');
  if (!form) {
    return;
  }

  const formCard = form.closest('.consultation-form-card');
  const successMessage = formCard?.querySelector('.form-success');
  const submitButton = form.querySelector('.btn-submit');
  const locationSelect = document.getElementById('location');
  const locationHint = document.getElementById('location-hint');

  // Store locations data
  let locations = [];
  let geocoder = null;

  /**
   * Validation rules for each field
   */
  const validators = {
    'first-name': {
      validate: (value) => value.trim().length >= 2,
      message: 'Please enter your first name (at least 2 characters).',
    },
    'last-name': {
      validate: (value) => value.trim().length >= 2,
      message: 'Please enter your last name (at least 2 characters).',
    },
    email: {
      validate: (value) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(value.trim());
      },
      message: 'Please enter a valid email address.',
    },
    phone: {
      validate: (value) => {
        // Accept various phone formats, minimum 10 digits
        const digits = value.replace(/\D/g, '');
        return digits.length >= 10;
      },
      message: 'Please enter a valid phone number.',
    },
    'preferred-language': {
      validate: (value) => value.trim().length > 0,
      message: 'Please select your preferred language.',
    },
    'zip-code': {
      validate: (value) => /^\d{5}$/.test(value.trim()),
      message: 'Please enter a valid 5-digit zip code.',
    },
    location: {
      validate: (value) => value.trim().length > 0,
      message: 'Please select a location.',
    },
    consent: {
      validate: (_, element) => element.checked,
      message: 'You must agree to continue.',
    },
  };

  /**
   * Initialize Google Maps Geocoder
   */
  function initGeocoder() {
    if (typeof google !== 'undefined' && google.maps) {
      geocoder = new google.maps.Geocoder();
    }
  }

  /**
   * Load location coordinates from WordPress REST API
   * The dropdown is pre-populated by PHP, we just need coordinates for distance calc
   */
  async function loadLocationCoordinates() {
    try {
      const response = await fetch(
        '/wp-json/wp/v2/location?per_page=100&parent=0&_fields=id,title,acf'
      );
      if (!response.ok) {
        throw new Error('Failed to load locations');
      }

      const data = await response.json();
      locations = data
        .map((location) => ({
          id: location.id,
          title: location.title.rendered,
          coordinates: getCoordinates(location.acf),
        }))
        .filter((loc) => loc.coordinates !== null);
    } catch (error) {
      // eslint-disable-next-line no-console
      console.error('Error loading location coordinates:', error);
      // Form still works, just without auto-selection
    }
  }

  /**
   * Extract coordinates from ACF field
   * @param {Object} acf - ACF field data
   * @returns {Object|null} - Coordinates object or null
   */
  function getCoordinates(acf) {
    if (!acf || !acf.location_map) {
      return null;
    }

    const locationMap = acf.location_map;
    if (locationMap.lat && locationMap.lng) {
      return {
        lat: parseFloat(locationMap.lat),
        lng: parseFloat(locationMap.lng),
      };
    }
    return null;
  }

  /**
   * Find nearest location based on zip code
   * @param {string} zipCode - 5-digit zip code
   */
  function findNearestLocation(zipCode) {
    if (!geocoder || locations.length === 0) {
      return;
    }

    geocoder.geocode(
      {
        address: zipCode + ', USA',
        componentRestrictions: { country: 'US' },
      },
      (results, status) => {
        if (status === 'OK' && results[0]) {
          const userLat = results[0].geometry.location.lat();
          const userLng = results[0].geometry.location.lng();

          // Calculate distances to all locations
          const locationsWithDistance = locations.map((loc) => ({
            ...loc,
            distance: calculateDistance(
              userLat,
              userLng,
              loc.coordinates.lat,
              loc.coordinates.lng
            ),
          }));

          // Find the nearest location
          const nearest = locationsWithDistance.reduce((prev, curr) =>
            prev.distance < curr.distance ? prev : curr
          );

          // Select the nearest location
          if (locationSelect) {
            locationSelect.value = nearest.id.toString();
            clearValidation(locationSelect);

            // Show the hint
            if (locationHint) {
              locationHint.hidden = false;
            }
          }
        }
      }
    );
  }

  /**
   * Calculate distance between two points using Haversine formula
   * @param {number} lat1 - Latitude of point 1
   * @param {number} lng1 - Longitude of point 1
   * @param {number} lat2 - Latitude of point 2
   * @param {number} lng2 - Longitude of point 2
   * @returns {number} - Distance in miles
   */
  function calculateDistance(lat1, lng1, lat2, lng2) {
    const R = 3959; // Earth's radius in miles
    const dLat = toRadians(lat2 - lat1);
    const dLng = toRadians(lng2 - lng1);

    const a =
      Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(toRadians(lat1)) *
        Math.cos(toRadians(lat2)) *
        Math.sin(dLng / 2) *
        Math.sin(dLng / 2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  }

  /**
   * Convert degrees to radians
   * @param {number} degrees - Degrees to convert
   * @returns {number} - Radians
   */
  function toRadians(degrees) {
    return degrees * (Math.PI / 180);
  }

  /**
   * Format phone number as user types
   * @param {string} value - Raw input value
   * @returns {string} - Formatted phone number
   */
  function formatPhoneNumber(value) {
    const digits = value.replace(/\D/g, '');
    const length = digits.length;

    if (length === 0) {
      return '';
    }
    if (length < 4) {
      return `(${digits}`;
    }
    if (length < 7) {
      return `(${digits.slice(0, 3)}) ${digits.slice(3)}`;
    }
    return `(${digits.slice(0, 3)}) ${digits.slice(3, 6)}-${digits.slice(6, 10)}`;
  }

  /**
   * Validate a single field
   * @param {HTMLElement} field - The form field to validate
   * @returns {boolean} - Whether the field is valid
   */
  function validateField(field) {
    const fieldId = field.id;
    const validator = validators[fieldId];

    if (!validator) {
      return true;
    }

    const isValid = validator.validate(field.value, field);
    const feedback = field
      .closest('.form-group, .form-check')
      ?.querySelector('.invalid-feedback');

    if (isValid) {
      field.classList.remove('is-invalid');
      field.classList.add('is-valid');
      field.setAttribute('aria-invalid', 'false');
    } else {
      field.classList.remove('is-valid');
      field.classList.add('is-invalid');
      field.setAttribute('aria-invalid', 'true');

      if (feedback) {
        feedback.textContent = validator.message;
      }
    }

    return isValid;
  }

  /**
   * Clear validation state from a field
   * @param {HTMLElement} field - The form field to clear
   */
  function clearValidation(field) {
    field.classList.remove('is-valid', 'is-invalid');
    field.removeAttribute('aria-invalid');
  }

  /**
   * Validate all form fields
   * @returns {boolean} - Whether all fields are valid
   */
  function validateForm() {
    const fields = form.querySelectorAll('.form-control, .form-check-input');
    let isFormValid = true;
    let firstInvalidField = null;

    fields.forEach((field) => {
      const isValid = validateField(field);
      if (!isValid && !firstInvalidField) {
        firstInvalidField = field;
        isFormValid = false;
      } else if (!isValid) {
        isFormValid = false;
      }
    });

    // Focus the first invalid field for accessibility
    if (firstInvalidField) {
      firstInvalidField.focus();
    }

    return isFormValid;
  }

  /**
   * Handle form submission
   * @param {Event} event - The submit event
   */
  async function handleSubmit(event) {
    event.preventDefault();

    // Validate all fields
    if (!validateForm()) {
      return;
    }

    // Show loading state
    submitButton.disabled = true;
    submitButton.classList.add('is-loading');

    try {
      // Simulate API call (replace with actual endpoint)
      await new Promise((resolve) => setTimeout(resolve, 1500));

      // Collect form data (FormData is a browser global)
      // eslint-disable-next-line no-undef
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      // Log for demo purposes (remove in production)
      // eslint-disable-next-line no-console
      console.log('Form submitted:', data);

      // Show success message
      form.hidden = true;
      if (successMessage) {
        successMessage.hidden = false;
      }

      // Scroll to success message
      formCard?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } catch (error) {
      // eslint-disable-next-line no-console
      console.error('Form submission error:', error);

      // Reset button state on error
      submitButton.disabled = false;
      submitButton.classList.remove('is-loading');

      // Show error to user (alert is a browser global)
      // eslint-disable-next-line no-undef
      alert(
        'There was an error submitting your request. Please try again later.'
      );
    }
  }

  /**
   * Handle real-time validation on blur
   * @param {Event} event - The blur event
   */
  function handleBlur(event) {
    const field = event.target;
    if (field.matches('.form-control, .form-check-input')) {
      validateField(field);
    }
  }

  /**
   * Handle input changes to clear invalid state
   * @param {Event} event - The input event
   */
  function handleInput(event) {
    const field = event.target;
    if (
      field.matches('.form-control') &&
      field.classList.contains('is-invalid')
    ) {
      // Only clear the invalid state, don't validate yet
      clearValidation(field);
    }
  }

  /**
   * Handle checkbox change
   * @param {Event} event - The change event
   */
  function handleChange(event) {
    const field = event.target;
    if (field.matches('.form-check-input')) {
      validateField(field);
    }

    // Hide location hint if user manually changes location
    if (field.id === 'location' && locationHint) {
      locationHint.hidden = true;
    }
  }

  /**
   * Handle zip code input - restrict to numbers and trigger location lookup
   * @param {Event} event - The input event
   */
  function handleZipCodeInput(event) {
    const field = event.target;
    if (field.id === 'zip-code') {
      // Remove any non-numeric characters
      field.value = field.value.replace(/\D/g, '').slice(0, 5);

      // If we have a complete 5-digit zip code, find nearest location
      if (field.value.length === 5 && geocoder) {
        findNearestLocation(field.value);
      }
    }
  }

  /**
   * Handle phone number input - format as user types
   * @param {Event} event - The input event
   */
  function handlePhoneInput(event) {
    const field = event.target;
    if (field.id === 'phone') {
      const cursorPosition = field.selectionStart;
      const oldLength = field.value.length;
      field.value = formatPhoneNumber(field.value);
      const newLength = field.value.length;

      // Adjust cursor position after formatting
      const diff = newLength - oldLength;
      field.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
    }
  }

  // Event listeners
  form.addEventListener('submit', handleSubmit);
  form.addEventListener('blur', handleBlur, true);
  form.addEventListener('input', handleInput);
  form.addEventListener('input', handleZipCodeInput);
  form.addEventListener('input', handlePhoneInput);
  form.addEventListener('change', handleChange);

  // Initialize - load coordinates for auto-selection feature
  loadLocationCoordinates();

  // Initialize geocoder when Google Maps is ready
  if (typeof google !== 'undefined' && google.maps) {
    initGeocoder();
  } else {
    // Wait for Google Maps to load
    window.initVirtualConsultationGeocoder = initGeocoder;
  }

  // Prevent form resubmission on page refresh
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
})();
