/**
 * End-to-end browser verification for the Conative Time site.
 * Requires the production server running (`npm run build && npm run start`).
 *
 *   node scripts/verify-e2e.mjs [baseUrl]
 *
 * Exits non-zero if any check fails. Screenshots land in scripts/e2e-shots/.
 */
import { chromium } from "playwright";
import fs from "node:fs";
import { fileURLToPath } from "node:url";

const baseUrl = process.argv[2] ?? "http://localhost:4567";
const shotsDir = fileURLToPath(new URL("./e2e-shots/", import.meta.url));
fs.mkdirSync(shotsDir, { recursive: true });

const routes = [
  { path: "/", expect: "The Joy Of Travel" },
  { path: "/about", expect: "Ana Carolina Villar" },
  { path: "/services", expect: "Our Services" },
  { path: "/services/travel-consultancy", expect: "Travel consultancy" },
  { path: "/blog", expect: "Our Blog" },
  { path: "/blog/saltos-de-jima", expect: "Saltos" },
  { path: "/contact", expect: "Contact Us" },
  { path: "/gallery", expect: "Sao Paulo" },
];

let failures = 0;
const fail = (msg) => {
  failures += 1;
  console.error(`FAIL  ${msg}`);
};
const pass = (msg) => console.log(`ok    ${msg}`);

const browser = await chromium.launch();
const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

for (const route of routes) {
  const consoleErrors = [];
  const pageErrors = [];
  const failedRequests = [];

  page.on("console", (msg) => {
    if (msg.type() === "error") consoleErrors.push(msg.text());
  });
  page.on("pageerror", (err) => pageErrors.push(String(err)));
  page.on("requestfailed", (req) =>
    failedRequests.push(`${req.url()} :: ${req.failure()?.errorText}`)
  );

  const response = await page.goto(`${baseUrl}${route.path}`, {
    waitUntil: "networkidle",
  });
  const status = response?.status() ?? 0;

  if (status !== 200) fail(`${route.path} returned HTTP ${status}`);
  else pass(`${route.path} HTTP 200`);

  const bodyText = await page.textContent("body");
  if (bodyText?.includes(route.expect)) pass(`${route.path} renders "${route.expect}"`);
  else fail(`${route.path} missing expected text "${route.expect}"`);

  // CSP violations surface as console errors; JS crashes as pageerrors.
  const cspViolations = consoleErrors.filter((e) =>
    /content security policy|refused to (load|execute)/i.test(e)
  );
  if (cspViolations.length > 0)
    fail(`${route.path} CSP violations: ${cspViolations[0]}`);
  if (pageErrors.length > 0) fail(`${route.path} pageerror: ${pageErrors[0]}`);
  else pass(`${route.path} no JS runtime errors`);

  // Aborted RSC prefetches are normal: the App Router cancels in-flight
  // prefetch requests when the user navigates elsewhere.
  const realFailures = failedRequests.filter(
    (r) => !r.includes("favicon") && !(r.includes("_rsc=") && r.includes("ERR_ABORTED"))
  );
  if (realFailures.length > 0) fail(`${route.path} failed request: ${realFailures[0]}`);

  await page.screenshot({
    path: `${shotsDir}${route.path === "/" ? "home" : route.path.replaceAll("/", "_")}.png`,
    fullPage: route.path === "/" ? false : true,
  });

  page.removeAllListeners("console");
  page.removeAllListeners("pageerror");
  page.removeAllListeners("requestfailed");
}

// Hero-specific: background image served by the API and the canvas animates.
await page.goto(`${baseUrl}/`, { waitUntil: "networkidle" });
const heroBg = await page.evaluate(() => {
  const el = document.querySelector('[style*="hero-image"]');
  return el ? getComputedStyle(el).backgroundImage : null;
});
if (heroBg?.includes("/api/hero-image/file?name="))
  pass(`hero background bound to ${heroBg.slice(0, 60)}…`);
else fail("hero background not bound to the hero-image API");

const canvasActive = await page.evaluate(
  () => document.querySelectorAll("canvas").length > 0
);
if (canvasActive) pass("AnomalousMatter canvas mounted");
else fail("AnomalousMatter canvas missing");

// Contact form: real end-to-end submit through the DOM.
await page.goto(`${baseUrl}/contact`, { waitUntil: "networkidle" });
await page.fill('input[name="name"]', "E2E Verification");
await page.fill('input[name="email"]', "e2e@example.com");
await page.fill(
  'textarea[name="message"]',
  "Automated end-to-end verification message."
);
await page.waitForTimeout(2500); // satisfy the server-side time-trap
await page.click('button[type="submit"]');
const sent = await page.waitForSelector("text=Message sent", {
  timeout: 8000,
});
if (sent) pass("contact form submitted end-to-end via DOM");
else fail("contact form did not reach success state");

await page.screenshot({ path: `${shotsDir}contact_success.png` });

// Footer anchors resolve to real targets on the home page.
await page.goto(`${baseUrl}/about`, { waitUntil: "networkidle" });
await page.click('footer >> text=Services');
await page.waitForURL(/\/#services$/, { timeout: 8000 });
pass("footer anchor navigates to /#services from /about");

await browser.close();

console.log(
  failures === 0
    ? `\nALL CHECKS PASSED (${routes.length} routes + hero + form + anchors)`
    : `\n${failures} CHECK(S) FAILED`
);
process.exit(failures === 0 ? 0 : 1);
