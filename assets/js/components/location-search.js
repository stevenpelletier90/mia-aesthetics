/**
 * Location Search JavaScript
 *
 * Handles the location search functionality with Google Maps integration
 *
 * @package Mia_Aesthetics
 */

(function () {
  "use strict";

  // Global variables
  let locations = [];
  let userLocation = null;
  let currentResults = [];
  let currentHighlightIndex = -1;

  // Define Google Maps callback immediately (before DOM load)
  window.initGoogleMaps = function () {
    if ("function" === typeof initializeAutocomplete) {
      initializeAutocomplete();
    }
  };

  // Search configuration
  const SEARCH_CONFIG = {
    MAX_DISTANCE_MILES: 100,
    MAX_RESULTS: 3,
    MIN_SEARCH_LENGTH: 2,
    DEBOUNCE_DELAY: 300,
  };

  // Cache geocoding results
  const geocodeCache = new Map();
  const CACHE_DURATION = 1000 * 60 * 60; // 1 hour

  // DOM elements
  const searchInput = document.getElementById("location-search");
  const searchDropdown = document.getElementById("search-dropdown");
  const loadingSpinner = document.getElementById("loading-spinner");
  const searchIcon = document.querySelector(".search-icon");

  // Initialize when page loads
  document.addEventListener("DOMContentLoaded", function () {
    // Test if input events work even without Google Maps
    if (searchInput) {
      const testInputHandler = function () {
        // Input event handler for testing
      };
      searchInput.addEventListener("input", testInputHandler);

      // Store reference so we can remove it later
      searchInput._testHandler = testInputHandler;
    }

    loadLocations();

    // Check if Google Maps is loading
    setTimeout(() => {
      // If Google Maps is available but callback wasn't called, call it manually
      if (
        "undefined" !== typeof google &&
        google.maps &&
        "function" === typeof initializeAutocomplete
      ) {
        initializeAutocomplete();
      }
    }, 3000);
  });

  // Google Maps callback is defined above before DOM load

  // Load locations from WordPress API
  async function loadLocations() {
    try {
      const response = await fetch(
        "/wp-json/wp/v2/location?per_page=100&parent=0&_fields=id,title,link,acf"
      );
      if (!response.ok) {
        throw new Error("Failed to load locations");
      }

      const data = await response.json();
      locations = data.map((location) => ({
        id: location.id,
        title: location.title.rendered,
        url: location.link,
        acf: location.acf || {},
        coordinates: getCoordinates(location.acf),
        distance: null,
      }));
    } catch {
      // Continue with empty locations if API fails
    }
  }

  // Extract coordinates from ACF field
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

  // Initialize search functionality
  window.initializeAutocomplete = function () {
    try {
      if ("undefined" === typeof google || !google.maps) {
        return;
      }

      window.geocoder = new google.maps.Geocoder();

      // Remove the test input listener first
      if (searchInput._testHandler) {
        searchInput.removeEventListener("input", searchInput._testHandler);
        delete searchInput._testHandler;
      }

      searchInput.addEventListener("input", debouncedSearch);
      searchInput.addEventListener("keydown", handleKeyNavigation);
    } catch {
      // Continue without Google Maps functionality
    }
  };

  // Debounce function
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Search handler
  function handleSearch() {
    const originalQuery = searchInput.value ? searchInput.value.trim() : "";
    const query = originalQuery.toLowerCase();

    if (query.length < SEARCH_CONFIG.MIN_SEARCH_LENGTH) {
      hideDropdown();
      return;
    }

    const exactMatches = findExactMatches(query);

    if (0 < exactMatches.length) {
      geocodeAndShowResults(query, exactMatches, true, originalQuery);
    } else {
      geocodeAndShowResults(query, [], false, originalQuery);
    }
  }

  function findExactMatches(query) {
    return locations.filter((location) => {
      const locationMap = location.acf.location_map;
      if (!locationMap) {
        return false;
      }

      const searchFields = [
        location.title,
        locationMap.city,
        locationMap.state,
        locationMap.state_short,
      ]
        .filter(Boolean)
        .map((field) => field.toLowerCase());

      return searchFields.some((field) => field.includes(query));
    });
  }

  const debouncedSearch = debounce(handleSearch, SEARCH_CONFIG.DEBOUNCE_DELAY);

  // Geocoding with caching
  function geocodeAndShowResults(query, exactMatches, hasExactMatches, originalQuery) {
    if (!window.geocoder) {
      return;
    }

    const cacheKey = query.toLowerCase();
    const cached = geocodeCache.get(cacheKey);

    if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
      userLocation = cached.location;
      calculateDistances();
      displaySimplifiedResults(query, exactMatches, hasExactMatches, originalQuery);
      return;
    }
    showLoading();

    geocoder.geocode(
      {
        address: query + ", USA",
        componentRestrictions: { country: "US" },
      },
      (results, status) => {
        if ("OK" === status && results[0]) {
          userLocation = {
            lat: results[0].geometry.location.lat(),
            lng: results[0].geometry.location.lng(),
          };

          geocodeCache.set(cacheKey, {
            location: userLocation,
            timestamp: Date.now(),
          });

          calculateDistances();
          displaySimplifiedResults(query, exactMatches, hasExactMatches, originalQuery);
        } else {
          hideLoading();
          searchDropdown.innerHTML = '<div class="no-results">No locations found</div>';
          showDropdown();
        }
      }
    );
  }

  // Display results
  function displaySimplifiedResults(searchQuery, exactMatches, hasExactMatches, originalQuery) {
    let resultsToShow = [];

    if (hasExactMatches) {
      const exactResults = exactMatches.map((loc) => ({ ...loc, isExactMatch: true }));
      const alternatives = locations
        .filter(
          (loc) =>
            loc.coordinates &&
            null !== loc.distance &&
            !exactMatches.some((exact) => exact.id === loc.id)
        )
        .sort((a, b) => (a.distance || Infinity) - (b.distance || Infinity))
        .slice(0, 2)
        .map((loc) => ({ ...loc, isExactMatch: false }));

      resultsToShow = [...exactResults, ...alternatives];
    } else {
      resultsToShow = locations
        .filter((loc) => loc.coordinates && null !== loc.distance)
        .sort((a, b) => (a.distance || Infinity) - (b.distance || Infinity))
        .slice(0, SEARCH_CONFIG.MAX_RESULTS)
        .map((loc) => ({ ...loc, isExactMatch: false }));
    }

    if (0 === resultsToShow.length) {
      searchDropdown.innerHTML = '<div class="no-results">No locations available</div>';
    } else {
      displayResultsWithDistance(
        resultsToShow,
        originalQuery || searchQuery,
        hasExactMatches ? "exact" : "nearby"
      );
    }

    hideLoading();
    showDropdown();
  }

  // Calculate distances
  function calculateDistances() {
    if (!userLocation) {
      return;
    }

    locations.forEach((location) => {
      if (location.coordinates) {
        location.distance = calculateDistance(
          userLocation.lat,
          userLocation.lng,
          location.coordinates.lat,
          location.coordinates.lng
        );
      }
    });
  }

  // Haversine formula for distance calculation
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

  function toRadians(degrees) {
    return degrees * (Math.PI / 180);
  }

  // Display results with distance
  function displayResultsWithDistance(results, searchQuery, searchType = "nearby") {
    currentResults = results;
    currentHighlightIndex = -1;

    let headerHtml = "";
    if ("exact" === searchType) {
      headerHtml = `
        <div style="padding: 0.75rem 1.5rem; background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.85rem; color: rgba(255, 255, 255, 0.8);">
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <span style="background-color: var(--color-gold); color: var(--color-primary); padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.25rem; flex-shrink: 0;"><i class="fas fa-star" style="font-size: 0.7rem;"></i></span>
            <span><strong style="color: #fff;">${escapeHtml(searchQuery)}</strong> + closest alternatives</span>
          </div>
        </div>
      `;
    } else {
      const closestDistance = results[0]?.distance || 0;
      const distanceNote = 100 < closestDistance ? " (nearest options)" : "";

      headerHtml = `
        <div style="padding: 0.75rem 1.5rem; background: rgba(255, 255, 255, 0.05); border-bottom: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.85rem; color: rgba(255, 255, 255, 0.8);">
          <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-map-marker-alt" style="color: var(--color-gold); flex-shrink: 0;"></i>
            <span>Closest locations to <strong style="color: #fff;">${escapeHtml(searchQuery)}</strong>${distanceNote}</span>
          </div>
        </div>
      `;
    }

    const html = results
      .map((location) => {
        const address = formatAddress(location.acf);

        let matchLabel = "";
        if (location.isExactMatch) {
          matchLabel =
            '<span style="background-color: var(--color-gold); color: var(--color-primary); font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 12px; display: inline-flex; align-items: center; gap: 0.2rem; white-space: nowrap;"><i class="fas fa-star" style="font-size: 0.6rem;"></i>Match</span>';
        }

        let distanceHtml = "";
        if (null !== location.distance) {
          const distanceText = `${location.distance.toFixed(1)} miles away`;
          distanceHtml = `<div class="location-distance">${distanceText}</div>`;
        }

        return `
          <div class="dropdown-item" role="option" data-location-id="${location.id}" aria-selected="false">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: ${matchLabel ? "0.25rem" : "0"};">
              <h4>${escapeHtml(location.title)}</h4>
              ${matchLabel}
            </div>
            ${address ? `<div class="location-address">${address}</div>` : ""}
            ${distanceHtml}
          </div>
        `;
      })
      .join("");

    searchDropdown.innerHTML = headerHtml + html;
    showDropdown();

    const items = searchDropdown.querySelectorAll(".dropdown-item");
    items.forEach((item, index) => {
      item.addEventListener("click", () => selectLocation(results[index]));
    });
  }

  // Navigate to selected location
  function selectLocation(location) {
    window.location.href = location.url;
  }

  // Format address
  function formatAddress(acf) {
    const locationMap = acf.location_map;
    if (!locationMap) {
      return "";
    }

    const addressParts = [];

    if (locationMap.street_number && locationMap.street_name) {
      addressParts.push(`<div>${locationMap.street_number} ${locationMap.street_name}</div>`);
    }

    const cityStateZip = [];
    let city = locationMap.city;

    // Special handling for locations where Google Maps doesn't populate city correctly
    if (!city && locationMap.state_short) {
      // For Brooklyn/NYC addresses, Google sometimes doesn't populate city
      if (
        "NY" === locationMap.state_short &&
        locationMap.street_name &&
        locationMap.street_name.toLowerCase().includes("atlantic")
      ) {
        city = "Brooklyn";
      }
    }

    if (city) {
      cityStateZip.push(city);
    }
    if (locationMap.state_short) {
      cityStateZip.push(locationMap.state_short);
    }
    if (locationMap.post_code) {
      cityStateZip.push(locationMap.post_code);
    }

    if (0 < cityStateZip.length) {
      addressParts.push(`<div>${cityStateZip.join(", ")}</div>`);
    }

    return addressParts.join("");
  }

  // Escape HTML
  function escapeHtml(unsafe) {
    if ("string" !== typeof unsafe) {
      return "";
    }
    return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }

  // Loading skeleton
  function createLoadingSkeleton() {
    return `
      <div class="loading-skeleton">
        <div class="skeleton-item">
          <div class="skeleton-title"></div>
          <div class="skeleton-address"></div>
          <div class="skeleton-distance"></div>
        </div>
        <div class="skeleton-item">
          <div class="skeleton-title"></div>
          <div class="skeleton-address"></div>
          <div class="skeleton-distance"></div>
        </div>
        <div class="skeleton-item">
          <div class="skeleton-title"></div>
          <div class="skeleton-address"></div>
          <div class="skeleton-distance"></div>
        </div>
      </div>
    `;
  }

  // UI functions
  function showLoading() {
    loadingSpinner.style.display = "block";
    searchIcon.style.display = "none";
    searchDropdown.innerHTML = createLoadingSkeleton();
    searchDropdown.querySelector(".loading-skeleton").style.display = "block";
    showDropdown();
  }

  function hideLoading() {
    loadingSpinner.style.display = "none";
    searchIcon.style.display = "block";
  }

  function showDropdown() {
    searchDropdown.style.display = "block";
    searchInput.setAttribute("aria-expanded", "true");
  }

  function hideDropdown() {
    searchDropdown.style.display = "none";
    currentHighlightIndex = -1;
    searchInput.setAttribute("aria-expanded", "false");
  }

  // Keyboard navigation
  function handleKeyNavigation(e) {
    const items = searchDropdown.querySelectorAll(".dropdown-item");

    if (0 === items.length) {
      return;
    }

    if ("ArrowDown" === e.key) {
      e.preventDefault();
      currentHighlightIndex = Math.min(currentHighlightIndex + 1, items.length - 1);
      updateHighlight(items);
    } else if ("ArrowUp" === e.key) {
      e.preventDefault();
      currentHighlightIndex = Math.max(currentHighlightIndex - 1, -1);
      updateHighlight(items);
    } else if ("Enter" === e.key && 0 <= currentHighlightIndex) {
      e.preventDefault();
      selectLocation(currentResults[currentHighlightIndex]);
    } else if ("Escape" === e.key) {
      hideDropdown();
      searchInput.blur();
    }
  }

  // Update visual highlight
  function updateHighlight(items) {
    items.forEach((item, index) => {
      if (index === currentHighlightIndex) {
        item.classList.add("highlighted");
        item.setAttribute("aria-selected", "true");
        item.scrollIntoView({ block: "nearest" });
        searchInput.setAttribute("aria-activedescendant", item.getAttribute("data-location-id"));
      } else {
        item.classList.remove("highlighted");
        item.setAttribute("aria-selected", "false");
      }
    });
  }

  // Hide dropdown when clicking outside
  document.addEventListener("click", function (e) {
    if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
      hideDropdown();
    }
  });

  // Handle escape key globally
  document.addEventListener("keydown", function (e) {
    if ("Escape" === e.key) {
      hideDropdown();
    }
  });
})();
