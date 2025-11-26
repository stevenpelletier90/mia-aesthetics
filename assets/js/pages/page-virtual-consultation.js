/**
 * Virtual Consultation Form
 * Phone/zip formatting, location auto-selection, and form submission
 * Uses HTML5 native validation for field validation
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
  const phoneInput = document.getElementById('phone');
  const zipCodeInput = document.getElementById('zip-code');

  // Store locations data for distance calculation
  let locations = [];
  let geocoder = null;

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
   * Find nearest location based on zip/postal code
   * @param {string} postalCode - US zip or Canadian postal code
   */
  function findNearestLocation(postalCode) {
    if (!geocoder || locations.length === 0) {
      return;
    }

    // Determine if US or Canadian format
    const isCanadian = /^[A-Z]\d[A-Z]/i.test(postalCode);
    const country = isCanadian ? 'CA' : 'US';

    geocoder.geocode(
      {
        address: postalCode + ', ' + country,
        componentRestrictions: { country: country },
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

  /**
   * Handle zip code input - format and trigger location lookup
   * @param {Event} event - The input event
   */
  function handleZipCodeInput(event) {
    const field = event.target;
    if (field.id === 'zip-code') {
      // Allow alphanumeric and space for Canadian postal codes
      let value = field.value.toUpperCase().replace(/[^A-Z0-9\s]/g, '');

      // Check if it looks like a US zip (all digits)
      const isUSFormat = /^\d+$/.test(value.replace(/\s/g, ''));

      if (isUSFormat) {
        // US zip: limit to 5 digits
        value = value.replace(/\D/g, '').slice(0, 5);
      } else {
        // Canadian: limit to 7 chars (including space)
        value = value.slice(0, 7);
      }

      field.value = value;

      // Trigger location lookup for valid formats
      const usZipRegex = /^\d{5}$/;
      const canadianRegex = /^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/;

      if ((usZipRegex.test(value) || canadianRegex.test(value)) && geocoder) {
        findNearestLocation(value);
      }
    }
  }

  /**
   * Handle location select change - hide hint when user manually changes
   * @param {Event} event - The change event
   */
  function handleLocationChange(event) {
    if (event.target.id === 'location' && locationHint) {
      locationHint.hidden = true;
    }
  }

  /**
   * Handle form submission
   * @param {Event} event - The submit event
   */
  async function handleSubmit(event) {
    event.preventDefault();

    // Mark form as submitted to enable CSS validation styling
    form.classList.add('was-submitted');

    // Use HTML5 native validation
    if (!form.checkValidity()) {
      form.reportValidity();
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

      // Show error to user
      // eslint-disable-next-line no-undef
      alert(
        'There was an error submitting your request. Please try again later.'
      );
    }
  }

  // Event listeners
  form.addEventListener('submit', handleSubmit);

  if (phoneInput) {
    phoneInput.addEventListener('input', handlePhoneInput);
  }

  if (zipCodeInput) {
    zipCodeInput.addEventListener('input', handleZipCodeInput);
  }

  if (locationSelect) {
    locationSelect.addEventListener('change', handleLocationChange);
  }

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
