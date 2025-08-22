// const fs = require("fs"); // Uncomment if file operations are needed
const https = require("https");

// Function to make HTTP request
function makeRequest(url) {
  return new Promise((resolve, reject) => {
    https
      .get(
        url,
        {
          headers: {
            "User-Agent":
              "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
          },
        },
        (response) => {
          let data = "";
          response.on("data", (chunk) => {
            data += chunk;
          });
          response.on("end", () => {
            resolve(data);
          });
        }
      )
      .on("error", (error) => {
        reject(error);
      });
  });
}

// Function to extract headings and paragraphs from HTML
function extractContent(html) {
  const headings = [];
  const paragraphs = [];

  // Extract H2 headings
  const h2Regex = /<h2[^>]*>(.*?)<\/h2>/gi;
  let h2Match;
  while ((h2Match = h2Regex.exec(html)) !== null) {
    const text = h2Match[1].replace(/<[^>]*>/g, "").trim();
    if (
      text.length > 0 &&
      !text.includes("FAQ") &&
      !text.includes("DREAM BODY") &&
      !text.includes("GET YOUR") &&
      !text.includes("Frequently Asked Questions") &&
      !text.includes("Starting Price")
    ) {
      headings.push(text);
    }
  }

  // Extract paragraphs
  const pRegex = /<p[^>]*>(.*?)<\/p>/gi;
  let pMatch;
  while ((pMatch = pRegex.exec(html)) !== null) {
    const text = pMatch[1].replace(/<[^>]*>/g, "").trim();
    if (
      text.length > 30 &&
      !text.includes("Starting Price") &&
      !text.includes("required fields") &&
      !text.includes("© 2025") &&
      !text.includes("Home /") &&
      !text.includes("BEGIN YOUR TRANSFORMATION") &&
      !text.includes("DREAM BODY") &&
      !text.includes("GET YOUR") &&
      !text.includes("FAQ") &&
      !text.includes("BOOK YOUR CONSULTATION") &&
      !text.includes("Contact Information") &&
      !text.includes("Preferred Communication")
    ) {
      paragraphs.push(text);
    }
  }

  return { headings, paragraphs };
}

// URLs to check
const procedures = [
  {
    name: "Blepharoplasty",
    url: "https://miaaesthetics.com/cosmetic-plastic-surgery/face/blepharoplasty/",
  },
  {
    name: "Rhinoplasty",
    url: "https://miaaesthetics.com/cosmetic-plastic-surgery/face/rhinoplasty/",
  },
  {
    name: "Otoplasty",
    url: "https://miaaesthetics.com/cosmetic-plastic-surgery/face/otoplasty/",
  },
];

async function checkProcedures() {
  console.log("Checking remaining face procedures for content accuracy...\n");

  for (const procedure of procedures) {
    try {
      console.log(`=== ${procedure.name.toUpperCase()} ===`);
      console.log(`URL: ${procedure.url}`);

      const html = await makeRequest(procedure.url);
      const content = extractContent(html);

      console.log("\nExtracted Content:");
      console.log("HEADINGS:");
      content.headings.forEach((heading, i) => {
        console.log(`${i + 1}. ${heading}`);
      });

      console.log("\nPARAGRAPHS:");
      content.paragraphs.slice(0, 10).forEach((paragraph, i) => {
        console.log(`${i + 1}. ${paragraph.substring(0, 100)}...`);
      });

      console.log(`\nTotal headings: ${content.headings.length}`);
      console.log(`Total paragraphs: ${content.paragraphs.length}`);
      console.log("\n" + "=".repeat(80) + "\n");
    } catch (error) {
      console.log(`Error checking ${procedure.name}: ${error.message}\n`);
    }
  }
}

checkProcedures();
