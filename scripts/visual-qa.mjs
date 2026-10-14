import { chromium } from '@playwright/test';
import fs from 'node:fs';

const outputDir = 'docs/screenshots';
fs.mkdirSync(outputDir, { recursive: true });
const edgePaths = [
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
];
const executablePath = edgePaths.find((candidate) => fs.existsSync(candidate));
if (!executablePath) throw new Error('Microsoft Edge executable was not found.');

const browser = await chromium.launch({ executablePath, headless: true, args: ['--disable-gpu', '--no-sandbox'] });
const failures = [];
const consoleErrors = [];
const results = [];

for (const viewport of [
    { name: 'mobile-narrow', width: 375, height: 812 },
    { name: 'mobile', width: 390, height: 844 },
    { name: 'tablet', width: 768, height: 1024 },
    { name: 'laptop', width: 1024, height: 900 },
    { name: 'desktop', width: 1440, height: 1000 },
]) {
    for (const route of ['/', '/shop', '/about']) {
        const page = await browser.newPage({ viewport: { width: viewport.width, height: viewport.height }, deviceScaleFactor: 1 });
        page.on('console', (message) => { if (message.type() === 'error') consoleErrors.push(`${route}: ${message.text()}`); });
        page.on('pageerror', (error) => consoleErrors.push(`${route}: ${error.message}`));
        page.on('requestfailed', (request) => { if (request.resourceType() === 'image') failures.push(`${route}: ${request.url()}`); });
        const response = await page.goto(`http://127.0.0.1:8000${route}`, { waitUntil: 'domcontentloaded' });
        await page.waitForTimeout(700);
        await page.locator('img').evaluateAll((images) => images.forEach((image) => { image.loading = 'eager'; }));
        await page.waitForTimeout(500);
        const brokenImages = await page.locator('img').evaluateAll((images) => images.filter((image) => image.naturalWidth === 0).map((image) => image.alt || image.src));
        const productImageSources = await page.locator('[data-product-card] img').evaluateAll((images) => images.map((image) => image.currentSrc || image.src));
        const duplicateProductImages = [...new Set(productImageSources.filter((source, index) => productImageSources.indexOf(source) !== index))];
        const overflow = await page.evaluate(() => document.documentElement.scrollWidth > window.innerWidth + 1);
        const slug = route === '/' ? 'home' : route.slice(1);
        await page.screenshot({ path: `${outputDir}/${slug}-${viewport.width}.png`, fullPage: true });
        results.push({ route, viewport: viewport.name, status: response?.status(), overflow, brokenImages, duplicateProductImages });
        if (route === '/') {
            await page.locator('[data-add-to-cart]').first().click();
            await page.locator('[data-wishlist]').first().click();
            const cartCount = await page.locator('.cart-count').textContent();
            const wishlistCount = await page.locator('.header-wishlist-count').textContent();
            const wishlistVisible = await page.locator('.header-wishlist-count').isVisible();
            await page.locator('[data-wishlist]').first().click();
            const wishlistCleared = await page.locator('.header-wishlist-count').isHidden();
            results.push({ route, viewport: viewport.name, interaction: 'cart + wishlist', cartCount, wishlistCount, wishlistVisible, wishlistCleared });
            await page.locator('[data-newsletter-form] input[type="email"]').fill('audit@luxora.test');
            await page.locator('[data-newsletter-form]').evaluate((form) => form.requestSubmit());
            results.push({ route, viewport: viewport.name, interaction: 'newsletter', visible: await page.locator('.toast.is-visible').count() === 1 });
            if (viewport.width === 1440) {
                const navigationChecks = [];
                for (const target of [
                    { path: '/shop', selector: '.site-nav a[href$="/shop"]' },
                    { path: '/about', selector: '.announcement-links a[href$="/about"]' },
                ]) {
                    await page.goto('http://127.0.0.1:8000/', { waitUntil: 'domcontentloaded' });
                    const navigation = page.waitForNavigation({ waitUntil: 'domcontentloaded' });
                    await page.locator(target.selector).click();
                    const navigationResponse = await navigation;
                    navigationChecks.push({ path: target.path, status: navigationResponse?.status(), landed: new URL(page.url()).pathname });
                }
                results.push({ route, viewport: viewport.name, interaction: 'navigation', navigationChecks });
                await page.goto('http://127.0.0.1:8000/', { waitUntil: 'domcontentloaded' });
            }
        }
        if (route === '/shop' && viewport.width >= 801) {
            await page.locator('[data-category-filter="Beauty"]').click();
            const beautyVisible = await page.locator('[data-product-card]:visible').count();
            await page.locator('[data-category-filter="all"]').click();
            const allVisible = await page.locator('[data-product-card]:visible').count();
            await page.locator('.filter-group input').first().check();
            const sizeFilterChecked = await page.locator('.filter-group input').first().isChecked();
            await page.locator('[data-clear-filters]').click();
            const cleared = !await page.locator('.filter-group input').first().isChecked();
            await page.locator('[data-sort-products]').selectOption('price-asc');
            const lowToHigh = await page.locator('[data-product-card]:visible h3').first().textContent();
            await page.locator('[data-sort-products]').selectOption('price-desc');
            const highToLow = await page.locator('[data-product-card]:visible h3').first().textContent();
            await page.locator('[data-sort-products]').selectOption('newest');
            const newest = await page.locator('[data-product-card]:visible h3').first().textContent();
            await page.locator('[data-pagination-page="2"]').click();
            const pageTwo = await page.locator('[data-pagination-page="2"]').getAttribute('aria-current');
            await page.locator('[data-pagination-next]').click();
            const pageThree = await page.locator('[data-pagination-page="3"]').getAttribute('aria-current');
            await page.locator('[data-pagination-previous]').click();
            const pageTwoAgain = await page.locator('[data-pagination-page="2"]').getAttribute('aria-current');
            results.push({ route, viewport: viewport.name, interaction: 'filters + sorting + pagination', beautyVisible, allVisible, sizeFilterChecked, cleared, lowToHigh, highToLow, newest, pageTwo, pageThree, pageTwoAgain });
        }
        if (route === '/' && viewport.width < 801) {
            await page.locator('.menu-toggle').click();
            const open = await page.locator('.site-nav.is-open').count() === 1;
            await page.locator('.menu-toggle').click();
            const closed = await page.locator('.site-nav.is-open').count() === 0;
            results.push({ route, viewport: viewport.name, interaction: 'mobile menu', open, closed });
        }
        await page.close();
    }
}

await browser.close();
const routeChecks = results.filter((entry) => entry.status !== undefined);
const auditPassed = consoleErrors.length === 0 && failures.length === 0 && routeChecks.every((entry) => entry.status === 200 && !entry.overflow && entry.brokenImages.length === 0 && entry.duplicateProductImages.length === 0);
const report = { generatedAt: new Date().toISOString(), auditPassed, results, consoleErrors, imageRequestFailures: failures };
fs.writeFileSync(`${outputDir}/report.json`, JSON.stringify(report, null, 2));
console.log(JSON.stringify(report, null, 2));
