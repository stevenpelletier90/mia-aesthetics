/**
 * Header-specific JavaScript functionality
 * Includes theme debug display when WP_DEBUG is enabled
 */

document.addEventListener("DOMContentLoaded", function () {
  // Theme Debug Display
  if (window.mia_config && window.mia_config.debug && window.mia_config.debug_info) {
    createDebugDisplay();
  }

  // Prevent mega-menu dropdowns from closing when clicking on background area
  initializeMegaMenus();
});

function createDebugDisplay() {
  const debugInfo = window.mia_config.debug_info;

  // Create debug panel
  const debugPanel = document.createElement("div");
  debugPanel.id = "mia-debug-panel";
  debugPanel.style.cssText = `
    position: fixed;
    top: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.9);
    color: #fff;
    padding: 15px;
    border-radius: 5px;
    font-family: monospace;
    font-size: 12px;
    max-width: 400px;
    z-index: 9999;
    border: 2px solid #ffa500;
    max-height: 80vh;
    overflow-y: auto;
    display: none;
  `;

  // Create toggle button
  const toggleButton = document.createElement("button");
  toggleButton.innerHTML = "🐞 DEBUG";
  toggleButton.style.cssText = `
    position: fixed;
    top: 10px;
    right: 10px;
    background: #ffa500;
    color: #000;
    border: none;
    padding: 8px 12px;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
    font-size: 11px;
  `;

  // Toggle functionality
  let isOpen = false;
  toggleButton.addEventListener("click", () => {
    isOpen = !isOpen;
    debugPanel.style.display = isOpen ? "block" : "none";
    toggleButton.innerHTML = isOpen ? "✕ CLOSE" : "🐞 DEBUG";
    toggleButton.style.right = isOpen ? "420px" : "10px";
  });

  // Build debug content
  let content = `<h3 style="color: #ffa500; margin-top: 0;">🎨 Theme Debug Info</h3>`;

  content += `<h4>📄 Current Template</h4>`;
  content += `<div>Template Key: <span style="color: #00ff00;">${debugInfo.template_key}</span></div>`;
  content += `<div>Page Template: <span style="color: #00ff00;">${debugInfo.page_template || "none"}</span></div>`;
  content += `<div>Post Type: <span style="color: #00ff00;">${debugInfo.post_type || "none"}</span></div>`;

  content += `<h4>🎯 Page Conditions</h4>`;
  content += `<div>is_singular: <span style="color: ${debugInfo.is_singular ? "#00ff00" : "#ff0000"};">${debugInfo.is_singular}</span></div>`;
  content += `<div>is_archive: <span style="color: ${debugInfo.is_archive ? "#00ff00" : "#ff0000"};">${debugInfo.is_archive}</span></div>`;
  content += `<div>is_home: <span style="color: ${debugInfo.is_home ? "#00ff00" : "#ff0000"};">${debugInfo.is_home}</span></div>`;
  content += `<div>is_front_page: <span style="color: ${debugInfo.is_front_page ? "#00ff00" : "#ff0000"};">${debugInfo.is_front_page}</span></div>`;

  if (debugInfo.template_mapping && Object.keys(debugInfo.template_mapping).length > 0) {
    content += `<h4>📂 Template Assets</h4>`;
    if (debugInfo.template_mapping.css) {
      content += `<div>CSS: <span style="color: #00ff00;">${debugInfo.template_mapping.css}</span></div>`;
    }
    if (debugInfo.template_mapping.js) {
      content += `<div>JS: <span style="color: #00ff00;">${debugInfo.template_mapping.js}</span></div>`;
    }
  }

  if (debugInfo.enqueued_styles && debugInfo.enqueued_styles.length > 0) {
    content += `<h4>🎨 Enqueued Styles</h4>`;
    debugInfo.enqueued_styles.forEach((style) => {
      content += `<div style="color: #87ceeb;">• ${style}</div>`;
    });
  }

  if (debugInfo.enqueued_scripts && debugInfo.enqueued_scripts.length > 0) {
    content += `<h4>📜 Enqueued Scripts</h4>`;
    debugInfo.enqueued_scripts.forEach((script) => {
      content += `<div style="color: #87ceeb;">• ${script}</div>`;
    });
  }

  content += `<div style="margin-top: 15px; padding-top: 10px; border-top: 1px solid #555; font-size: 10px; color: #888;">
    Keyboard: Press 'D' to toggle debug panel
  </div>`;

  debugPanel.innerHTML = content;

  // Add to page
  document.body.appendChild(toggleButton);
  document.body.appendChild(debugPanel);

  // Keyboard shortcut
  document.addEventListener("keydown", (e) => {
    if (e.key.toLowerCase() === "d" && !e.ctrlKey && !e.altKey && !e.shiftKey) {
      const activeElement = document.activeElement;
      if (
        activeElement &&
        (activeElement.tagName === "INPUT" || activeElement.tagName === "TEXTAREA")
      ) {
        return; // Don't trigger if typing in input fields
      }
      toggleButton.click();
    }
  });
}

/**
 * Initialize mega-menu dropdowns to prevent closing when clicking background
 */
function initializeMegaMenus() {
  const megaMenus = document.querySelectorAll(".dropdown-menu.mega-menu");

  megaMenus.forEach(function (menu) {
    menu.addEventListener("click", function (event) {
      // Only prevent closing if clicking on the menu background, not on links
      if (event.target.tagName !== "A" && !event.target.closest("a")) {
        event.stopPropagation();
      }
    });
  });
}
