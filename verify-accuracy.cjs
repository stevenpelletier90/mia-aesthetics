#!/usr/bin/env node

const { chromium } = require('playwright');
const fs = require('fs').promises;
const path = require('path');

// Define procedures to verify
const PROCEDURES = {
  'breast-augmentation': {
    url: 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/augmentation-implants/',
    template: 'breast-procedure-templates/breast-augmentation-template.html'
  },
  'breast-reduction': {
    url: 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/reduction/',
    template: 'breast-procedure-templates/breast-reduction-template.html'
  },
  'breast-lift': {
    url: 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/lift/',
    template: 'breast-procedure-templates/breast-lift-template.html'
  },
  'breast-implant-revision': {
    url: 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/implant-revision/',
    template: 'breast-procedure-templates/breast-implant-revision-template.html'
  },
  'male-bbl': {
    url: 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-bbl/',
    template: 'male-procedure-templates/male-bbl-template.html'
  }
};

async function scrapeLiveContent(url) {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  try {
    await page.goto(url, { 
      waitUntil: 'domcontentloaded',
      timeout: 15000 
    });
    
    await page.waitForTimeout(2000);
    
    const content = await page.evaluate(() => {
      const headings = Array.from(document.querySelectorAll('h2')).map(h => h.textContent.trim()).filter(Boolean);
      const paragraphs = Array.from(document.querySelectorAll('p')).map(p => p.textContent.trim()).filter(text => text.length > 20);
      
      return { headings, paragraphs };
    });
    
    await browser.close();
    return content;
    
  } catch (error) {
    await browser.close();
    console.error(`Error scraping ${url}: ${error.message}`);
    return null;
  }
}

async function extractTemplateContent(templatePath) {
  try {
    const html = await fs.readFile(templatePath, 'utf-8');
    
    // Extract text content (with multiline support)
    const headings = [];
    const paragraphs = [];
    
    // Extract h2 headings (with DOTALL flag for multiline)
    const h2Matches = html.matchAll(/<h2[^>]*>([\s\S]*?)<\/h2>/gi);
    for (const match of h2Matches) {
      const text = match[1].replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
      if (text) headings.push(text);
    }
    
    // Extract paragraphs (with DOTALL flag for multiline)
    const pMatches = html.matchAll(/<p[^>]*>([\s\S]*?)<\/p>/gi);
    for (const match of pMatches) {
      const text = match[1].replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
      if (text && text.length > 20) {
        paragraphs.push(text);
      }
    }
    
    return { headings, paragraphs };
    
  } catch (error) {
    console.error(`Error reading template ${templatePath}: ${error.message}`);
    return null;
  }
}

function compareContent(live, template, procedureName) {
  const report = {
    procedure: procedureName,
    headingsMatch: [],
    headingsMissing: [],
    contentMatches: [],
    contentMissing: [],
    accuracy: 0
  };
  
  // Compare headings
  live.headings.forEach(heading => {
    if (template.headings.some(th => th.includes(heading) || heading.includes(th))) {
      report.headingsMatch.push(heading);
    } else {
      report.headingsMissing.push(heading);
    }
  });
  
  // Compare content (check if key phrases from live site exist in template)
  const templateText = template.paragraphs.join(' ').toLowerCase();
  let contentMatches = 0;
  
  live.paragraphs.forEach(paragraph => {
    const firstWords = paragraph.split(' ').slice(0, 8).join(' ').toLowerCase();
    if (templateText.includes(firstWords)) {
      report.contentMatches.push(paragraph.substring(0, 100) + '...');
      contentMatches++;
    } else {
      report.contentMissing.push(paragraph.substring(0, 100) + '...');
    }
  });
  
  // Calculate accuracy
  const totalChecks = live.headings.length + live.paragraphs.length;
  const matches = report.headingsMatch.length + contentMatches;
  
  if (totalChecks > 0) {
    report.accuracy = Math.round((matches / totalChecks) * 100);
  }
  
  return report;
}

async function verifyAllProcedures() {
  console.log('Mia Aesthetics Content Accuracy Verification');
  console.log('='.repeat(50));
  
  const results = [];
  
  for (const [name, config] of Object.entries(PROCEDURES)) {
    console.log(`\\n--- Verifying ${name} ---`);
    
    // Scrape live content
    console.log('Fetching live content...');
    const liveContent = await scrapeLiveContent(config.url);
    
    if (!liveContent) {
      results.push({ procedure: name, error: 'Failed to fetch live content' });
      continue;
    }
    
    // Extract template content
    console.log('Reading template...');
    const templateContent = await extractTemplateContent(config.template);
    
    if (!templateContent) {
      results.push({ procedure: name, error: 'Failed to read template' });
      continue;
    }
    
    // Compare
    const comparison = compareContent(liveContent, templateContent, name);
    results.push(comparison);
    
    // Display results
    console.log(`Live site: ${liveContent.headings.length} headings, ${liveContent.paragraphs.length} paragraphs`);
    console.log(`Template: ${templateContent.headings.length} headings, ${templateContent.paragraphs.length} paragraphs`);
    console.log(`Accuracy: ${comparison.accuracy}%`);
    
    if (comparison.accuracy >= 80) {
      console.log('✅ GOOD - Content matches well');
    } else if (comparison.accuracy >= 60) {
      console.log('⚠️ FAIR - Some content matches');
    } else {
      console.log('❌ POOR - Major differences found');
    }
    
    // Wait between requests
    await new Promise(resolve => setTimeout(resolve, 3000));
  }
  
  // Save detailed report
  await fs.writeFile('accuracy-report.json', JSON.stringify(results, null, 2));
  
  // Summary
  console.log('\\n' + '='.repeat(50));
  console.log('SUMMARY:');
  
  results.forEach(result => {
    if (result.error) {
      console.log(`❌ ${result.procedure}: ${result.error}`);
    } else {
      const status = result.accuracy >= 80 ? '✅' : result.accuracy >= 60 ? '⚠️' : '❌';
      console.log(`${status} ${result.procedure}: ${result.accuracy}% accuracy`);
    }
  });
  
  console.log(`\\nDetailed report saved to: accuracy-report.json`);
  
  return results;
}

// Run verification
if (require.main === module) {
  verifyAllProcedures().catch(console.error);
}

module.exports = { verifyAllProcedures, scrapeLiveContent, extractTemplateContent, compareContent };