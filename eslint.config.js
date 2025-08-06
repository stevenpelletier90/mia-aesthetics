import js from '@eslint/js';

export default [
  js.configs.recommended,
  {
    files: ['assets/js/**/*.js'],
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: 'script',
      globals: {
        window: 'readonly',
        document: 'readonly',
        jQuery: 'readonly',
        $: 'readonly',
        wp: 'readonly',
        console: 'readonly',
        gtag: 'readonly',
        bootstrap: 'readonly',
        google: 'readonly',
        geocoder: 'readonly',
        IntersectionObserver: 'readonly',
        setTimeout: 'readonly',
        setInterval: 'readonly',
        clearTimeout: 'readonly',
        clearInterval: 'readonly',
        requestAnimationFrame: 'readonly',
        fetch: 'readonly',
        Event: 'readonly',
        URLSearchParams: 'readonly',
        location: 'readonly',
        history: 'readonly',
        performance: 'readonly',
        navigator: 'readonly',
        initializeAutocomplete: 'readonly',
        initializeAutocompleteCareers: 'readonly'
      }
    },
    rules: {
      'no-console': 'warn',
      'no-unused-vars': 'warn',
      'prefer-const': 'error'
    }
  }
];