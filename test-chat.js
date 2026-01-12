import { chromium } from 'playwright';

const browser = await chromium.launch({ headless: true });
const context = await browser.newContext();
const page = await context.newPage();

// Go to login page
await page.goto('https://aichat.demo.sbarron.com/login');
await page.waitForLoadState('networkidle');

// Click login with pre-filled credentials
await page.click('button[type="submit"]');
await page.waitForLoadState('networkidle');

// Click on the Welcome conversation
await page.click('text=Welcome to AIChat!');
await page.waitForLoadState('networkidle');
await page.waitForTimeout(1000);

// Take screenshot of chat interface
await page.screenshot({ path: 'chat-interface.png', fullPage: true });
console.log('Chat interface URL:', page.url());

// Send a message
const textarea = await page.$('textarea');
if (textarea) {
    await textarea.fill('Hello! What can you help me with today?');
    await page.screenshot({ path: 'chat-before-send.png', fullPage: true });
    
    // Click send button
    await page.click('button[type="submit"]');
    
    // Wait for response (up to 30 seconds for AI)
    await page.waitForTimeout(10000);
    await page.screenshot({ path: 'chat-after-send.png', fullPage: true });
}

await browser.close();
