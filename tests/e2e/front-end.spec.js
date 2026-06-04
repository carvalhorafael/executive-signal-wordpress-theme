import AxeBuilder from "@axe-core/playwright";
import { expect, test } from "@playwright/test";
import { execFileSync } from "node:child_process";

const runWpCli = (args) => {
  return execFileSync("npx", ["wp-env", "run", "cli", "wp", ...args], {
    encoding: "utf8",
    stdio: "pipe",
  }).trim();
};

const tryWpCli = (args) => {
  try {
    return runWpCli(args);
  } catch {
    return "";
  }
};

const fixture = {
  categorySlug: "e2e-theme",
  menuName: "E2E Primary",
  postSlug: "e2e-theme-article",
  siblingSlug: "e2e-theme-related",
};

test.beforeAll(() => {
  runWpCli(["theme", "activate", "executive-signal-wordpress-theme"]);
  tryWpCli(["language", "core", "install", "pt_BR"]);
  runWpCli(["option", "update", "WPLANG", "pt_BR"]);
  runWpCli(["rewrite", "structure", "/%postname%/", "--hard"]);
  runWpCli(["rewrite", "flush", "--hard"]);

  tryWpCli(["term", "create", "category", "E2E Theme", "--slug=e2e-theme"]);
  tryWpCli(["term", "create", "post_tag", "E2E Tag", "--slug=e2e-tag"]);

  const categoryId = runWpCli(["term", "get", "category", fixture.categorySlug, "--by=slug", "--field=term_id"]);
  const existingPostId = tryWpCli(["post", "list", "--post_type=post", `--name=${fixture.postSlug}`, "--field=ID"]);
  const postId =
    existingPostId ||
    runWpCli([
      "post",
      "create",
      "--post_type=post",
      "--post_status=publish",
      `--post_name=${fixture.postSlug}`,
      "--post_title=E2E Theme Article",
      "--post_excerpt=Article fixture for theme surface tests.",
      "--post_content=Fixture content for the article template.",
      `--post_category=${categoryId}`,
      "--porcelain",
    ]);

  tryWpCli(["post", "term", "add", postId, "post_tag", "e2e-tag"]);

  const existingMenuId = tryWpCli(["term", "list", "nav_menu", `--name=${fixture.menuName}`, "--field=term_id"]);

  if (existingMenuId) {
    tryWpCli(["menu", "delete", existingMenuId]);
  }

  runWpCli(["menu", "create", fixture.menuName]);

  const menuId = runWpCli(["term", "list", "nav_menu", `--name=${fixture.menuName}`, "--field=term_id"]);
  runWpCli(["menu", "item", "add-custom", menuId, "Início", "/", "--porcelain"]);
  runWpCli(["menu", "item", "add-custom", menuId, "Blog", "/blog/", "--porcelain"]);

  const parentMenuItemId = runWpCli([
    "menu",
    "item",
    "add-custom",
    menuId,
    "Gestão",
    `/category/${fixture.categorySlug}/`,
    "--porcelain",
  ]);

  runWpCli([
    "menu",
    "item",
    "add-custom",
    menuId,
    "Negócios",
    `/category/${fixture.categorySlug}/`,
    `--parent-id=${parentMenuItemId}`,
    "--porcelain",
  ]);

  runWpCli(["menu", "location", "assign", menuId, "primary"]);

  const existingSiblingId = tryWpCli(["post", "list", "--post_type=post", `--name=${fixture.siblingSlug}`, "--field=ID"]);

  if (!existingSiblingId) {
    runWpCli([
      "post",
      "create",
      "--post_type=post",
      "--post_status=publish",
      `--post_name=${fixture.siblingSlug}`,
      "--post_title=E2E Related Article",
      "--post_content=Related fixture content.",
      `--post_category=${categoryId}`,
      "--porcelain",
    ]);
  }
});

const expectNoAxeViolations = async (page) => {
  const results = await new AxeBuilder({ page })
    .withTags(["wcag2a", "wcag2aa", "wcag21a", "wcag21aa"])
    .analyze();

  expect(results.violations).toEqual([]);
};

test.describe("Executive Signal theme front end", () => {
  test("renders the homepage shell and loads built assets", async ({ page }) => {
    const consoleErrors = [];
    page.on("console", (message) => {
      if (message.type() === "error") {
        consoleErrors.push(message.text());
      }
    });

    await page.goto("/");

    await expect(page.locator("[data-es-blog-site-header]")).toBeVisible();
    await expect(page.locator("html")).toHaveAttribute("data-es-theme", "light");
    await expect(page.locator("html")).toHaveAttribute("data-es-palette", "signal");
    await expect(page.locator("[data-es-theme-switcher]")).toBeVisible();
    await expect(page.locator("[data-es-header-search]")).toBeVisible();
    await expect(page.locator('link[href*="assets/dist/assets/main-"]')).toHaveCount(1);
    await expect(page.locator('script[src*="assets/dist/assets/main-"]')).toHaveCount(1);
    expect(consoleErrors).toEqual([]);
  });

  test("passes automated accessibility smoke checks", async ({ page }) => {
    await page.goto("/");

    await expectNoAxeViolations(page);
  });

  test("renders archive, 404 and single post editorial surfaces", async ({ page }) => {
    await page.goto("/rota-inexistente-404/");
    await expect(page.locator(".es-empty-state__title")).toHaveText("Página não encontrada");
    await expect(page.locator(".es-empty-state .search-form")).toBeVisible();

    await page.goto(`/category/${fixture.categorySlug}/`);
    await expect(page.locator(".es-blog-archive-header__eyebrow")).toHaveText("Categoria");
    await expect(page.locator(".es-article-card").first()).toBeVisible();

    await page.goto(`/${fixture.postSlug}/`);
    await expect(page.locator('article[itemtype="https://schema.org/BlogPosting"]')).toBeVisible();
    await expect(page.locator(".es-article-tags")).toBeVisible();
    await expect(page.locator(".es-social-share-bar--article")).toBeVisible();
    await expect(page.locator(".es-post-navigation")).toBeVisible();
    await expect(page.locator(".es-related-articles")).toBeVisible();
  });

  test("opens mobile navigation and submenus", async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 900 });
    await page.goto("/");

    const header = page.locator("[data-es-blog-site-header]");
    const mobileToggle = page.locator(".es-blog-site-header__menu-toggle");
    const mobileNav = page.locator(".es-blog-site-header__nav");

    await expect(mobileToggle).toBeVisible();
    await expect(mobileNav).toBeHidden();

    await mobileToggle.click();
    await expect(header).toHaveAttribute("data-mobile-open", "true");
    await expect(mobileNav).toBeVisible();

    const submenuToggle = page.locator(".es-blog-site-header__submenu-toggle").first();

    if (await submenuToggle.count()) {
      await submenuToggle.click();
      await expect(submenuToggle).toHaveAttribute("aria-expanded", "true");
    }
  });
});
