#!/usr/bin/env node

const { chromium } = require('playwright');
const fs = require('fs').promises;

// Breast procedures to verify (focusing on core content only)
const BREAST_PROCEDURES = {
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
  }
};

async function scrapeCoreContent(url) {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  try {
    console.log(`  🌐 Fetching: ${url}`);
    await page.goto(url, { 
      waitUntil: 'domcontentloaded',
      timeout: 15000 
    });
    
    await page.waitForTimeout(2000);
    
    const content = await page.evaluate(() => {
      // Focus on core procedure content, not layout elements
      const headings = Array.from(document.querySelectorAll('h2')).map(h => h.textContent.trim()).filter(text => {
        // Filter out FAQ and layout headings
        return !text.includes('FAQ') && 
               !text.includes('DREAM BODY') && 
               !text.includes('GET YOUR') &&
               text.length > 0;
      });
      
      const paragraphs = Array.from(document.querySelectorAll('p')).map(p => p.textContent.trim()).filter(text => {
        // Filter out pricing, forms, and layout content
        return text.length > 30 && 
               !text.includes('Starting Price') &&
               !text.includes('$') &&
               !text.includes('required fields') &&
               !text.includes('© 2025') &&
               !text.includes('Home /') &&
               !text.includes('BEGIN YOUR TRANSFORMATION');
      });
      
      return { headings, paragraphs };
    });
    
    await browser.close();
    console.log(`  ✓ Core content: ${content.headings.length} headings, ${content.paragraphs.length} paragraphs`);
    return content;
    
  } catch (error) {
    await browser.close();
    console.error(`  ❌ Error scraping: ${error.message}`);
    return null;
  }
}

async function extractTemplateContent(templatePath) {
  try {
    const html = await fs.readFile(templatePath, 'utf-8');
    
    const headings = [];
    const paragraphs = [];
    
    // Extract h2 headings
    const h2Matches = html.matchAll(/<h2[^>]*>([\s\S]*?)<\/h2>/gi);
    for (const match of h2Matches) {
      const text = match[1].replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
      if (text) headings.push(text);
    }
    
    // Extract paragraphs
    const pMatches = html.matchAll(/<p[^>]*>([\s\S]*?)<\/p>/gi);
    for (const match of pMatches) {
      const text = match[1].replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();
      if (text && text.length > 20) {
        paragraphs.push(text);
      }
    }
    
    console.log(`  📄 Template: ${headings.length} headings, ${paragraphs.length} paragraphs`);
    return { headings, paragraphs };
    
  } catch (error) {
    console.error(`  ❌ Error reading template: ${error.message}`);
    return null;
  }
}

function analyzeCoreContent(live, template, procedureName) {
  const analysis = {
    procedure: procedureName,
    headingsMatch: [],
    headingsMissing: [],
    contentMatches: [],
    contentMissing: [],
    coreAccuracy: 0
  };
  
  // Compare core procedure headings only
  live.headings.forEach(heading => {
    if (template.headings.some(th => 
      th.toLowerCase().includes(heading.toLowerCase().substring(0, 10)) || 
      heading.toLowerCase().includes(th.toLowerCase().substring(0, 10))
    )) {
      analysis.headingsMatch.push(heading);
    } else {
      analysis.headingsMissing.push(heading);
    }
  });
  
  // Compare core content paragraphs
  const templateText = template.paragraphs.join(' ').toLowerCase();
  let coreMatches = 0;
  
  live.paragraphs.forEach(paragraph => {
    const firstWords = paragraph.split(' ').slice(0, 8).join(' ').toLowerCase();
    if (templateText.includes(firstWords)) {
      analysis.contentMatches.push(paragraph.substring(0, 80) + '...');
      coreMatches++;
    } else {
      analysis.contentMissing.push(paragraph.substring(0, 80) + '...');
    }
  });
  
  // Calculate core content accuracy (procedure info only)
  const totalCoreContent = live.headings.length + Math.min(live.paragraphs.length, 8); // Focus on main paragraphs
  const totalMatches = analysis.headingsMatch.length + coreMatches;
  
  if (totalCoreContent > 0) {
    analysis.coreAccuracy = Math.round((totalMatches / totalCoreContent) * 100);
  }
  
  return analysis;
}

