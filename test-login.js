import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext();
const page = await context.newPage();

// Go to login page
await page.goto('https://aichat.demo.sbarron.com/login');
await page.waitForLoadState('networkidle');

// Demo credentials should be pre-filled, just click login
await page.click('button[type="submit"]');

// Wait for redirect
await page.waitForLoadState('networkidle');
await page.waitForTimeout(2000);

// Take screenshot
await page.screenshot({ path: 'chat-after-login.png', fullPage: true });
console.log('Current URL:', page.url());

await browser.close();
