#!/usr/bin/env node

import { glob } from 'glob';
import { spawn } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

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

    console.log(`🎨 Building ${cssFiles.length} CSS files...`);

    for (const file of cssFiles) {
      const inputPath = path.join(projectRoot, file);
      const outputPath = path.join(
        projectRoot, 
        file.replace('.css', '.min.css')
      );

      console.log(`   Processing: ${file}`);

      await new Promise((resolve, reject) => {
        const postcss = spawn('npx', [
          'postcss',
          inputPath,
          '-o', outputPath,
          '--config', path.join(projectRoot, 'postcss.config.js')
        ], {
          cwd: projectRoot,
          stdio: ['pipe', 'pipe', 'inherit']
        });

        postcss.on('close', (code) => {
          if (code === 0) {
            resolve();
          } else {
            reject(new Error(`PostCSS failed with code ${code} for ${file}`));
          }
        });
      });
    }

    console.log('✅ CSS build complete!');
  } catch (error) {
    console.error('❌ CSS build failed:', error.message);
    process.exit(1);
  }
}

buildCSS();