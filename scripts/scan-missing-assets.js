#!/usr/bin/env node

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

console.log('🔍 Scanning for potentially missing assets...\n');

// Get all files in assets directory
function getAllFiles(dirPath, arrayOfFiles = [], basePath = '') {
  const files = fs.readdirSync(dirPath);
  
  files.forEach((file) => {
    const fullPath = path.join(dirPath, file);
    const relativePath = path.join(basePath, file).replace(/\\/g, '/');
    
    if (fs.statSync(fullPath).isDirectory()) {
      getAllFiles(fullPath, arrayOfFiles, relativePath);
    } else {
      arrayOfFiles.push({
        full: fullPath,
        relative: relativePath,
        name: file,
        ext: path.extname(file),
        size: fs.statSync(fullPath).size
      });
    }
  });
  
  return arrayOfFiles;
}

// Get all assets in source
const sourceAssets = getAllFiles(path.join(projectRoot, 'assets'));

// Get all assets in bundle
const bundlePath = path.join(projectRoot, 'theme-bundle/mia-aesthetics/assets');
let bundleAssets = [];
if (fs.existsSync(bundlePath)) {
  bundleAssets = getAllFiles(bundlePath);
}

console.log(`📊 Found ${sourceAssets.length} source assets, ${bundleAssets.length} bundle assets\n`);

// Create maps for easy lookup
const bundleMap = new Map();
bundleAssets.forEach(file => {
  bundleMap.set(file.relative, file);
});

const sourceMap = new Map();
sourceAssets.forEach(file => {
  sourceMap.set(file.relative, file);
});

// Find missing assets
const missing = [];
const sourceOnly = [];
const bundleOnly = [];

sourceAssets.forEach(file => {
  if (!bundleMap.has(file.relative)) {
    missing.push(file);
  }
});

bundleAssets.forEach(file => {
  if (!sourceMap.has(file.relative)) {
    bundleOnly.push(file);
  }
});

// Categorize missing assets
const missingCategories = {
  css: missing.filter(f => f.ext === '.css'),
  js: missing.filter(f => f.ext === '.js'),
  scss: missing.filter(f => f.ext === '.scss'),
  maps: missing.filter(f => f.ext === '.map'),
  other: missing.filter(f => !['.css', '.js', '.scss', '.map'].includes(f.ext))
};

console.log('🚨 MISSING FROM BUNDLE:\n');

if (missingCategories.css.length > 0) {
  console.log('📄 CSS Files:');
  missingCategories.css.forEach(file => {
    const isMinified = file.name.includes('.min.');
    const hasMinified = sourceMap.has(file.relative.replace('.css', '.min.css'));
    const note = isMinified ? '' : (hasMinified ? ' (has .min version)' : ' (no .min version)');
    console.log(`   ❌ ${file.relative}${note}`);
  });
  console.log('');
}

if (missingCategories.js.length > 0) {
  console.log('📜 JavaScript Files:');
  missingCategories.js.forEach(file => {
    const isMinified = file.name.includes('.min.');
    const hasMinified = sourceMap.has(file.relative.replace('.js', '.min.js'));
    const note = isMinified ? '' : (hasMinified ? ' (has .min version)' : ' (no .min version)');
    console.log(`   ❌ ${file.relative}${note}`);
  });
  console.log('');
}

if (missingCategories.maps.length > 0 && process.argv.includes('--with-maps')) {
  console.log('🗺  Source Maps:');
  missingCategories.maps.forEach(file => {
    console.log(`   ❌ ${file.relative}`);
  });
  console.log('');
}

if (missingCategories.other.length > 0) {
  console.log('📁 Other Assets:');
  missingCategories.other.forEach(file => {
    console.log(`   ❌ ${file.relative}`);
  });
  console.log('');
}

// Check for specific pattern issues
console.log('🔍 PATTERN ANALYSIS:\n');

// Check for non-minified CSS/JS that should be in bundle for debug mode
const nonMinified = missing.filter(f => 
  (f.ext === '.css' || f.ext === '.js') && 
  !f.name.includes('.min.') &&
  (f.relative.includes('/components/') || 
   f.relative.includes('/templates/') || 
   f.relative.includes('/layout/'))
);

if (nonMinified.length > 0) {
  console.log('⚠️  Non-minified files that might be needed for debug mode:');
  nonMinified.forEach(file => {
    console.log(`   ${file.relative}`);
  });
  console.log('');
}

// Check for utilities that might be missing
const utilities = missing.filter(f => f.relative.includes('/utilities/'));
if (utilities.length > 0) {
  console.log('🛠  Missing utility files:');
  utilities.forEach(file => {
    console.log(`   ${file.relative}`);
  });
  console.log('');
}

console.log('📋 SUMMARY:');
console.log(`   Missing: ${missing.length} files`);
console.log(`   CSS: ${missingCategories.css.length} files`);
console.log(`   JS: ${missingCategories.js.length} files`);
console.log(`   Non-minified needed for debug: ${nonMinified.length} files`);
console.log('');

if (missing.length === 0) {
  console.log('✅ No missing assets detected!');
} else {
  console.log('💡 Next steps:');
  console.log('   1. Review bundle script filtering logic');
  console.log('   2. Check if missing files should be included');
  console.log('   3. Run bundle script to update');
  console.log('   4. Enable WP_DEBUG and visit pages to see asset debug info');
}