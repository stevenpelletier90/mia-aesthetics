import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

export default {
  content: [
    // WordPress templates
    join(__dirname, '*.php'),
    join(__dirname, 'components/**/*.php'),
    join(__dirname, 'inc/**/*.php'),
    join(__dirname, 'page-*.php'),
    join(__dirname, 'single-*.php'),
    join(__dirname, 'archive-*.php'),
    join(__dirname, 'html-templates/**/*.html'),
    
    // JavaScript files
    join(__dirname, 'assets/js/**/*.js')
  ],
  css: [
    join(__dirname, 'assets/css/**/*.css')
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