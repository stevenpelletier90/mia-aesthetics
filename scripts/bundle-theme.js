#!/usr/bin/env node

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, "..");

// Theme name and output directory
const themeName = "mia-aesthetics";
const outputDir = path.join(projectRoot, "theme-bundle");

// CLI flags
const DEV_COPY = process.argv.includes("--dev-copy");
const WITH_MAPS = process.argv.includes("--with-maps");

// Files and directories to include in the WordPress theme bundle
const includePatterns = [
  // Core WordPress theme files
  "*.php",
  "style.css",
  "theme.json",

  // Template and component files
  "components/**/*",
  "inc/**/*",

  // Custom assets (CSS, JS, fonts, data)
  "assets/css/**/*",
  "assets/js/**/*",
  "assets/fonts/**/*",
  "assets/data/**/*",
];

// Node modules assets to copy (only what we need)
const nodeModulesAssets = [
  // Bootstrap
  {
    source: "node_modules/bootstrap/dist/css/bootstrap.min.css",
    dest: "assets/bootstrap/css/bootstrap.min.css"
  },
  {
    source: "node_modules/bootstrap/dist/css/bootstrap.min.css.map",
    dest: "assets/bootstrap/css/bootstrap.min.css.map"
  },
  {
    source: "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
    dest: "assets/bootstrap/js/bootstrap.bundle.min.js"
  },
  {
    source: "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map",
    dest: "assets/bootstrap/js/bootstrap.bundle.min.js.map"
  },
  // Font Awesome
  {
    source: "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
    dest: "assets/fontawesome/css/all.min.css"
  },
  {
    source: "node_modules/@fortawesome/fontawesome-free/webfonts/fa-brands-400.woff2",
    dest: "assets/fontawesome/webfonts/fa-brands-400.woff2"
  },
  {
    source: "node_modules/@fortawesome/fontawesome-free/webfonts/fa-regular-400.woff2",
    dest: "assets/fontawesome/webfonts/fa-regular-400.woff2"
  },
  {
    source: "node_modules/@fortawesome/fontawesome-free/webfonts/fa-solid-900.woff2",
    dest: "assets/fontawesome/webfonts/fa-solid-900.woff2"
  },
  // Glide.js
  {
    source: "node_modules/@glidejs/glide/dist/css/glide.core.min.css",
    dest: "assets/glide/css/glide.core.min.css"
  },
  {
    source: "node_modules/@glidejs/glide/dist/glide.min.js",
    dest: "assets/glide/js/glide.min.js"
  }
];

// Files and directories to exclude
const excludePatterns = [
  "node_modules/**/*",
  "vendor/**/*",
  "scripts/**/*",
  "package.json",
  "package-lock.json",
  "composer.json",
  "composer.lock",
  "phpcs.xml",
  "phpstan.neon",
  ".php-cs-fixer.php",
  "postcss.config.js",
  "eslint.config.js",
  ".git/**/*",
  "theme-bundle/**/*",
  "style-guide.html",
  "mcpreadme.md",
  "CLAUDE.md",
  "AGENTS.md",
  // Exclude SCSS source files (only compiled CSS should be bundled)
  "assets/scss/**/*",
  
  // Exclude old static asset directories (now using npm)
  "assets/bootstrap/**/*",
  "assets/fontawesome/**/*",
];

function isExcluded(filePath) {
  return excludePatterns.some((pattern) => {
    const regexPattern = pattern.replace(/\*\*/g, ".*").replace(/\*/g, "[^/]*");
    const regex = new RegExp(`^${regexPattern}$`);
    return regex.test(filePath);
  });
}

