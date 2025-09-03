import { purgeCSSPlugin } from '@fullhuman/postcss-purgecss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

const plugins = [
  autoprefixer({
    grid: true,
    overrideBrowserslist: [
      "> 0.2%",
      "last 3 versions", 
      "not dead",
      "not IE 11"
    ]
  }),
  cssnano({
    preset: "default",
  })
];

// Add PurgeCSS only in production
if (process.env.NODE_ENV === 'production') {
  plugins.push(
    purgeCSSPlugin({
      content: [
        './**/*.php',
        './assets/js/**/*.js', 
        './html-templates/**/*.html'
      ],
      safelist: [
        // WordPress classes
        /^wp-/,
        /^has-/,
        /^is-/,
        /^post-/,
        /^page-/,
        /^single-/,
        /^archive-/,
        /^admin-/,
        
        // Bootstrap JavaScript classes
        /^bs-/,
        /^data-bs-/,
        'active', 'show', 'collapse', 'collapsed', 'collapsing',
        'fade', 'modal-backdrop', 'offcanvas-backdrop',
        
        // Navigation states
        'current-menu-item', 'current-menu-ancestor', 'current-page-ancestor',
        
        // Common state classes
        'loading', 'disabled', 'visible', 'hidden'
      ],
      // Skip PurgeCSS for large files that need all styles
      rejected: true,
      printRejected: false
    })
  );
}

export default {
  plugins
};
