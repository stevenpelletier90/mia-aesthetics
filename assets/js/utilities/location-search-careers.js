/**
 * Location Search JavaScript - Careers Version
 *
 * Searches career location pages instead of main location CPTs
 * Shows full geographical names (e.g., Tampa–St. Petersburg)
 *
 * @package Mia_Aesthetics
 */

(function () {
  "use strict";

  // Global variables
  let careerLocations = [];
  let userLocation = null;
  let currentResults = [];
  let currentHighlightIndex = -1;

  // Define Google Maps callback immediately (before DOM load)
  window.initGoogleMapsCareers = function () {
    if ("function" === typeof initializeAutocompleteCareers) {
      initializeAutocompleteCareers();
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

  // DOM elements - using different IDs to avoid conflicts
  const searchInput = document.getElementById("location-search-careers");
  const searchDropdown = document.getElementById("search-dropdown-careers");
  const loadingSpinner = document.getElementById("loading-spinner-careers");
  const searchIcon = document.querySelector(".location-search-careers .search-icon");

  // Initialize when page loads
  document.addEventListener("DOMContentLoaded", function () {
    loadCareerLocations();
  });

  // Load career location pages from WordPress API
  async function loadCareerLocations() {
    try {
      // Query for pages using the careers-locations template
      const response = await fetch(
        "/wp-json/wp/v2/pages?per_page=100&meta_key=_wp_page_template&meta_value=page-careers-locations.php"
      );
      if (!response.ok) {
        throw new Error("Failed to load career locations");
      }

      const data = await response.json();

      // Process career pages and get their linked main locations
      careerLocations = [];

      // Process pages one by one to avoid rate limiting
      for (let i = 0; i < data.length; i++) {
        const page = data[i];

        try {
          // Get ACF fields for this page
          const acfResponse = await fetch(
            `/wp-json/wp/v2/pages/${page.id}?_fields=id,title,link,acf`
          );
          const pageWithAcf = await acfResponse.json();

          let coordinates = null;
          let locationData = null;

          // Only process pages that have a linked main location (true career location pages)
          if (pageWithAcf.acf && pageWithAcf.acf.linked_main_location) {
            try {
              const mainLocationId = pageWithAcf.acf.linked_main_location;

              const locationResponse = await fetch(
                `/wp-json/wp/v2/location/${mainLocationId}?_fields=acf`
              );
              const mainLocation = await locationResponse.json();

              if (mainLocation.acf && mainLocation.acf.location_map) {
                locationData = mainLocation.acf.location_map;
                coordinates = getCoordinates(mainLocation.acf);
              } else {
                // No location map data available
              }
            } catch {
              // Continue if location data unavailable
            }

            // Only add to results if we have a linked main location
            careerLocations.push({
              id: page.id,
              title: pageWithAcf.title.rendered,
              url: pageWithAcf.link,
              acf: pageWithAcf.acf || {},
              locationData: locationData,
              coordinates: coordinates,
              distance: null,
            });
          } else {
            // Skip pages without linked locations
          }

          // Add small delay between requests to avoid rate limiting
          if (i < data.length - 1) {
            await new Promise((resolve) => setTimeout(resolve, 100));
          }
        } catch {
          // Continue processing remaining pages
        }
      }

      // Filter out locations without coordinates
      const locationsWithCoordinates = careerLocations.filter((loc) => null !== loc.coordinates);

      careerLocations = locationsWithCoordinates;
    } catch {
      // Continue with empty locations array if API fails
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
  window.initializeAutocompleteCareers = function () {
    try {
      if ("undefined" === typeof google || !google.maps) {
        return;
      }

      window.geocoder = new google.maps.Geocoder();

      if (searchInput) {
        searchInput.addEventListener("input", debouncedSearch);
        searchInput.addEventListener("keydown", handleKeyNavigation);
      }
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
    return careerLocations.filter((location) => {
      const locationData = location.locationData;
      if (!locationData) {
        return false;
      }

      // Check various fields for matches
      const searchFields = [
        location.title,
        locationData.city,
        locationData.state,
        locationData.state_short,
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
          searchDropdown.innerHTML = '<div class="no-results">No career locations found</div>';
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
      const alternatives = careerLocations
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
      resultsToShow = careerLocations
        .filter((loc) => loc.coordinates && null !== loc.distance)
        .sort((a, b) => (a.distance || Infinity) - (b.distance || Infinity))
        .slice(0, SEARCH_CONFIG.MAX_RESULTS)
        .map((loc) => ({ ...loc, isExactMatch: false }));
    }

    if (0 === resultsToShow.length) {
      searchDropdown.innerHTML = '<div class="no-results">No career locations available</div>';
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

    careerLocations.forEach((location) => {
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
            <span>Closest career locations to <strong style="color: #fff;">${escapeHtml(searchQuery)}</strong>${distanceNote}</span>
          </div>
        </div>
      `;
    }

    const html = results
      .map((location) => {
        const address = formatAddress(location.locationData);

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
  function formatAddress(locationData) {
    if (!locationData) {
      return "";
    }

    const addressParts = [];

    if (locationData.street_number && locationData.street_name) {
      addressParts.push(`<div>${locationData.street_number} ${locationData.street_name}</div>`);
    }

    const cityStateZip = [];
    let city = locationData.city;

    // Special handling for locations where Google Maps doesn't populate city correctly
    if (!city && locationData.state_short) {
      // For Brooklyn/NYC addresses, Google sometimes doesn't populate city
      if (
        "NY" === locationData.state_short &&
        locationData.street_name &&
        locationData.street_name.toLowerCase().includes("atlantic")
      ) {
        city = "Brooklyn";
      }
    }

    if (city) {
      cityStateZip.push(city);
    }
    if (locationData.state_short) {
      cityStateZip.push(locationData.state_short);
    }
    if (locationData.post_code) {
      cityStateZip.push(locationData.post_code);
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
    if (loadingSpinner) {
      loadingSpinner.style.display = "block";
    }
    if (searchIcon) {
      searchIcon.style.display = "none";
    }
    searchDropdown.innerHTML = createLoadingSkeleton();
    searchDropdown.querySelector(".loading-skeleton").style.display = "block";
    showDropdown();
  }

  function hideLoading() {
    if (loadingSpinner) {
      loadingSpinner.style.display = "none";
    }
    if (searchIcon) {
      searchIcon.style.display = "block";
    }
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
    if (searchInput && !searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
      hideDropdown();
    }
  });

  // Handle escape key globally
  document.addEventListener("keydown", function (e) {
    if ("Escape" === e.key && searchDropdown) {
      hideDropdown();
    }
  });

  // Check if Google Maps is loaded after a delay
  setTimeout(() => {
    if (
      "undefined" !== typeof google &&
      google.maps &&
      "function" === typeof initializeAutocompleteCareers
    ) {
      initializeAutocompleteCareers();
    }
  }, 3000);
})();
