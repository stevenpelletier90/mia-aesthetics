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
					URL: 'readonly',
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
			'prefer-const': 'error',
			'no-undef': 'error',
			'no-redeclare': 'error',
			'no-unused-expressions': 'error',
			'curly': 'error',
			'eqeqeq': 'error',
			'no-eval': 'error',
			'no-implied-eval': 'error'
		}
	},
	{
		files: ['scripts/**/*.js'],
		languageOptions: {
			ecmaVersion: 2022,
			sourceType: 'module',
			globals: {
				console: 'readonly',
				process: 'readonly',
				__dirname: 'readonly',
				__filename: 'readonly'
			}
		},
		rules: {
			'no-console': 'off',
			'no-unused-vars': 'warn',
			'prefer-const': 'error',
			'no-undef': 'error'
		}
	}
	];