// Mobile responsiveness audit script
// Takes screenshots at common breakpoints for visual inspection

const { chromium, devices } = require('C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/node_modules/playwright-core');
const path = require('path');

const OUT_DIR = 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/scratch/mobile-audit';
const BASE_URL = 'http://127.0.0.1:8085';

const PAGES = [
  { name: 'home',    url: '/' },
  { name: 'jobs',    url: '/jobs' },
  { name: 'login',   url: '/login' },
  { name: 'register',url: '/register' },
  { name: 'contact', url: '/contact-us' },
  { name: 'about',   url: '/about-us' },
  { name: 'blog',    url: '/blog' },
  { name: 'faq',     url: '/faq' },
];

const VIEWPORTS = [
  { name: 'iphone-se',     width: 375,  height: 667  },
  { name: 'iphone-14',     width: 390,  height: 844  },
  { name: 'iphone-14-pro', width: 393,  height: 852  },
  { name: 'pixel-7',       width: 412,  height: 915  },
  { name: 'ipad-mini',     width: 768,  height: 1024 },
];

async function main() {
  const fs = require('fs');
  if (!fs.existsSync(OUT_DIR)) {
    fs.mkdirSync(OUT_DIR, { recursive: true });
  }

  const browser = await chromium.launch({ headless: true });

  for (const vp of VIEWPORTS) {
    const context = await browser.newContext({
      viewport: { width: vp.width, height: vp.height },
      deviceScaleFactor: 1,
      isMobile: vp.width < 768,
      hasTouch: vp.width < 768,
    });
    const page = await context.newPage();

    for (const pg of PAGES) {
      const file = path.join(OUT_DIR, `${pg.name}__${vp.name}.png`);
      try {
        const resp = await page.goto(BASE_URL + pg.url, {
          waitUntil: 'networkidle',
          timeout: 20000,
        });
        // Wait briefly for layout
        await page.waitForTimeout(700);
        await page.screenshot({ path: file, fullPage: true });
        const status = resp ? resp.status() : '???';
        console.log(`✓ ${vp.name.padEnd(13)} ${pg.name.padEnd(8)} ${status}`);
      } catch (err) {
        console.log(`✗ ${vp.name.padEnd(13)} ${pg.name.padEnd(8)} ERR: ${err.message.split('\n')[0]}`);
      }
    }
    await context.close();
  }

  await browser.close();
  console.log('\nDone. Screenshots in:', OUT_DIR);
}

main().catch(err => {
  console.error('Fatal:', err.message);
  process.exit(1);
});
