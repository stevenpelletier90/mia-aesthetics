#!/usr/bin/env node

import { glob } from 'glob';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

async function buildJS() {
  try {
    // Find all JS files (excluding vendor files)
    const jsFiles = await glob('assets/js/**/*.js', {
      cwd: projectRoot,
      ignore: [
        'assets/vendor/**/*'
      ]
    });

    console.log(`📜 Validating ${jsFiles.length} JS files...`);

    for (const file of jsFiles) {
      const inputPath = path.join(projectRoot, file);

      console.log(`   Processing: ${file}`);

      // Just validate the file exists and is readable
      try {
        await fs.access(inputPath, fs.constants.R_OK);
      } catch (error) {
        throw new Error(`Cannot read ${file}: ${error.message}`);
      }
    }

    console.log('✅ JS validation complete!');
  } catch (error) {
    console.error('❌ JS validation failed:', error.message);
    process.exit(1);
  }
}

buildJS();