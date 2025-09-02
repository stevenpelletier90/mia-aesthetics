#!/usr/bin/env node

/**
 * Convert CSS files to SCSS format and update build scripts
 * 
 * This script converts all template CSS files to SCSS while maintaining
 * the modular loading system used by enqueue.php
 */

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, '..');

// Directories to process
const cssBasePath = path.join(projectRoot, 'assets', 'css');
const scssBasePath = path.join(projectRoot, 'assets', 'scss');

// Template directories to convert
const templateDirs = [
  'templates/pages',
  'templates/archives', 
  'templates/singles',
  'templates/misc',
  'utilities'
];

/**
 * Convert CSS content to SCSS format
 */
function convertCssToScss(cssContent, filename) {
  let scssContent = cssContent;
  
  // Add SCSS header comment
  const scssHeader = `// =============================================================================
// ${filename.replace('.scss', '')} Styles  
// =============================================================================

`;
  
  // Convert CSS comments to SCSS comments where appropriate
  scssContent = scssContent.replace(/\/\* ={10,}[\s\S]*?\*\//g, (match) => {
    return match.replace(/\/\*/g, '//').replace(/\*\//g, '');
  });
  
  return scssHeader + scssContent;
}

/**
 * Create SCSS file from CSS file
 */
function createScssFile(cssFilePath, scssFilePath) {
  try {
    if (!fs.existsSync(cssFilePath)) {
      console.log(`⚠️  CSS file not found: ${cssFilePath}`);
      return false;
    }
    
    const cssContent = fs.readFileSync(cssFilePath, 'utf8');
    const filename = path.basename(scssFilePath);
    const scssContent = convertCssToScss(cssContent, filename);
    
    // Ensure directory exists
    const scssDir = path.dirname(scssFilePath);
    if (!fs.existsSync(scssDir)) {
      fs.mkdirSync(scssDir, { recursive: true });
    }
    
    fs.writeFileSync(scssFilePath, scssContent);
    console.log(`✅ Converted: ${path.relative(projectRoot, scssFilePath)}`);
    return true;
  } catch (error) {
    console.error(`❌ Error converting ${cssFilePath}:`, error.message);
    return false;
  }
}

/**
 * Generate SCSS build command for all template files
 */
function generateScssBuildCommands() {
  const commands = [];
  
  // Find all SCSS files in templates
  templateDirs.forEach(dir => {
    const scssDir = path.join(scssBasePath, dir);
    if (fs.existsSync(scssDir)) {
      const files = fs.readdirSync(scssDir, { recursive: true })
        .filter(file => file.endsWith('.scss'))
        .map(file => {
          const scssPath = `assets/scss/${dir}/${file}`;
          const cssPath = `theme-bundle/mia-aesthetics/assets/css/${dir}/${file.replace('.scss', '.css')}`;
          return `${scssPath}:${cssPath}`;
        });
      commands.push(...files);
    }
  });
  
  return commands.join(' ');
}

/**
 * Main conversion process
 */
function main() {
  console.log('🚀 Converting CSS files to SCSS...\n');
  
  let convertedCount = 0;
  
  // Process each template directory
  templateDirs.forEach(dir => {
    console.log(`📁 Processing ${dir}...`);
    
    const cssDir = path.join(cssBasePath, dir);
    const scssDir = path.join(scssBasePath, dir);
    
    if (!fs.existsSync(cssDir)) {
      console.log(`⚠️  Directory not found: ${cssDir}`);
      return;
    }
    
    // Get all CSS files (excluding minified)
    const cssFiles = fs.readdirSync(cssDir, { recursive: true })
      .filter(file => file.endsWith('.css') && !file.includes('.min.'));
    
    cssFiles.forEach(file => {
      const cssFilePath = path.join(cssDir, file);
      const scssFileName = file.replace('.css', '.scss');
      const scssFilePath = path.join(scssDir, scssFileName);
      
      if (createScssFile(cssFilePath, scssFilePath)) {
        convertedCount++;
      }
    });
  });
  
  console.log(`\n✨ Conversion complete! Converted ${convertedCount} files.`);
  
  // Generate build command suggestion
  const buildCommand = generateScssBuildCommands();
  if (buildCommand) {
    console.log('\n📝 Add this to your package.json sass:build:templates script:');
    console.log(`"sass:build:templates": "sass ${buildCommand} --style=expanded --source-map"`);
  }
}

// Run the conversion
main();