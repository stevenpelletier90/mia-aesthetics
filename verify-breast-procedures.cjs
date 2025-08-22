#!/usr/bin/env node

const { chromium } = require('playwright');
const fs = require('fs').promises;

// Breast procedures to verify
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

async function scrapeLiveContent(url) {
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
      const headings = Array.from(document.querySelectorAll('h2')).map(h => h.textContent.trim()).filter(Boolean);
      const paragraphs = Array.from(document.querySelectorAll('p')).map(p => p.textContent.trim()).filter(text => text.length > 20);
      
      return { headings, paragraphs };
    });
    
    await browser.close();
    console.log(`  ✓ Found ${content.headings.length} headings, ${content.paragraphs.length} paragraphs`);
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

function analyzeContentMatch(live, template, procedureName) {
  const analysis = {
    procedure: procedureName,
    headingsMatch: [],
    headingsMissing: [],
    contentMatches: [],
    contentMissing: [],
    keyFindings: [],
    accuracy: 0
  };
  
  // Compare headings
  live.headings.forEach(heading => {
    if (template.headings.some(th => th.includes(heading) || heading.includes(th.substring(0, 15)))) {
      analysis.headingsMatch.push(heading);
    } else {
      // Skip UI headings like "GET YOUR DREAM BODY TODAY"
      if (!heading.includes('DREAM BODY') && !heading.includes('FAQ')) {
        analysis.headingsMissing.push(heading);
      }
    }
  });
  
  // Compare content - look for key procedure information
  const templateText = template.paragraphs.join(' ').toLowerCase();
  let contentMatches = 0;
  let procedureContentFound = 0;
  
  live.paragraphs.forEach(paragraph => {
    // Skip UI/pricing content
    if (paragraph.includes('Starting Price') || paragraph.includes('$') || 
        paragraph.includes('required fields') || paragraph.length < 50) {
      return;
    }
    
    const firstWords = paragraph.split(' ').slice(0, 8).join(' ').toLowerCase();
    if (templateText.includes(firstWords)) {
      analysis.contentMatches.push(paragraph.substring(0, 80) + '...');
      contentMatches++;
      
      // Track procedure-specific content
      if (paragraph.toLowerCase().includes(procedureName.replace('-', ' ')) ||
          paragraph.toLowerCase().includes('procedure') ||
          paragraph.toLowerCase().includes('surgery') ||
          paragraph.toLowerCase().includes('implant')) {
        procedureContentFound++;
      }
    } else if (paragraph.length > 50 && 
               !paragraph.includes('©') && 
               !paragraph.includes('Home /')) {
      analysis.contentMissing.push(paragraph.substring(0, 80) + '...');
    }
  });
  
  // Key findings
  if (analysis.headingsMatch.length >= 2) {
    analysis.keyFindings.push('✅ Main headings match');
  }
  if (procedureContentFound >= 3) {
    analysis.keyFindings.push('✅ Core procedure content found');
  }
  if (contentMatches >= 3) {
    analysis.keyFindings.push('✅ Multiple content blocks match');
  }
  
  // Calculate procedure-focused accuracy
  const relevantLiveContent = live.paragraphs.filter(p => 
    p.length > 50 && 
    !p.includes('Starting Price') && 
    !p.includes('required fields') &&
    !p.includes('©')
  ).length;
  
  const totalRelevant = analysis.headingsMatch.length + contentMatches;
  const totalExpected = Math.min(live.headings.length - 2, 5) + Math.min(relevantLiveContent, 10); // Reasonable expectations
  
  if (totalExpected > 0) {
    analysis.accuracy = Math.round((totalRelevant / totalExpected) * 100);
  }
  
  return analysis;
}

async function verifyBreastProcedures() {
  console.log('🔍 Mia Aesthetics - Breast Procedures Content Verification');
  console.log('=' .repeat(60));
  
  const results = [];
  
  for (const [name, config] of Object.entries(BREAST_PROCEDURES)) {
    console.log(`\\n📋 VERIFYING: ${name.toUpperCase()}`);
    console.log('-'.repeat(40));
    
    // Scrape live content
    const liveContent = await scrapeLiveContent(config.url);
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
    
    // Analyze
    const analysis = analyzeContentMatch(liveContent, templateContent, name);
    results.push(analysis);
    
    // Display results
    console.log(`\\n  📊 ANALYSIS:`);
    console.log(`     Accuracy Score: ${analysis.accuracy}%`);
    console.log(`     Headings Matched: ${analysis.headingsMatch.length}`);
    console.log(`     Content Blocks Matched: ${analysis.contentMatches.length}`);
    
    analysis.keyFindings.forEach(finding => console.log(`     ${finding}`));
    
    if (analysis.accuracy >= 70) {
      console.log(`  🎉 EXCELLENT - Content is highly accurate`);
    } else if (analysis.accuracy >= 50) {
      console.log(`  ✅ GOOD - Content matches well`);
    } else {
      console.log(`  ⚠️ NEEDS REVIEW - Some discrepancies found`);
    }
    
    // Show key matches
    if (analysis.headingsMatch.length > 0) {
      console.log(`\\n  🎯 Matched Headings:`);
      analysis.headingsMatch.forEach(h => console.log(`     • ${h}`));
    }
    
    if (analysis.contentMatches.length > 0) {
      console.log(`\\n  📝 Matched Content (first 3):`);
      analysis.contentMatches.slice(0, 3).forEach(c => console.log(`     • ${c}`));
    }
    
    // Wait between requests
    await new Promise(resolve => setTimeout(resolve, 3000));
  }
  
  // Save detailed report
  await fs.writeFile('breast-procedures-report.json', JSON.stringify(results, null, 2));
  
  // Summary
  console.log('\\n' + '='.repeat(60));
  console.log('📈 BREAST PROCEDURES SUMMARY:');
  console.log('='.repeat(60));
  
  let totalScore = 0;
  let validResults = 0;
  
  results.forEach(result => {
    if (result.error) {
      console.log(`❌ ${result.procedure.toUpperCase()}: ${result.error}`);
    } else {
      const emoji = result.accuracy >= 70 ? '🎉' : result.accuracy >= 50 ? '✅' : '⚠️';
      console.log(`${emoji} ${result.procedure.toUpperCase()}: ${result.accuracy}% accuracy`);
      totalScore += result.accuracy;
      validResults++;
    }
  });
  
  if (validResults > 0) {
    const avgScore = Math.round(totalScore / validResults);
    console.log(`\\n🏆 OVERALL BREAST PROCEDURES ACCURACY: ${avgScore}%`);
  }
  
  console.log(`\\n📋 Detailed report saved to: breast-procedures-report.json`);
  
  return results;
}

// Run verification
if (require.main === module) {
  verifyBreastProcedures().catch(console.error);
}

module.exports = { verifyBreastProcedures, scrapeLiveContent, extractTemplateContent, analyzeContentMatch };