#!/usr/bin/env node

const axios = require('axios');
const cheerio = require('cheerio');
const fs = require('fs').promises;

// URLs to crawl
const PROCEDURE_URLS = {
  'breast-augmentation': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/augmentation-implants/',
  'breast-reduction': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/reduction/',
  'breast-lift': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/lift/',
  'breast-implant-revision': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/implant-revision/',
  'male-bbl': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-bbl/',
  'male-liposuction': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-liposuction/',
  'male-tummy-tuck': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/body/male-tummy-tuck/',
  'gynecomastia': 'https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/male-breast-procedures/'
};

async function crawlProcedurePage(url) {
  try {
    console.log(`Fetching: ${url}`);
    
    // Fetch the page
    const response = await axios.get(url, {
      headers: {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
      },
      timeout: 10000
    });
    
    // Parse with cheerio
    const $ = cheerio.load(response.data);
    
    // Extract content
    const content = {
      headings: [],
      paragraphs: [],
      lists: []
    };
    
    // Get all h2 headings
    $('h2').each((i, elem) => {
      const text = $(elem).text().trim();
      if (text) {
        content.headings.push(text);
      }
    });
    
    // Get all paragraphs
    $('p').each((i, elem) => {
      const text = $(elem).text().trim();
      if (text && text.length > 20) {
        content.paragraphs.push(text);
      }
    });
    
    // Get list items
    $('li').each((i, elem) => {
      const text = $(elem).text().trim();
      if (text) {
        content.lists.push(text);
      }
    });
    
    return content;
    
  } catch (error) {
    console.error(`Error crawling ${url}:`, error.message);
    return null;
  }
}

async function crawlAllProcedures() {
  const results = {};
  
  for (const [name, url] of Object.entries(PROCEDURE_URLS)) {
    console.log(`\n--- Crawling ${name} ---`);
    const content = await crawlProcedurePage(url);
    
    if (content) {
      results[name] = {
        url,
        ...content,
        timestamp: new Date().toISOString()
      };
      
      console.log(`✓ Found ${content.headings.length} headings, ${content.paragraphs.length} paragraphs`);
      
      // Show first few headings
      if (content.headings.length > 0) {
        console.log(`  Headings: ${content.headings.slice(0, 3).join(', ')}...`);
      }
    } else {
      results[name] = { error: 'Failed to crawl' };
    }
    
    // Wait between requests to be polite
    await new Promise(resolve => setTimeout(resolve, 1000));
  }
  
  // Save results
  await fs.writeFile('crawled-content.json', JSON.stringify(results, null, 2));
  console.log('\n✓ Results saved to crawled-content.json');
  
  return results;
}

// Function to compare with template
function compareWithTemplate(crawledContent, templateName) {
  // This will compare the crawled content with our template
  console.log(`\nComparing ${templateName}:`);
  
  if (crawledContent.headings) {
    console.log(`Live site headings: ${crawledContent.headings.join(', ')}`);
  }
  
  if (crawledContent.paragraphs && crawledContent.paragraphs.length > 0) {
    console.log(`First paragraph: ${crawledContent.paragraphs[0].substring(0, 100)}...`);
  }
}

// Run if called directly
if (require.main === module) {
  crawlAllProcedures()
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

module.exports = { crawlProcedurePage, crawlAllProcedures, compareWithTemplate };