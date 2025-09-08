#!/usr/bin/env node

import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Vendor assets to copy from node_modules
const vendorAssets = [
  // Bootstrap
  {
    source: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
    dest: 'assets/vendor/bootstrap/css/bootstrap.min.css'
  },
  {
    source: 'node_modules/bootstrap/dist/css/bootstrap.min.css.map',
    dest: 'assets/vendor/bootstrap/css/bootstrap.min.css.map'
  },
  {
    source: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    dest: 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js'
  },
  {
    source: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map',
    dest: 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js.map'
  },
  
  // Font Awesome
  {
    source: 'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
    dest: 'assets/vendor/fontawesome/css/all.min.css'
  },
  {
    source: 'node_modules/@fortawesome/fontawesome-free/webfonts/fa-brands-400.woff2',
    dest: 'assets/vendor/fontawesome/webfonts/fa-brands-400.woff2'
  },
  {
    source: 'node_modules/@fortawesome/fontawesome-free/webfonts/fa-regular-400.woff2',
    dest: 'assets/vendor/fontawesome/webfonts/fa-regular-400.woff2'
  },
  {
    source: 'node_modules/@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2',
    dest: 'assets/vendor/fontawesome/webfonts/fa-solid-900.woff2'
  },
  {
    source: 'node_modules/@fortawesome/fontawesome-free/webfonts/fa-v4compatibility.woff2',
    dest: 'assets/vendor/fontawesome/webfonts/fa-v4compatibility.woff2'
  },
  
  // Glide.js
  {
    source: 'node_modules/@glidejs/glide/dist/css/glide.core.min.css',
    dest: 'assets/vendor/glide/css/glide.core.min.css'
  },
  {
    source: 'node_modules/@glidejs/glide/dist/glide.min.js',
    dest: 'assets/vendor/glide/js/glide.min.js'
  }
];

async function ensureDirectory(filePath) {
  const dir = path.dirname(filePath);
  await fs.mkdir(dir, { recursive: true });
}

async function buildVendor() {
  try {
    console.log(`📦 Updating ${vendorAssets.length} vendor assets...`);

    for (const asset of vendorAssets) {
      const sourcePath = path.join(projectRoot, asset.source);
      const destPath = path.join(projectRoot, asset.dest);

      console.log(`   Copying: ${asset.source} → ${asset.dest}`);

      try {
        await ensureDirectory(destPath);
        await fs.copyFile(sourcePath, destPath);
      } catch (error) {
        console.warn(`   ⚠️  Warning: Could not copy ${asset.source} (${error.message})`);
      }
    }

    console.log('✅ Vendor build complete!');
  } catch (error) {
    console.error('❌ Vendor build failed:', error.message);
    process.exit(1);
  }
}

buildVendor();