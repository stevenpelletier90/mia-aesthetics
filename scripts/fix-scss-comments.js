#!/usr/bin/env node

/**
 * Fix SCSS comment syntax issues in converted files
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Find all SCSS template files
const scssDir = path.join(projectRoot, 'assets', 'scss', 'templates');

function fixScssComments(content) {
  // Remove duplicate headers
  content = content.replace(/^\/\/ =+\s*\n\/\/ .+ Styles\s*\n\/\/ =+\s*\n\n\/\/ =+[\s\S]*?\n   .+\s*\n   =+\s*\n\n/m, '');
  
  // Fix CSS comments to SCSS comments
  content = content.replace(/\/\* (.+)\n   =+\s*\*\//g, '// $1\n// ' + '='.repeat(50));
  
  // Remove any remaining CSS block comments in headers
  content = content.replace(/\/\*[\s\S]*?\*\//g, '');
  
  return content;
}

function processDirectory(dir) {
  if (!fs.existsSync(dir)) return;
  
  fs.readdirSync(dir, { withFileTypes: true }).forEach(dirent => {
    const fullPath = path.join(dir, dirent.name);
    
    if (dirent.isDirectory()) {
      processDirectory(fullPath);
    } else if (dirent.isFile() && dirent.name.endsWith('.scss')) {
      try {
        const content = fs.readFileSync(fullPath, 'utf8');
        const fixed = fixScssComments(content);
        
        if (fixed !== content) {
          fs.writeFileSync(fullPath, fixed);
          console.log(`✅ Fixed: ${path.relative(projectRoot, fullPath)}`);
        }
      } catch (error) {
        console.error(`❌ Error fixing ${fullPath}:`, error.message);
      }
    }
  });
}

console.log('🔧 Fixing SCSS comment syntax...\n');
processDirectory(scssDir);
console.log('\n✨ SCSS comment fixes complete!');