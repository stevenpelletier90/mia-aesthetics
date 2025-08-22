const fs = require('fs').promises;
const path = require('path');

// Mia Aesthetics procedure URLs to crawl
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

// Function to extract text content from HTML
function extractTextContent(html) {
  // Remove script and style tags
  html = html.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
  html = html.replace(/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/gi, '');
  
  // Extract main content area if it exists
  const mainContentMatch = html.match(/<main[^>]*>([\s\S]*?)<\/main>/i) ||
                          html.match(/<article[^>]*>([\s\S]*?)<\/article>/i) ||
                          html.match(/<div[^>]*class="[^"]*content[^"]*"[^>]*>([\s\S]*?)<\/div>/i);
  
  if (mainContentMatch) {
    html = mainContentMatch[1];
  }
  
  // Extract headings and paragraphs
  const headings = [];
  const paragraphs = [];
  
  // Extract h1-h6 headings
  const headingMatches = html.matchAll(/<h[1-6][^>]*>(.*?)<\/h[1-6]>/gi);
  for (const match of headingMatches) {
    const text = match[1].replace(/<[^>]+>/g, '').trim();
    if (text) headings.push(text);
  }
  
  // Extract paragraphs
  const paragraphMatches = html.matchAll(/<p[^>]*>(.*?)<\/p>/gi);
  for (const match of paragraphMatches) {
    const text = match[1].replace(/<[^>]+>/g, '').trim();
    if (text && text.length > 20) paragraphs.push(text);
  }
  
  // Extract list items
  const listItems = [];
  const listMatches = html.matchAll(/<li[^>]*>(.*?)<\/li>/gi);
  for (const match of listMatches) {
    const text = match[1].replace(/<[^>]+>/g, '').trim();
    if (text) listItems.push(text);
  }
  
  return {
    headings,
    paragraphs,
    listItems,
    fullText: html.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim()
  };
}

// Function to compare content
function compareContent(original, template) {
  const similarities = [];
  const differences = [];
  
  // Check headings
  original.headings.forEach(heading => {
    if (template.fullText.includes(heading)) {
      similarities.push(`✓ Heading found: "${heading}"`);
    } else {
      differences.push(`✗ Heading missing: "${heading}"`);
    }
  });
  
  // Check key paragraphs (first few words to account for formatting differences)
  original.paragraphs.forEach(para => {
    const firstWords = para.split(' ').slice(0, 10).join(' ');
    if (template.fullText.includes(firstWords)) {
      similarities.push(`✓ Paragraph found (starts with): "${firstWords}..."`);
    } else {
      differences.push(`✗ Paragraph missing (starts with): "${firstWords}..."`);
    }
  });
  
  return {
    similarities,
    differences,
    accuracy: (similarities.length / (similarities.length + differences.length) * 100).toFixed(2)
  };
}

// Main function to crawl and compare
async function crawlAndCompare() {
  const results = {};
  
  for (const [name, url] of Object.entries(PROCEDURE_URLS)) {
    console.log(`\nProcessing ${name}...`);
    console.log(`URL: ${url}`);
    
    try {
      // Read the template file
      let templatePath;
      if (name.includes('male')) {
        templatePath = path.join(__dirname, 'male-procedure-templates', `${name}-template.html`);
      } else if (name.includes('breast')) {
        templatePath = path.join(__dirname, 'breast-procedure-templates', `${name}-template.html`);
      } else {
        templatePath = path.join(__dirname, 'body-procedure-templates', `${name}-template.html`);
      }
      
      let templateContent = '';
      try {
        templateContent = await fs.readFile(templatePath, 'utf-8');
      } catch (err) {
        console.log(`Template not found: ${templatePath}`);
        continue;
      }
      
      const templateData = extractTextContent(templateContent);
      
      // Store results
      results[name] = {
        url,
        templatePath,
        templateContent: {
          headingCount: templateData.headings.length,
          paragraphCount: templateData.paragraphs.length,
          sample: templateData.paragraphs.slice(0, 2)
        }
      };
      
      console.log(`Found ${templateData.headings.length} headings and ${templateData.paragraphs.length} paragraphs in template`);
      
    } catch (error) {
      console.error(`Error processing ${name}:`, error.message);
      results[name] = { error: error.message };
    }
  }
  
  // Save results
  await fs.writeFile(
    path.join(__dirname, 'content-analysis.json'),
    JSON.stringify(results, null, 2)
  );
  
  console.log('\nAnalysis complete! Results saved to content-analysis.json');
  return results;
}

// Function to fetch content using Puppeteer (to be called from main script)
async function fetchUrlContent(url) {
  // This would be called using the Puppeteer MCP tool
  // For now, we'll create a placeholder
  return {
    headings: [],
    paragraphs: [],
    listItems: []
  };
}

// Export for use in other scripts
module.exports = {
  extractTextContent,
  compareContent,
  crawlAndCompare,
  PROCEDURE_URLS
};

// Run if called directly
if (require.main === module) {
  crawlAndCompare().catch(console.error);
}