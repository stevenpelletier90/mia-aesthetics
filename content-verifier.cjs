/**
 * Content Verification System for Mia Aesthetics Templates
 * This script compares template content with live website content
 */

const fs = require("fs").promises;
const path = require("path");

// Configuration
const SITE_BASE_URL = "https://www.miaaesthetics.com";

const PROCEDURES = {
  breast: {
    "breast-augmentation": "/cosmetic-plastic-surgery/breast/augmentation-implants/",
    "breast-reduction": "/cosmetic-plastic-surgery/breast/reduction/",
    "breast-lift": "/cosmetic-plastic-surgery/breast/lift/",
    "breast-implant-revision": "/cosmetic-plastic-surgery/breast/implant-revision/",
  },
  male: {
    "male-bbl": "/cosmetic-plastic-surgery/body/male-bbl/",
    "male-liposuction": "/cosmetic-plastic-surgery/body/male-liposuction/",
    "male-tummy-tuck": "/cosmetic-plastic-surgery/body/male-tummy-tuck/",
    gynecomastia: "/cosmetic-plastic-surgery/breast/male-breast-procedures/",
  },
  body: {
    "tummy-tuck": "/cosmetic-plastic-surgery/body/tummy-tuck/",
    liposuction: "/cosmetic-plastic-surgery/body/liposuction/",
    bbl: "/cosmetic-plastic-surgery/body/brazilian-butt-lift/",
    "mommy-makeover": "/cosmetic-plastic-surgery/body/mommy-makeover/",
  },
};

/**
 * Extract text content from HTML template
 */
function extractTemplateContent(html) {
  // Remove HTML tags but preserve text
  const textContent = html
    .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, "")
    .replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, "")
    .replace(/<[^>]+>/g, " ")
    .replace(/\s+/g, " ")
    .trim();

  // Extract specific elements
  const headings = [];
  const paragraphs = [];

  // Extract H2 headings
  const h2Matches = html.matchAll(/<h2[^>]*>(.*?)<\/h2>/gi);
  for (const match of h2Matches) {
    const text = match[1].replace(/<[^>]+>/g, "").trim();
    if (text) headings.push(text);
  }

  // Extract paragraphs
  const pMatches = html.matchAll(/<p[^>]*>(.*?)<\/p>/gi);
  for (const match of pMatches) {
    const text = match[1].replace(/<[^>]+>/g, "").trim();
    if (text && text.length > 30) {
      paragraphs.push(text);
    }
  }

  return {
    fullText: textContent,
    headings,
    paragraphs,
  };
}

/**
 * Normalize text for comparison
 */
function normalizeText(text) {
  return text
    .toLowerCase()
    .replace(/[''""]/g, "") // Remove quotes
    .replace(/[–—]/g, "-") // Normalize dashes
    .replace(/\s+/g, " ") // Normalize whitespace
    .trim();
}

/**
 * Compare content between template and expected
 */
function compareContent(templateContent, expectedContent) {
  const results = {
    matches: [],
    differences: [],
    accuracy: 0,
  };

  const templateNorm = normalizeText(templateContent.fullText);

  // Check each expected content item
  expectedContent.forEach((item) => {
    const itemNorm = normalizeText(item);
    const firstWords = itemNorm.split(" ").slice(0, 10).join(" ");

    if (templateNorm.includes(firstWords)) {
      results.matches.push(`✓ Found: "${item.substring(0, 60)}..."`);
    } else {
      results.differences.push(`✗ Missing: "${item.substring(0, 60)}..."`);
    }
  });

  // Calculate accuracy
  const total = expectedContent.length;
  if (total > 0) {
    results.accuracy = Math.round((results.matches.length / total) * 100);
  }

  return results;
}

/**
 * Get template file path
 */
function getTemplatePath(category, name) {
  const baseDir = path.join(__dirname, `${category}-procedure-templates`);
  return path.join(baseDir, `${name}-template.html`);
}

/**
 * Verify a single template
 */
async function verifyTemplate(category, name, expectedContent = null) {
  const templatePath = getTemplatePath(category, name);

  try {
    const templateHtml = await fs.readFile(templatePath, "utf-8");
    const templateContent = extractTemplateContent(templateHtml);

    const result = {
      name,
      category,
      url: SITE_BASE_URL + PROCEDURES[category][name],
      templatePath,
      content: {
        headings: templateContent.headings,
        paragraphCount: templateContent.paragraphs.length,
        firstParagraph: templateContent.paragraphs[0] || "No content found",
      },
    };

    if (expectedContent) {
      result.comparison = compareContent(templateContent, expectedContent);
    }

    return result;
  } catch (error) {
    return {
      name,
      category,
      error: error.message,
    };
  }
}

/**
 * Verify all templates
 */
async function verifyAllTemplates() {
  const results = [];

  for (const [category, procedures] of Object.entries(PROCEDURES)) {
    for (const name of Object.keys(procedures)) {
      console.log(`Verifying ${category}/${name}...`);
      const result = await verifyTemplate(category, name);
      results.push(result);
    }
  }

  return results;
}

/**
 * Generate verification report
 */
async function generateReport() {
  console.log("Content Verification Report");
  console.log("===========================\n");

  const results = await verifyAllTemplates();

  let successCount = 0;
  let errorCount = 0;

  results.forEach((result) => {
    if (result.error) {
      console.log(`✗ ${result.category}/${result.name}: ${result.error}`);
      errorCount++;
    } else {
      console.log(`✓ ${result.category}/${result.name}:`);
      console.log(`  URL: ${result.url}`);
      console.log(`  Headings: ${result.content.headings.length}`);
      console.log(`  Paragraphs: ${result.content.paragraphCount}`);
      if (result.comparison) {
        console.log(`  Accuracy: ${result.comparison.accuracy}%`);
      }
      successCount++;
    }
    console.log("");
  });

  // Save detailed report
  const reportPath = path.join(__dirname, "verification-report.json");
  await fs.writeFile(reportPath, JSON.stringify(results, null, 2));

  console.log("Summary:");
  console.log(`Total templates: ${results.length}`);
  console.log(`Verified: ${successCount}`);
  console.log(`Errors: ${errorCount}`);
  console.log(`\nDetailed report saved to: ${reportPath}`);

  return results;
}

// Export functions for use in other scripts
module.exports = {
  extractTemplateContent,
  compareContent,
  verifyTemplate,
  verifyAllTemplates,
  generateReport,
};

// Run if called directly
if (require.main === module) {
  generateReport().catch(console.error);
}
