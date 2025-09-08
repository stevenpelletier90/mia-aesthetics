#!/usr/bin/env node

import { glob } from 'glob';
import { minify } from 'terser';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

async function buildJS() {
  try {
    // Find all JS files (excluding vendor and already minified files)
    const jsFiles = await glob('assets/js/**/*.js', {
      cwd: projectRoot,
      ignore: [
        'assets/js/**/*.min.js',
        'assets/vendor/**/*'
      ]
    });

    console.log(`📜 Building ${jsFiles.length} JS files...`);

    for (const file of jsFiles) {
      const inputPath = path.join(projectRoot, file);
      const outputPath = path.join(
        projectRoot, 
        file.replace('.js', '.min.js')
      );
      const mapPath = outputPath + '.map';

      console.log(`   Processing: ${file}`);

      const code = await fs.readFile(inputPath, 'utf8');
      
      const result = await minify(code, {
        sourceMap: {
          filename: path.basename(outputPath),
          url: path.basename(mapPath)
        },
        compress: {
          drop_console: false, // Keep console logs for debugging
          drop_debugger: false
        },
        mangle: false, // Don't mangle names for better debugging
        format: {
          comments: /^!/
        }
      });

      // Write minified file
      await fs.writeFile(outputPath, result.code);
      
      // Write source map
      if (result.map) {
        await fs.writeFile(mapPath, result.map);
      }
    }

    console.log('✅ JS build complete!');
  } catch (error) {
    console.error('❌ JS build failed:', error.message);
    process.exit(1);
  }
}

buildJS();