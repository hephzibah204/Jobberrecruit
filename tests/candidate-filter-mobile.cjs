// Exercise the production drawer CSS and script without requiring seeded accounts.
const { chromium, devices } = require('playwright');
const fs = require('node:fs');
const assert = require('node:assert/strict');

(async () => {
  const source = fs.readFileSync('app/Views/employers/candidates.php', 'utf8');
  const styles = source.match(/<style>([\s\S]*?)<\/style>/)[1];
  const script = source.split('/* Mobile filters drawer toggle')[1].split('function showToast')[0];
  const shell = fs.readFileSync('css/employer-shell.css', 'utf8');
  const browser = await chromium.launch({ ...(process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {}), headless: true, timeout: 60000 });
  try {
    for (const device of (process.env.TEST_DEVICES || 'iPhone SE,iPhone 13,iPhone 13 Pro Max,Pixel 5,Galaxy S9+').split(',')) {
      const context = await browser.newContext({ ...devices[device] });
      const page = await context.newPage();
      await page.setContent(`<meta name="viewport" content="width=device-width,initial-scale=1"><style>${shell}${styles}</style>
        <body class="emp-shell"><div id="emp-scrim" class="emp-scrim" hidden></div>
        <button id="f-open" style="margin:60px">Filters</button>
        <main class="emp-content"><div class="cand-layout"><aside id="filters" class="card filters-card"><button id="f-close" class="f-close">Close</button><button id="f-apply-btn">View Results</button></aside></div></main>
        <script>/* Mobile filters drawer toggle${script}</script>`);
      const drawer = page.locator('#filters');
      const closed = async () => {
        await page.waitForTimeout(350);
        assert.equal(await drawer.evaluate(e => getComputedStyle(e).visibility), 'hidden');
        assert.equal(await page.locator('body').evaluate(e => e.classList.contains('menu-open')), false);
      };
      await closed();
      // A touch beginning on the button must not open a drawer during a scroll gesture.
      await page.locator('#f-open').dispatchEvent('touchstart');
      assert.equal(await drawer.evaluate(e => e.classList.contains('open')), false);
      for (const target of ['#f-close', '#f-apply-btn', '#emp-scrim']) {
        await page.locator('#f-open').tap();
        await page.waitForTimeout(350);
        assert.equal(await drawer.evaluate(e => getComputedStyle(e).visibility), 'visible');
        assert.equal(await drawer.evaluate(e => getComputedStyle(e).transform), 'none');
        assert.deepEqual(await drawer.evaluate(e => {
          const result = [];
          for (let node = e; node; node = node.parentElement) {
            const s = getComputedStyle(node);
            if (s.filter !== 'none' || (s.backdropFilter && s.backdropFilter !== 'none') || Number(s.opacity) < 1 || s.transform !== 'none') {
              result.push({ tag: node.tagName, filter: s.filter, backdrop: s.backdropFilter, opacity: s.opacity, transform: s.transform });
            }
          }
          return result;
        }), [], 'Filter and its ancestors must not blur, fade, or transform its text');
        assert.equal(await page.locator('#f-close').evaluate(e => {
          const r = e.getBoundingClientRect();
          return e.contains(document.elementFromPoint(r.x + r.width / 2, r.y + r.height / 2));
        }), true, 'The filter must paint above the scrim');
        if (target === '#emp-scrim') await page.touchscreen.tap(page.viewportSize().width - 10, 100);
        else await page.locator(target).tap();
        await closed();
      }
      await page.evaluate(() => { window.openCandidateFilters(); window.closeCandidateFilters(); });
      await closed();
      assert.equal(await page.locator('#emp-scrim').evaluate(e => e.classList.contains('show')), false);
      await page.evaluate(() => { window.openCandidateFilters(); window.dispatchEvent(new Event('pageshow')); });
      await closed();
      console.log(`${device}: initial state, scroll gesture, close, results, backdrop, immediate close, history restore passed`);
      await context.close();
    }
  } finally { await browser.close(); }
})().catch(error => { console.error(error); process.exitCode = 1; });
