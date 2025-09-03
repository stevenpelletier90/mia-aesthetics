#!/usr/bin/env node

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import postcss from "postcss";
import autoprefixer from "autoprefixer";
import cssnano from "cssnano";
import { minify } from "terser";
import { Buffer } from "node:buffer";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, "..");
const assetsDir = path.join(projectRoot, "assets");

// Directories to exclude from minification (vendor assets)
const excludeDirs = ["bootstrap", "fontawesome", "glide"];

// Statistics tracking
const stats = {
  css: { processed: 0, skipped: 0, totalSaved: 0 },
  js: { processed: 0, skipped: 0, totalSaved: 0 }
};

/**
 * Check if a file path should be excluded
 */
function shouldExclude(filePath) {
  // Check if file is in an excluded directory
  const relativePath = path.relative(assetsDir, filePath);
  for (const dir of excludeDirs) {
    if (relativePath.startsWith(dir + path.sep)) {
      return true;
    }
  }
  
  // Skip already minified files
  if (filePath.includes(".min.")) {
    return true;
  }
  
  return false;
}

/**
 * Get all files recursively
 */
function getAllFiles(dirPath, extension, arrayOfFiles = []) {
  const files = fs.readdirSync(dirPath);

  files.forEach((file) => {
    const fullPath = path.join(dirPath, file);
    
    if (fs.statSync(fullPath).isDirectory()) {
      getAllFiles(fullPath, extension, arrayOfFiles);
    } else if (file.endsWith(extension) && !shouldExclude(fullPath)) {
      arrayOfFiles.push(fullPath);
    }
  });

  return arrayOfFiles;
}

/**
 * Format bytes to human readable
 */
function formatBytes(bytes) {
  return (bytes / 1024).toFixed(2) + " KB";
}

/**
 * Minify CSS files using PostCSS and cssnano with source maps
 */
async function minifyCSS() {
  console.log("\n🎨 Minifying CSS files...\n");
  
  const cssFiles = getAllFiles(path.join(assetsDir, "css"), ".css");
  
  const processor = postcss([
    autoprefixer({
      grid: true
    }),
    cssnano({ preset: "default" })
  ]);
  
  for (const file of cssFiles) {
    const relativePath = path.relative(projectRoot, file);
    const outputPath = file.replace(/\.css$/, ".min.css");
    const mapPath = outputPath + ".map";
    
    try {
      const css = fs.readFileSync(file, "utf8");
      const originalSize = Buffer.byteLength(css);
      
      
      const result = await processor.process(css, { 
        from: file, 
        to: outputPath,
        map: { 
          inline: false,
          annotation: path.basename(mapPath)
        }
      });
      
      // Write minified CSS and source map
      fs.writeFileSync(outputPath, result.css);
      if (result.map) {
        fs.writeFileSync(mapPath, result.map.toString());
      }
      
      const minifiedSize = Buffer.byteLength(result.css);
      const saved = originalSize - minifiedSize;
      const percent = ((saved / originalSize) * 100).toFixed(1);
      
      console.log(`   ✓ ${relativePath}`);
      console.log(`     ${formatBytes(originalSize)} → ${formatBytes(minifiedSize)} (${percent}% reduction)`);
      console.log(`     🔧 Autoprefixer + minification applied`);
      
      stats.css.processed++;
      stats.css.totalSaved += saved;
      
    } catch (error) {
      console.error(`   ✗ Failed to minify ${relativePath}:`, error.message);
    }
  }
  
  // Count skipped files
  const allCssFiles = getAllFiles(path.join(assetsDir, "css"), ".css", []);
  stats.css.skipped = allCssFiles.length - cssFiles.length;
}

/**
 * Minify JavaScript files using Terser with source maps
 */
async function minifyJS() {
  console.log("\n📦 Minifying JavaScript files...\n");
  
  const jsFiles = getAllFiles(path.join(assetsDir, "js"), ".js");
  
  for (const file of jsFiles) {
    const relativePath = path.relative(projectRoot, file);
    const outputPath = file.replace(/\.js$/, ".min.js");
    const mapPath = outputPath + ".map";
    
    try {
      const js = fs.readFileSync(file, "utf8");
      const result = await minify(js, {
        sourceMap: {
          filename: path.basename(outputPath),
          url: path.basename(mapPath)
        },
        compress: {
          drop_console: false,  // Keep console.log for debugging
          drop_debugger: false  // Keep debugger statements
        },
        mangle: {
          keep_fnames: true  // Preserve function names for better stack traces
        },
        format: {
          comments: false  // Remove comments but keep licenses if any
        }
      });
      
      if (result.error) {
        throw result.error;
      }
      
      // Write minified JS and source map
      fs.writeFileSync(outputPath, result.code);
      if (result.map) {
        fs.writeFileSync(mapPath, result.map);
      }
      
      const originalSize = Buffer.byteLength(js);
      const minifiedSize = Buffer.byteLength(result.code);
      const saved = originalSize - minifiedSize;
      const percent = ((saved / originalSize) * 100).toFixed(1);
      
      console.log(`   ✓ ${relativePath}`);
      console.log(`     ${formatBytes(originalSize)} → ${formatBytes(minifiedSize)} (${percent}% reduction)`);
      
      stats.js.processed++;
      stats.js.totalSaved += saved;
      
    } catch (error) {
      console.error(`   ✗ Failed to minify ${relativePath}:`, error.message);
    }
  }
  
  // Count skipped files
  const allJsFiles = getAllFiles(path.join(assetsDir, "js"), ".js", []);
  stats.js.skipped = allJsFiles.length - jsFiles.length;
}


/**
 * Main execution
 */
async function main() {
  const mode = process.argv[2] || "all";
  
  console.log("🚀 Starting asset minification...");
  console.log(`   Mode: ${mode}`);
  console.log(`   Excluding: ${excludeDirs.join(", ")}`);
  console.log(`   PurgeCSS: Disabled (keeping Stylelint)`);
  
  if (mode === "css" || mode === "all") {
    await minifyCSS();
  }
  
  if (mode === "js" || mode === "all") {
    await minifyJS();
  }
  
  // Summary
  console.log("\n📊 Minification Summary:");
  console.log("   CSS Files:");
  console.log(`     • Processed: ${stats.css.processed} files`);
  console.log(`     • Skipped: ${stats.css.skipped} files (vendor/already minified)`);
  console.log(`     • Total saved: ${formatBytes(stats.css.totalSaved)}`);
  
  if (stats.js.processed > 0) {
    console.log("   JS Files:");
    console.log(`     • Processed: ${stats.js.processed} files`);
    console.log(`     • Skipped: ${stats.js.skipped} files (vendor/already minified)`);
    console.log(`     • Total saved: ${formatBytes(stats.js.totalSaved)}`);
  }
  
  console.log("\n✅ Minification complete! (PurgeCSS disabled)");
}

// Run the script
main().catch(console.error);
