#!/usr/bin/env node
/*
 * Adds `@use` for abstracts barrel file to SCSS partials and removes legacy @import of abstracts from main.scss.
 * - Inserts a module-scoped import: `@use "<relative>/abstracts/index" as *;`
 * - Skips vendors and abstracts themselves.
 * - Computes correct relative path per file.
 */

import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const projectRoot = path.resolve(__dirname, "..");
const scssRoot = path.join(projectRoot, "assets", "scss");

function listScssFiles(dir) {
  const out = [];
  for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
    const full = path.join(dir, entry.name);
    if (entry.isDirectory()) out.push(...listScssFiles(full));
    else if (entry.isFile() && entry.name.endsWith(".scss")) out.push(full);
  }
  return out;
}

function toPosix(p) {
  return p.split(path.sep).join("/");
}

function insertUseLine(file) {
  const content = fs.readFileSync(file, "utf8");
  // Skip if already has a @use for abstracts
  if (/@use\s+["'].*abstracts\/index["']\s+as\s+\*/.test(content)) return false;

  // Compute relative import path to abstracts/index from this file's directory
  const fromDir = path.dirname(file);
  const rel = path.relative(fromDir, path.join(scssRoot, "abstracts", "index"));
  // Ensure posix form & prefix ./ if needed
  let relPosix = toPosix(rel);
  if (!relPosix.startsWith(".") && !relPosix.startsWith("/")) relPosix = "./" + relPosix;

  const useLine = `@use "${relPosix}" as *;\n`;

  // Insert after initial header comments (// or /* ... */) if present
  let insertAtTop = 0;
  if (content.startsWith("/*")) {
    const end = content.indexOf("*/");
    if (end !== -1) insertAtTop = end + 2; // after block comment
  } else {
    const lines = content.split(/\r?\n/);
    let i = 0;
    while (i < lines.length && lines[i].trim().startsWith("//")) i++;
    insertAtTop = lines.slice(0, i).join("\n").length;
    if (i > 0) insertAtTop += 1; // account for newline
  }

  const updated = content.slice(0, insertAtTop) + useLine + content.slice(insertAtTop);
  fs.writeFileSync(file, updated, "utf8");
  return true;
}

function migrate() {
  // 1) Remove legacy abstracts @import from main.scss
  const mainScss = path.join(scssRoot, "main.scss");
  let main = fs.readFileSync(mainScss, "utf8");
  const before = main;
  main = main
    .replace(/\n@import\s+"abstracts\/variables";?/g, "")
    .replace(/\n@import\s+"abstracts\/functions";?/g, "")
    .replace(/\n@import\s+"abstracts\/mixins";?/g, "");
  if (main !== before) fs.writeFileSync(mainScss, main, "utf8");

  // 2) Add @use to all partials except vendors and abstracts and main.scss
  const files = listScssFiles(scssRoot).filter((f) => {
    const rel = toPosix(path.relative(scssRoot, f));
    if (rel === "main.scss") return false;
    if (rel.startsWith("vendors/")) return false;
    if (rel.startsWith("abstracts/")) return false;
    return true;
  });

  let changed = 0;
  for (const file of files) {
    // Only add @use to files that likely reference tokens or mixins
    const content = fs.readFileSync(file, "utf8");
    const needs = /\$mia-|\$font-family|\$headings-font-family|@include\s+|\$btn-/.test(content);
    if (!needs) continue;
    if (insertUseLine(file)) changed++;
  }

  console.log(`SCSS migrate: updated ${changed} file(s).`);
}

migrate();
