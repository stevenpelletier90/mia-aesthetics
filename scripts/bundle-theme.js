#!/usr/bin/env node

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Theme name and output directory
const themeName = 'mia-aesthetics';
const outputDir = path.join(projectRoot, 'theme-bundle');

// Files and directories to include in the WordPress theme bundle
const includePatterns = [
  // Core WordPress theme files
  '*.php',
  'style.css',
  
  // Template and component files
  'components/**/*',
  'inc/**/*',
  
  // Assets (CSS, JS, fonts, data)
  'assets/css/**/*',
  'assets/js/**/*', 
  'assets/fonts/**/*',
  'assets/data/**/*',
  
  // Third-party assets
  'assets/bootstrap/**/*',
  'assets/fontawesome/**/*',
  
];

// Files and directories to exclude
const excludePatterns = [
  'node_modules/**/*',
  'vendor/**/*',
  'scripts/**/*',
  'package.json',
  'package-lock.json',
  'composer.json',
  'composer.lock',
  'phpcs.xml',
  'phpstan.neon',
  'postcss.config.js',
  'eslint.config.js',
  '.git/**/*',
  'theme-bundle/**/*',
  'style-guide.html',
  'mcpreadme.md',
  'CLAUDE.md'
];

function isExcluded(filePath) {
  return excludePatterns.some(pattern => {
    const regexPattern = pattern.replace(/\*\*/g, '.*').replace(/\*/g, '[^/]*');
    const regex = new RegExp(`^${regexPattern}$`);
    return regex.test(filePath);
  });
}

function shouldInclude(filePath) {
  if (isExcluded(filePath)) return false;
  
  return includePatterns.some(pattern => {
    // Handle different pattern types
    if (pattern.includes('**')) {
      // For recursive patterns like assets/css/**/*
      const basePath = pattern.split('/**')[0];
      return filePath.startsWith(basePath + '/');
    } else if (pattern.includes('*')) {
      // For single-level patterns like *.php
      const regexPattern = pattern.replace(/\*/g, '[^/]*');
      const regex = new RegExp(`^${regexPattern}$`);
      return regex.test(filePath);
    } else {
      // For exact matches
      return filePath === pattern;
    }
  });
}

function copyFileSync(source, destination) {
  const destDir = path.dirname(destination);
  if (!fs.existsSync(destDir)) {
    fs.mkdirSync(destDir, { recursive: true });
  }
  fs.copyFileSync(source, destination);
}

function getAllFiles(dirPath, arrayOfFiles = [], relativePath = '') {
  const files = fs.readdirSync(dirPath);

  files.forEach(file => {
    const fullPath = path.join(dirPath, file);
    const relativeFilePath = path.join(relativePath, file).replace(/\\/g, '/');
    
    if (fs.statSync(fullPath).isDirectory()) {
      getAllFiles(fullPath, arrayOfFiles, relativeFilePath);
    } else {
      if (shouldInclude(relativeFilePath)) {
        arrayOfFiles.push({
          source: fullPath,
          relative: relativeFilePath,
          destination: path.join(outputDir, themeName, relativeFilePath)
        });
      }
    }
  });

  return arrayOfFiles;
}

function bundleTheme() {
  console.log('🚀 Starting WordPress theme bundle process...');
  
  // Clean output directory
  if (fs.existsSync(outputDir)) {
    fs.rmSync(outputDir, { recursive: true, force: true });
  }
  
  // Create output directory
  fs.mkdirSync(outputDir, { recursive: true });
  
  // Get all files to include
  const filesToCopy = getAllFiles(projectRoot);
  
  console.log(`📦 Found ${filesToCopy.length} files to bundle:`);
  
  // Copy files
  let copiedCount = 0;
  filesToCopy.forEach(file => {
    try {
      copyFileSync(file.source, file.destination);
      copiedCount++;
      console.log(`   ✓ ${file.relative}`);
    } catch (error) {
      console.error(`   ✗ Failed to copy ${file.relative}:`, error.message);
    }
  });
  
  // Create theme zip if needed
  console.log(`\n✅ Successfully bundled ${copiedCount} files!`);
  console.log(`📁 Theme bundle created at: ${path.join(outputDir, themeName)}`);
  console.log('\n🎯 Next steps:');
  console.log(`   1. Navigate to: ${outputDir}`);
  console.log(`   2. Compress the '${themeName}' folder into a ZIP file`);
  console.log('   3. Upload the ZIP to WordPress Admin > Appearance > Themes > Add New');
  
  // Show bundle contents summary
  const cssFiles = filesToCopy.filter(f => f.relative.endsWith('.css')).length;
  const jsFiles = filesToCopy.filter(f => f.relative.endsWith('.js')).length;
  const phpFiles = filesToCopy.filter(f => f.relative.endsWith('.php')).length;
  
  console.log('\n📊 Bundle Contents:');
  console.log(`   • ${phpFiles} PHP template files`);
  console.log(`   • ${cssFiles} CSS files (including all template-specific styles)`);
  console.log(`   • ${jsFiles} JavaScript files`);
  console.log(`   • Font files, images, and other assets`);
  console.log(`   • WordPress theme documentation`);
}

// Run the bundle process
bundleTheme();