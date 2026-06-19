import { expect, test } from "@playwright/test";
import { execFileSync } from "node:child_process";

const runWpCli = (args) => {
  return execFileSync("npx", ["wp-env", "run", "tests-cli", "wp", ...args], {
    encoding: "utf8",
    stdio: "pipe",
  }).trim();
};

test.beforeAll(() => {
  runWpCli(["theme", "activate", "executive-signal-wordpress-theme"]);
  runWpCli(["user", "update", "admin", "--role=administrator", "--user_pass=password"]);
});

async function loginAsAdmin(page) {
  const loginPath = "/wp-login.php";
  const adminPath = "/wp-admin/";

  for (let attempt = 1; attempt <= 3; attempt += 1) {
    await page.context().clearCookies();
    await page.goto(loginPath, { waitUntil: "domcontentloaded" });
    const credentials = {
      log: "admin",
      pwd: "password",
      "wp-submit": "Log In",
      redirect_to: new URL(adminPath, page.url()).toString(),
      testcookie: "1",
    };

    await page.request.post(loginPath, { form: credentials });
    await page.goto(adminPath, { waitUntil: "domcontentloaded" });

    if (!(await page.locator("#user_login").isVisible())) {
      await expect(page.locator("#wpadminbar")).toBeVisible();
      return;
    }
  }

  throw new Error("WordPress redirected back to the login form after repeated admin login attempts.");
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
