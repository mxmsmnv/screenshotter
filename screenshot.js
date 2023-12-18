const puppeteer = require('puppeteer');
const sharp = require('sharp');
const fs = require('fs');

(async () => {
  const url = process.argv[2];
  const path = process.argv[3];

  const browser = await puppeteer.launch({ headless: "new" });
  const page = await browser.newPage();

  // Set User-Agent for macOS
  await page.setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

  await page.setViewport({ width: 1920, height: 1080, deviceScaleFactor: 2 });

  try {
    // Trying to load the page
    await page.goto(url, { waitUntil: 'load', timeout: 5000 });

    // Creating a screenshot with the specified cropping parameters
    const screenshotBuffer = await page.screenshot();

    // Using sharp to process the screenshot
    const processedImageBuffer = await sharp(screenshotBuffer)
      .withMetadata({ density: 144 })
      // Add any other sharp operations here if necessary
      .toFormat('jpeg', { quality: 100 })
      .toBuffer();

    // Save the processed image to a JPG file
    fs.writeFileSync(path, processedImageBuffer);
  } catch (error) {
    // Processing the error without displaying it on the screen
  } finally {
    // Always close the browser when finished
    await browser.close();
  }
})();
