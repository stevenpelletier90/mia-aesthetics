#!/usr/bin/env node

import { glob } from 'glob';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Theme name and output directory
const themeName = 'mia-aesthetics';
const bundleDir = path.join(projectRoot, 'theme-bundle');
const themeDir = path.join(bundleDir, themeName);

// CLI flags
const CLEAN = process.argv.includes('--clean');

// Files and patterns to include in bundle
const includePatterns = [
  // Core WordPress theme files
  '*.php',
  'style.css',
  'theme.json',
  
  // Template and component files
  'components/**/*',
  'inc/**/*',
  
  // Assets (source files only)
  'assets/css/**/*.css',
  'assets/js/**/*.js',
  'assets/fonts/**/*',
  'assets/data/**/*',
  'assets/vendor/**/*'
];

// Files and patterns to exclude
const excludePatterns = [
  // Development files
  'node_modules/**/*',
  'vendor/**/*',
  'scripts/**/*',
  'theme-bundle/**/*',
  
  // Config files
  'package*.json',
  'composer.*',
  '*.config.js',
  'phpcs.xml',
  'phpstan.neon',
  '.php-cs-fixer.php',
  'purgecss.config.js',
  
  // IDE and docs
  '.git/**/*',
  '.claude/**/*',
  'CLAUDE.md',
  '*.md',
  '.DS_Store',
  '.gitignore',
  '.gitattributes',
  '.prettierrc.json',
  '.prettierignore',
  '.stylelintrc.json',
  '.stylelintignore',
  'eslint.config.js',
  
  // Purged CSS directory
  'assets/css-purged/**/*',
  
  // Always exclude minified files and source maps (source files only)
  '**/*.min.css',
  '**/*.min.js',
  '**/*.map'
];

async function ensureDirectory(dirPath) {
  await fs.mkdir(dirPath, { recursive: true });
}

async function copyFile(source, dest) {
  await ensureDirectory(path.dirname(dest));
  await fs.copyFile(source, dest);
}

async function cleanBundle() {
  if (CLEAN) {
    try {
      await fs.rm(bundleDir, { recursive: true, force: true });
      console.log(`🧹 Cleaned ${bundleDir}`);
    } catch {
      // Directory might not exist, that's fine
    }
  }
}

async function bundleTheme() {
  try {
    console.log(`📦 Creating WordPress theme bundle: ${themeName}`);
    console.log(`🔧 Source files only (no minified files or source maps)`);
    
    await cleanBundle();
    await ensureDirectory(themeDir);

    // Find all files to include
    const allFiles = [];
    const filesByCategory = {
      php: [],
      css: [],
      js: [],
      vendor: [],
      other: []
    };
    
    for (const pattern of includePatterns) {
      const files = await glob(pattern, {
        cwd: projectRoot,
        ignore: excludePatterns,
        dot: false
      });
      allFiles.push(...files);
      
      // Categorize files for better reporting
      files.forEach(file => {
        const ext = path.extname(file).toLowerCase();
        if (ext === '.php') {
          filesByCategory.php.push(file);
        } else if (ext === '.css') {
          filesByCategory.css.push(file);
        } else if (ext === '.js') {
          filesByCategory.js.push(file);
        } else if (file.includes('vendor/')) {
          filesByCategory.vendor.push(file);
        } else {
          filesByCategory.other.push(file);
        }
      });
    }

    // Remove duplicates and sort
    const uniqueFiles = [...new Set(allFiles)].sort();
    
    console.log(`📋 Found ${uniqueFiles.length} files to bundle:`);
    console.log(`   📄 PHP files: ${filesByCategory.php.length}`);
    console.log(`   🎨 CSS files: ${filesByCategory.css.length}`);
    console.log(`   ⚡ JS files: ${filesByCategory.js.length}`);
    console.log(`   📦 Vendor files: ${filesByCategory.vendor.length}`);
    console.log(`   📁 Other files: ${filesByCategory.other.length}`);

    // Copy files to bundle directory
    let copiedCount = 0;
    let totalSize = 0;
    const errors = [];
    
    for (const file of uniqueFiles) {
      const sourcePath = path.join(projectRoot, file);
      const destPath = path.join(themeDir, file);

      try {
        const stat = await fs.stat(sourcePath);
        if (stat.isFile()) {
          await copyFile(sourcePath, destPath);
          copiedCount++;
          totalSize += stat.size;
          
          if (copiedCount % 50 === 0) {
            console.log(`   📄 Copied ${copiedCount} files...`);
          }
        }
      } catch (error) {
        const errorMsg = `Could not copy ${file}: ${error.message}`;
        errors.push(errorMsg);
        console.warn(`   ⚠️  Warning: ${errorMsg}`);
      }
    }

    // Create a simple readme for deployment
    const readmeContent = `# ${themeName} WordPress Theme Bundle

This bundle contains the complete WordPress theme ready for deployment.

## Deployment Instructions

1. ZIP this entire folder
2. Upload via WordPress Admin → Appearance → Themes → Add New → Upload Theme
3. Or upload via SFTP to /wp-content/themes/

## Bundle Information

- Created: ${new Date().toISOString()}
- Files: ${copiedCount}
- Type: Source files only (no minified files)

---
Generated by Mia Aesthetics build system
`;

    await fs.writeFile(
      path.join(themeDir, 'BUNDLE-README.md'), 
      readmeContent
    );

    // Helper function to format bytes
    const formatBytes = (bytes) => {
      if (bytes === 0) return '0 B';
      const k = 1024;
      const sizes = ['B', 'KB', 'MB', 'GB'];
      const i = Math.floor(Math.log(bytes) / Math.log(k));
      return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    };

    console.log(`✅ Theme bundle created successfully!`);
    console.log(`📁 Location: ${themeDir}`);
    console.log(`📊 Files bundled: ${copiedCount}`);
    console.log(`💾 Total size: ${formatBytes(totalSize)}`);
    
    if (errors.length > 0) {
      console.log(`⚠️  Warnings: ${errors.length} files could not be copied`);
    }
    
    console.log(`📝 Source files only (no minified files or source maps)`);
    console.log('');
    console.log('🚀 Ready for deployment:');
    console.log(`   1. cd theme-bundle && zip -r ${themeName}.zip ${themeName}/`);
    console.log(`   2. Upload ${themeName}.zip to WordPress`);
    
  } catch (error) {
    console.error('❌ Bundle failed:', error.message);
    process.exit(1);
  }
}

bundleTheme();