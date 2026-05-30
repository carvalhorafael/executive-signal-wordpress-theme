import { expect, test } from "@playwright/test";
import { execFileSync } from "node:child_process";

const runWpCli = (args) => {
  return execFileSync("npx", ["wp-env", "run", "cli", "wp", ...args], {
    encoding: "utf8",
    stdio: "pipe",
  }).trim();
};

test.beforeAll(() => {
  runWpCli(["theme", "activate", "executive-signal-wordpress-theme"]);
});

async function loginAsAdmin(page) {
  await page.goto("/wp-login.php", { waitUntil: "domcontentloaded" });

  if (!(await page.locator("#user_login").isVisible())) {
    await page.goto("/wp-admin/", { waitUntil: "domcontentloaded" });
    await expect(page.locator("#wpadminbar")).toBeVisible();
    return;
  }

  await page.locator("#user_login").fill("admin");
  await page.locator("#user_pass").fill("password");
  await page.locator("#wp-submit").click();
  await page.waitForLoadState("domcontentloaded");
  await page.goto("/wp-admin/", { waitUntil: "domcontentloaded" });
  await expect(page.locator("#wpadminbar")).toBeVisible();
}

test.describe("Executive Signal theme editor contracts", () => {
  test("exposes Executive Signal patterns to the block editor", async ({ page }) => {
    test.skip(test.info().project.name !== "chromium", "Editor smoke runs only on desktop.");

    await loginAsAdmin(page);
    await page.goto("/wp-admin/post-new.php");

    await page.waitForFunction(() => {
      const core = window.wp?.data?.select("core");
      const patterns = core?.getBlockPatterns?.();

      return Array.isArray(patterns) && patterns.some((pattern) => pattern.name === "executive-signal/hero");
    });

    const patternNames = await page.evaluate(() => {
      return window.wp.data
        .select("core")
        .getBlockPatterns()
        .map((pattern) => pattern.name)
        .filter(Boolean);
    });

    expect(patternNames).toEqual(
      expect.arrayContaining([
        "executive-signal/hero",
        "executive-signal/signal-grid",
        "executive-signal/report-preview",
        "executive-signal/cta",
        "executive-signal/landing-page",
      ]),
    );
  });
});