function shouldInclude(filePath) {
  if (isExcluded(filePath)) return false;

  // Prefer minified runtime assets in the bundle; include a small set of editor CSS
  // - CSS: include only *.min.css except keep theme.css and fonts.css for editor styles
  // - JS: include only *.min.js
  // - Always exclude source maps
  if (filePath.startsWith("assets/")) {
    if (!WITH_MAPS && filePath.endsWith(".map")) return false;
    if (filePath.startsWith("assets/css/")) {
      const keepEditorCss = filePath === "assets/css/theme.css" || filePath === "assets/css/fonts.css";
      const keepComponentCss = filePath.includes("/components/") || filePath.includes("/templates/") || filePath.includes("/layout/") || filePath.includes("/utilities/");
      const isMinCss = filePath.endsWith(".min.css");
      // Keep editor CSS, minified CSS, and component/template CSS for debug mode
      if (!keepEditorCss && !isMinCss && !keepComponentCss) return false;
    }
    if (filePath.startsWith("assets/js/")) {
      const keepComponentJs = filePath.includes("/components/") || filePath.includes("/templates/") || filePath.includes("/layout/") || filePath.includes("/utilities/");
      const isMinJs = filePath.endsWith(".min.js");
      // Keep minified JS and component/template/layout/utility JS for debug mode
      if (!isMinJs && !keepComponentJs) return false;
    }
  }

  return includePatterns.some((pattern) => {
    // Handle different pattern types
    if (pattern.includes("**")) {
      // For recursive patterns like assets/css/**/*
      const basePath = pattern.split("/**")[0];
      return filePath.startsWith(basePath + "/");
    } else if (pattern.includes("*")) {
      // For single-level patterns like *.php
      const regexPattern = pattern.replace(/\*/g, "[^/]*");
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

function getAllFiles(dirPath, arrayOfFiles = [], relativePath = "") {
  const files = fs.readdirSync(dirPath);

  files.forEach((file) => {
    const fullPath = path.join(dirPath, file);
    const relativeFilePath = path.join(relativePath, file).replace(/\\/g, "/");

    if (fs.statSync(fullPath).isDirectory()) {
      getAllFiles(fullPath, arrayOfFiles, relativeFilePath);
    } else {
      if (shouldInclude(relativeFilePath)) {
        arrayOfFiles.push({
          source: fullPath,
          relative: relativeFilePath,
          destination: path.join(outputDir, themeName, relativeFilePath),
        });
      }
    }
  });

  return arrayOfFiles;
}

function copyNodeAssets(targetRoot) {
  console.log("\n📦 Copying npm dependencies...");
  let nodeAssetsCount = 0;
  nodeModulesAssets.forEach((asset) => {
    const sourcePath = path.join(projectRoot, asset.source);
    const destPath = path.join(targetRoot, asset.dest);

    if (fs.existsSync(sourcePath)) {
      try {
        copyFileSync(sourcePath, destPath);
        nodeAssetsCount++;
        console.log(`   ✓ ${path.relative(targetRoot, destPath)}`);
      } catch (error) {
        console.error(`   ✗ Failed to copy ${asset.source}:`, error.message);
      }
    } else {
      console.warn(`   ⚠ Source file not found: ${asset.source}`);
    }
  });
  console.log(`   → Copied ${nodeAssetsCount} dependency files.`);
  return nodeAssetsCount;
}

function bundleTheme() {
  console.log("🚀 Starting WordPress theme bundle process...");

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
  filesToCopy.forEach((file) => {
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
  // Copy node modules assets into bundle
  const nodeAssetsCount = copyNodeAssets(path.join(outputDir, themeName));

  console.log(`📁 Theme bundle created at: ${path.join(outputDir, themeName)}`);
  console.log("\n🎯 Next steps:");
  console.log(`   1. Navigate to: ${outputDir}`);
  console.log(`   2. Compress the '${themeName}' folder into a ZIP file`);
  console.log("   3. Upload the ZIP to WordPress Admin > Appearance > Themes > Add New");

  // Show bundle contents summary
  const cssFiles = filesToCopy.filter((f) => f.relative.endsWith(".css")).length;
  const jsFiles = filesToCopy.filter((f) => f.relative.endsWith(".js")).length;
  const phpFiles = filesToCopy.filter((f) => f.relative.endsWith(".php")).length;

  console.log("\n📊 Bundle Contents:");
  console.log(`   • ${phpFiles} PHP template files`);
  console.log(`   • ${cssFiles} CSS files (including all template-specific styles)`);
  console.log(`   • ${jsFiles} JavaScript files`);
  console.log(`   • ${nodeAssetsCount} npm dependency files (Bootstrap + Font Awesome)`);
  console.log(`   • Font files, images, and other assets`);
  console.log(`   • WordPress theme documentation`);
  if (WITH_MAPS) {
    console.log("\n🗺  Source maps included (because --with-maps was passed)");
  } else {
    console.log("\n🗜  Source maps excluded (default). Add --with-maps to include them.");
  }
}

// Run the bundle process
if (DEV_COPY) {
  console.log("🛠️  Dev copy mode: Copying vendor assets into working directory (assets/)...");
  // Copy straight into the project root assets folder
  copyNodeAssets(projectRoot);
  console.log("\n✅ Dev assets ready under ./assets (bootstrap, fontawesome, glide).\n");
} else {
  bundleTheme();
}
