#!/usr/bin/env node
// Copies vendor assets from node_modules into assets/vendor
// Safe to run repeatedly; creates directories if missing.

import { promises as fs } from 'fs';
import path from 'path';

const root = process.cwd();

const targets = [
  // Bootstrap
  {
    from: 'node_modules/bootstrap/dist/css/bootstrap.min.css',
    to: 'assets/vendor/bootstrap/css/bootstrap.min.css',
  },
  {
    from: 'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    to: 'assets/vendor/bootstrap/js/bootstrap.bundle.min.js',
  },

  // Font Awesome
  {
    from: 'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
    to: 'assets/vendor/fontawesome/css/all.min.css',
  },
  // Copy webfonts directory (all files)
  {
    fromDir: 'node_modules/@fortawesome/fontawesome-free/webfonts',
    toDir: 'assets/vendor/fontawesome/webfonts',
  },

  // (Glide.js removed — not used)
];

async function ensureDir(dir) {
  await fs.mkdir(dir, { recursive: true });
}

async function copyFile(srcRel, destRel) {
  const src = path.join(root, srcRel);
  const dest = path.join(root, destRel);
  try {
    await ensureDir(path.dirname(dest));
    await fs.copyFile(src, dest);
    console.log(`✓ ${srcRel} -> ${destRel}`);
  } catch (err) {
    console.warn(`! Skipped ${srcRel}: ${err.code || err.message}`);
  }
}

async function copyDir(srcRel, destRel) {
  const src = path.join(root, srcRel);
  const dest = path.join(root, destRel);
  try {
    await ensureDir(dest);
    const entries = await fs.readdir(src, { withFileTypes: true });
    for (const entry of entries) {
      const from = path.join(srcRel, entry.name);
      const to = path.join(destRel, entry.name);
      if (entry.isDirectory()) {
        await copyDir(from, to);
      } else if (entry.isFile()) {
        await copyFile(from, to);
      }
    }
    console.log(`✓ Copied directory ${srcRel} -> ${destRel}`);
  } catch (err) {
    console.warn(`! Skipped dir ${srcRel}: ${err.code || err.message}`);
  }
}

async function run() {
  for (const t of targets) {
    if (t.from && t.to) {
      // Optional files shouldn't fail the build
      if (t.optional) {
        try {
          await copyFile(t.from, t.to);
        } catch {
          // ignore
        }
      } else {
        await copyFile(t.from, t.to);
      }
    } else if (t.fromDir && t.toDir) {
      await copyDir(t.fromDir, t.toDir);
    }
  }
}

run();