async function verifyCoreContent() {
  console.log('🔍 Mia Aesthetics - Core Content Verification (Template Content Only)');
  console.log('=' .repeat(70));
  console.log('Note: Ignoring FAQ sections, pricing, H1s - these are handled by page layout');
  console.log('=' .repeat(70));
  
  const results = [];
  
  for (const [name, config] of Object.entries(BREAST_PROCEDURES)) {
    console.log(`\\n📋 VERIFYING CORE CONTENT: ${name.toUpperCase()}`);
    console.log('-'.repeat(50));
    
    // Scrape core content only
    const liveContent = await scrapeCoreContent(config.url);
    if (!liveContent) {
      results.push({ procedure: name, error: 'Failed to fetch live content' });
      continue;
    }
    
    // Extract template content
    const templateContent = await extractTemplateContent(config.template);
    if (!templateContent) {
      results.push({ procedure: name, error: 'Failed to read template' });
      continue;
    }
    
    // Analyze core content
    const analysis = analyzeCoreContent(liveContent, templateContent, name);
    results.push(analysis);
    
    // Display results
    console.log(`\\n  📊 CORE CONTENT ANALYSIS:`);
    console.log(`     Core Accuracy: ${analysis.coreAccuracy}%`);
    console.log(`     Headings Matched: ${analysis.headingsMatch.length}/${liveContent.headings.length}`);
    console.log(`     Content Blocks Matched: ${analysis.contentMatches.length}/${liveContent.paragraphs.length}`);
    
    if (analysis.coreAccuracy >= 85) {
      console.log(`  🎉 EXCELLENT - Core content is highly accurate`);
    } else if (analysis.coreAccuracy >= 70) {
      console.log(`  ✅ GOOD - Core content matches well`);
    } else if (analysis.coreAccuracy >= 50) {
      console.log(`  ⚠️ FAIR - Some core content matches`);
    } else {
      console.log(`  ❌ NEEDS UPDATE - Core content differs significantly`);
    }
    
    // Show matched headings
    if (analysis.headingsMatch.length > 0) {
      console.log(`\\n  🎯 Core Headings Matched:`);
      analysis.headingsMatch.forEach(h => console.log(`     ✓ ${h}`));
    }
    
    if (analysis.headingsMissing.length > 0) {
      console.log(`\\n  ⚠️ Missing Core Headings:`);
      analysis.headingsMissing.forEach(h => console.log(`     • ${h}`));
    }
    
    // Wait between requests
    await new Promise(resolve => setTimeout(resolve, 3000));
  }
  
  // Save report
  await fs.writeFile('core-content-report.json', JSON.stringify(results, null, 2));
  
  // Summary
  console.log('\\n' + '='.repeat(70));
  console.log('📈 CORE CONTENT SUMMARY (Template Content Only):');
  console.log('='.repeat(70));
  
  let totalScore = 0;
  let validResults = 0;
  
  results.forEach(result => {
    if (result.error) {
      console.log(`❌ ${result.procedure.toUpperCase()}: ${result.error}`);
    } else {
      const emoji = result.coreAccuracy >= 85 ? '🎉' : 
                   result.coreAccuracy >= 70 ? '✅' : 
                   result.coreAccuracy >= 50 ? '⚠️' : '❌';
      console.log(`${emoji} ${result.procedure.toUpperCase()}: ${result.coreAccuracy}% core accuracy`);
      totalScore += result.coreAccuracy;
      validResults++;
    }
  });
  
  if (validResults > 0) {
    const avgScore = Math.round(totalScore / validResults);
    console.log(`\\n🏆 OVERALL CORE CONTENT ACCURACY: ${avgScore}%`);
    
    if (avgScore >= 85) {
      console.log('🎉 Templates are highly accurate for core content!');
    } else if (avgScore >= 70) {
      console.log('✅ Templates have good core content accuracy.');
    } else {
      console.log('⚠️ Some templates may need core content updates.');
    }
  }
  
  console.log(`\\n📋 Detailed report: core-content-report.json`);
  
  return results;
}

// Run verification
if (require.main === module) {
  verifyCoreContent().catch(console.error);
}

module.exports = { verifyCoreContent, scrapeCoreContent, extractTemplateContent, analyzeCoreContent };