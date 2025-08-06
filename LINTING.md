# Linting Setup for Mia Aesthetics WordPress Theme

This theme now includes comprehensive linting for PHP, JavaScript, and CSS files.

## Installation

### 1. Install Node.js dependencies

```bash
npm install
```

### 2. Install PHP dependencies

```bash
composer install
```

## Available Commands

### Run all linters

```bash
npm run lint
```

### JavaScript Linting (ESLint)

```bash
# Check for issues
npm run lint:js

# Auto-fix issues
npm run lint:js:fix
```

### CSS Linting (Stylelint)

```bash
# Check for issues
npm run lint:css

# Auto-fix issues
npm run lint:css:fix
```

### PHP Linting (PHP CodeSniffer)

```bash
# Check for issues
composer run-script phpcs
# or
npm run lint:php

# Auto-fix issues
composer run-script phpcbf
```

### Code Formatting (Prettier)

```bash
# Format all files
npm run format

# Check formatting without changing files
npm run format:check
```

### Fix all issues at once

```bash
npm run lint:fix
```

## Configuration Files

- `.eslintrc.json` - JavaScript linting rules (WordPress standards)
- `.stylelintrc.json` - CSS linting rules (WordPress standards)
- `phpcs.xml` - PHP linting rules (WordPress Coding Standards)
- `.prettierrc` - Code formatting rules
- `.prettierignore` - Files to exclude from Prettier formatting

## IDE Integration

### VS Code

Install these extensions for real-time linting:

- ESLint
- Stylelint
- PHP Sniffer & Beautifier
- Prettier - Code formatter

### PHPStorm

PHPStorm has built-in support for all these tools. Configure them in:

- Settings > Languages & Frameworks > JavaScript > Code Quality Tools
- Settings > Languages & Frameworks > PHP > Quality Tools

## WordPress Coding Standards

This setup follows official WordPress coding standards:

- PHP: WordPress-Extra and WordPress-Docs standards
- JavaScript: WordPress ESLint config
- CSS: WordPress Stylelint config
- All integrated with Prettier for consistent formatting

## Ignoring Linting Rules

### JavaScript

```javascript
// eslint-disable-next-line rule-name
const example = 'value';

/* eslint-disable */
// Code block to ignore
/* eslint-enable */
```

### CSS

```css
/* stylelint-disable-next-line rule-name */
.example {
}

/* stylelint-disable */
/* Code block to ignore */
/* stylelint-enable */
```

### PHP

```php
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo $trusted_content;

// phpcs:disable
// Code block to ignore
// phpcs:enable
```

## Troubleshooting

### Command not found errors

Make sure you've run both `npm install` and `composer install`.

### PHP CodeSniffer not detecting WordPress standards

Run `composer install` again. The Composer installer should automatically set up the WordPress standards.

### Files in vendor/ or node_modules/ being linted

These directories are excluded in the configuration files. If they're still being checked, ensure your linter is reading the config files correctly.
