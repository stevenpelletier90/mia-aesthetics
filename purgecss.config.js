module.exports = {
  content: [
    // WordPress templates
    './*.php',
    './components/**/*.php',
    './inc/**/*.php',
    './page-*.php',
    './single-*.php',
    './archive-*.php',
    
    // JavaScript files
    './assets/js/**/*.js',
    
    // Any other content files
    './assets/css/**/*.css'
  ],
  css: [
    './assets/css/**/*.css'
  ],
  // Safelist important classes that might be dynamically generated
  safelist: [
    // WordPress classes
    /^wp-/,
    /^post-/,
    /^page-/,
    /^attachment-/,
    /^admin-/,
    
    // Bootstrap classes that might be used dynamically
    /^btn-/,
    /^text-/,
    /^bg-/,
    /^border-/,
    /^d-/,
    /^flex-/,
    /^justify-/,
    /^align-/,
    /^m[tblrxy]?-/,
    /^p[tblrxy]?-/,
    
    // Gravity Forms
    /^gform/,
    /^gfield/,
    /^ginput/,
    
    // Glide.js
    /^glide/,
    
    // FontAwesome
    /^fa-/,
    /^fas/,
    /^far/,
    /^fab/,
    
    // Common dynamic classes
    'active',
    'current',
    'current-menu-item',
    'current-page-ancestor',
    'hover',
    'focus',
    'visited'
  ],
  // Variables to keep
  variables: true,
  keyframes: true,
  fontFace: true
};