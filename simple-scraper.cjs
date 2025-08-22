#!/usr/bin/env node

const { chromium } = require('playwright');

async function quickScrape(url) {
  console.log(`Scraping: ${url}`);
  
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  try {
    // Simple navigation with shorter timeout
    await page.goto(url, { 
      waitUntil: 'domcontentloaded',
      timeout: 15000 
    });
    
    // Wait a bit for content to load
    await page.waitForTimeout(3000);
    
    // Extract content
    const content = await page.evaluate(() => {
      const headings = Array.from(document.querySelectorAll('h2')).map(h => h.textContent.trim()).filter(Boolean);
      const paragraphs = Array.from(document.querySelectorAll('p')).map(p => p.textContent.trim()).filter(text => text.length > 20);
      
      return { headings, paragraphs: paragraphs.slice(0, 10) };
    });
    
    await browser.close();
    
    console.log(`✓ Found ${content.headings.length} headings, ${content.paragraphs.length} paragraphs`);
    return content;
    
  } catch (error) {
    await browser.close();
    console.error(`✗ Error: ${error.message}`);
    return null;
  }
}

// Test on breast augmentation page
if (require.main === module) {
  quickScrape('https://www.miaaesthetics.com/cosmetic-plastic-surgery/breast/augmentation-implants/')
    .then(result => {
      if (result) {
        console.log('\n=== HEADINGS ===');
        result.headings.forEach((h, i) => console.log(`${i+1}. ${h}`));
        
        console.log('\n=== FIRST PARAGRAPH ===');
        console.log(result.paragraphs[0] || 'No paragraphs found');
        
        console.log('\n=== COMPARISON WITH OUR TEMPLATE ===');
        const ourContent = "Breast augmentation is a surgical procedure that involves placing synthetic implants into the chest to enhance the volume and appearance of breasts.";
        
        if (result.paragraphs.some(p => p.includes("Breast augmentation is a surgical procedure"))) {
          console.log('✓ MATCH: Our template content matches the live site!');
        } else {
          console.log('✗ NO MATCH: Content differs from live site');
          console.log('Live site first paragraph:', result.paragraphs[0]);
          console.log('Our template:', ourContent);
        }
      }
    })
    .catch(console.error);
}

module.exports = { quickScrape };