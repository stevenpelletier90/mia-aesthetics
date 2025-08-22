#!/usr/bin/env node

const { chromium } = require('playwright');
const fs = require('fs').promises;

// URLs to scrape
const PROCEDURE_URLS = {
  'breast-augmentation': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/augmentation-implants/',
  'breast-reduction': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/reduction/',
  'breast-lift': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/lift/',
  'breast-implant-revision': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/implant-revision/',
  'male-bbl': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-bbl/'
};

async function scrapePage(url) {
  const browser = await chromium.launch({ headless: true });
  
  try {
    const page = await browser.newPage();
    
    // Set user agent to appear more like a real browser
    await page.setExtraHTTPHeaders({
      'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    });
    
    console.log(`Navigating to: ${url}`);
    await page.goto(url, { waitUntil: 'networkidle' });
    
    // Extract content
    const content = await page.evaluate(() => {
      const result = {
        headings: [],
        paragraphs: [],
        lists: []
      };
      
      // Get all h2 headings
      document.querySelectorAll('h2').forEach(h => {
        const text = h.textContent.trim();
        if (text) result.headings.push(text);
      });
      
      // Get all paragraphs
      document.querySelectorAll('p').forEach(p => {
        const text = p.textContent.trim();
        if (text && text.length > 20) {
          result.paragraphs.push(text);
        }
      });
      
      // Get list items
      document.querySelectorAll('li').forEach(li => {
        const text = li.textContent.trim();
        if (text) result.lists.push(text);
      });
      
      return result;
    });
    
    return content;
    
  } catch (error) {
    console.error(`Error scraping ${url}:`, error.message);
    return null;
  } finally {
    await browser.close();
  }
}

async function scrapeAll() {
  const results = {};
  
  for (const [name, url] of Object.entries(PROCEDURE_URLS)) {
    console.log(`\n--- Scraping ${name} ---`);
    const content = await scrapePage(url);
    
    if (content) {
      results[name] = {
        url,
        ...content,
        timestamp: new Date().toISOString()
      };
      
      console.log(`✓ Found ${content.headings.length} headings, ${content.paragraphs.length} paragraphs`);
      
      // Show first few items
      if (content.headings.length > 0) {
        console.log(`  First heading: ${content.headings[0]}`);
      }
      if (content.paragraphs.length > 0) {
        console.log(`  First paragraph: ${content.paragraphs[0].substring(0, 100)}...`);
      }
    } else {
      results[name] = { error: 'Failed to scrape' };
    }
    
    // Wait between requests
    await new Promise(resolve => setTimeout(resolve, 2000));
  }
  
  // Save results
  await fs.writeFile('scraped-content.json', JSON.stringify(results, null, 2));
  console.log('\n✓ Results saved to scraped-content.json');
  
  return results;
}

// Run if called directly
if (require.main === module) {
  scrapeAll()
    .then(results => {
      console.log('\n=== SUMMARY ===');
      Object.entries(results).forEach(([name, data]) => {
        if (data.error) {
          console.log(`❌ ${name}: ${data.error}`);
        } else {
          console.log(`✅ ${name}: ${data.headings?.length || 0} headings, ${data.paragraphs?.length || 0} paragraphs`);
        }
      });
    })
    .catch(console.error);
}

module.exports = { scrapePage, scrapeAll };