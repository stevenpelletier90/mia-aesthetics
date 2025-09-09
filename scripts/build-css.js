#!/usr/bin/env node

import { glob } from 'glob';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Create PostCSS processor with autoprefixer only (no minification)
const processor = postcss([
  autoprefixer({
    grid: true
  })
]);

async function processCSS(file) {
  const inputPath = path.join(projectRoot, file);
  const outputPath = path.join(projectRoot, file); // Process in-place
  
  try {
    // Read the CSS file
    const css = await fs.readFile(inputPath, 'utf8');
    
    // Process with PostCSS (autoprefixer only)
    const result = await processor.process(css, {
      from: inputPath,
      to: outputPath
    });
    
    // Write processed CSS back to same file
    await fs.writeFile(outputPath, result.css);
    
    console.log(`   ✓ ${file}`);
  } catch (error) {
    console.error(`   ✗ ${file}: ${error.message}`);
    throw error;
  }
}

async function buildCSS() {
  try {
    // Find all CSS files (excluding vendor and already minified files)
    const cssFiles = await glob('assets/css/**/*.css', {
      cwd: projectRoot,
      ignore: [
        'assets/css/**/*.min.css',
        'assets/vendor/**/*',
        'assets/css-purged/**/*'
      ]
    });

    console.log(`🎨 Processing ${cssFiles.length} CSS files with autoprefixer...`);
    
    // Process files in parallel batches of 5 for better performance
    const batchSize = 5;
    for (let i = 0; i < cssFiles.length; i += batchSize) {
      const batch = cssFiles.slice(i, i + batchSize);
      await Promise.all(batch.map(file => processCSS(file)));
    }

    console.log('✅ CSS processing complete!');
  } catch (error) {
    console.error('❌ CSS build failed:', error.message);
    process.exit(1);
  }
}

buildCSS();