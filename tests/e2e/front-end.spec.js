import AxeBuilder from "@axe-core/playwright";
import { expect, test } from "@playwright/test";
import { execFileSync } from "node:child_process";

const runWpCli = (args) => {
  return execFileSync("npx", ["wp-env", "run", "tests-cli", "wp", ...args], {
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

const deleteTermsBySlug = (taxonomy, slug) => {
  const termIds = tryWpCli(["term", "list", taxonomy, `--slug=${slug}`, "--field=term_id"]);

  termIds
    .split(/\s+/)
    .filter(Boolean)
    .forEach((termId) => {
      tryWpCli(["term", "delete", taxonomy, termId]);
    });
};

const fixture = {
  categorySlug: "e2e-theme",
  extraMaterialCategorySlug: "e2e-extra-materials",
  extraMaterialSlug: "e2e-extra-free-material",
  materialCategorySlug: "e2e-materials",
  materialSlug: "e2e-free-material",
  menuName: "E2E Primary",
  materialsPageSlug: "materiais-gratuitos",
  postSlug: "e2e-theme-article",
  siblingSlug: "e2e-theme-related",
};

const articleSections = [
  ["h2", "Primeira seção"],
  ["h3", "Detalhe interno"],
  ["h2", "Segundo bloco"],
  ["h2", "Terceiro bloco"],
  ["h3", "Ponto de apoio"],
  ["h2", "Quarto bloco"],
  ["h2", "Quinto bloco"],
  ["h3", "Sinal secundário"],
  ["h2", "Sexto bloco"],
  ["h2", "Sétimo bloco"],
  ["h3", "Nota operacional"],
  ["h2", "Oitavo bloco"],
  ["h2", "Nono bloco"],
  ["h3", "Fechamento interno"],
  ["h2", "Conclusão"],
];

const articleContent = [
  "<p>Fixture content for the article template.</p>",
  ...articleSections.map(([level, title]) => `<${level}>${title}</${level}><p>Texto de apoio para ${title}.</p>`),
].join("");

test.beforeAll(() => {
  tryWpCli(["plugin", "activate", "free-materials"]);
  runWpCli(["theme", "activate", "executive-signal-wordpress-theme"]);
  tryWpCli(["language", "core", "install", "pt_BR"]);
  runWpCli(["option", "update", "WPLANG", "pt_BR"]);
  runWpCli(["rewrite", "structure", "/%postname%/", "--hard"]);
  runWpCli(["rewrite", "flush", "--hard"]);

  const existingMaterialsPageId = tryWpCli(["post", "list", "--post_type=page", `--name=${fixture.materialsPageSlug}`, "--field=ID"]);

  if (existingMaterialsPageId) {
    runWpCli([
      "post",
      "update",
      existingMaterialsPageId,
      "--post_status=publish",
      "--post_title=Materiais Gratuitos",
      "--post_content=Descrição editável da página de materiais gratuitos.",
    ]);
  } else {
    runWpCli([
      "post",
      "create",
      "--post_type=page",
      "--post_status=publish",
      `--post_name=${fixture.materialsPageSlug}`,
      "--post_title=Materiais Gratuitos",
      "--post_content=Descrição editável da página de materiais gratuitos.",
      "--porcelain",
    ]);
  }

  tryWpCli(["term", "create", "category", "E2E Theme", "--slug=e2e-theme"]);
  tryWpCli(["term", "create", "post_tag", "E2E Tag", "--slug=e2e-tag"]);
  deleteTermsBySlug("material_categoria", fixture.materialCategorySlug);
  deleteTermsBySlug("material_categoria", `${fixture.materialCategorySlug}-2`);
  deleteTermsBySlug("material_categoria", fixture.extraMaterialCategorySlug);
  deleteTermsBySlug("material_categoria", `${fixture.extraMaterialCategorySlug}-2`);
  deleteTermsBySlug("material_categoria", "80");
  tryWpCli(["term", "create", "material_categoria", "E2E Materials", "--slug=e2e-materials"]);
  tryWpCli(["term", "create", "material_categoria", "E2E Extra Materials", "--slug=e2e-extra-materials"]);

  const categoryId = runWpCli(["term", "get", "category", fixture.categorySlug, "--by=slug", "--field=term_id"]);
  const materialCategoryId = runWpCli([
    "term",
    "get",
    "material_categoria",
    fixture.materialCategorySlug,
    "--by=slug",
    "--field=term_id",
  ]);
  const extraMaterialCategoryId = runWpCli([
    "term",
    "get",
    "material_categoria",
    fixture.extraMaterialCategorySlug,
    "--by=slug",
    "--field=term_id",
  ]);
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

  runWpCli([
    "post",
    "update",
    postId,
    `--post_content=${articleContent}`,
  ]);

  tryWpCli(["post", "term", "add", postId, "post_tag", "e2e-tag"]);

  const existingMaterialId = tryWpCli([
    "post",
    "list",
    "--post_type=material_gratuito",
    `--name=${fixture.materialSlug}`,
    "--field=ID",
  ]);
  const materialId =
    existingMaterialId ||
    runWpCli([
      "post",
      "create",
      "--post_type=material_gratuito",
      "--post_status=publish",
      `--post_name=${fixture.materialSlug}`,
      "--post_title=E2E Free Material",
      "--post_excerpt=Material fixture for free resource tests.",
      "--post_content=Fixture content for the material template.",
      "--porcelain",
    ]);

  runWpCli([
    "eval",
    `wp_set_object_terms(${Number(materialId)}, array(${Number(materialCategoryId)}), 'material_categoria', false);`,
  ]);
  runWpCli(["post", "meta", "update", materialId, "_executive_signal_material_capture_label", "Receive material"]);

  if (!tryWpCli(["post", "meta", "get", materialId, "_thumbnail_id"])) {
    runWpCli([
      "media",
      "import",
      "/var/www/html/wp-content/themes/executive-signal-wordpress-theme/screenshot.png",
      `--post_id=${materialId}`,
      "--title=E2E Free Material Image",
      "--featured_image",
      "--porcelain",
    ]);
  }

  const existingExtraMaterialId = tryWpCli([
    "post",
    "list",
    "--post_type=material_gratuito",
    `--name=${fixture.extraMaterialSlug}`,
    "--field=ID",
  ]);
  const extraMaterialId =
    existingExtraMaterialId ||
    runWpCli([
      "post",
      "create",
      "--post_type=material_gratuito",
      "--post_status=publish",
      `--post_name=${fixture.extraMaterialSlug}`,
      "--post_title=E2E Extra Free Material",
      "--post_excerpt=Second material fixture for category filter tests.",
      "--post_content=Fixture content for the second material template.",
      "--porcelain",
    ]);

  runWpCli([
    "eval",
    `wp_set_object_terms(${Number(extraMaterialId)}, array(${Number(extraMaterialCategoryId)}), 'material_categoria', false);`,
  ]);

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

  const leftWidgetId = tryWpCli(["widget", "list", "post-left", "--field=id"]);

  if (!leftWidgetId.includes("block-")) {
    runWpCli([
      "widget",
      "add",
      "block",
      "post-left",
      "--content=<h2>Widget esquerdo</h2><p>Conteúdo de apoio lateral.</p>",
    ]);
  }

  const rightWidgetId = tryWpCli(["widget", "list", "post-right", "--field=id"]);

  if (!rightWidgetId.includes("block-")) {
    runWpCli([
      "widget",
      "add",
      "block",
      "post-right",
      "--content=<h2>Widget direito</h2><p>Chamada complementar do artigo.</p>",
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
    await expect(page.locator(".es-blog-site-header-feed-link__icon")).toBeVisible();
    await expect(page.locator(".es-blog-theme-switcher__trigger-icon")).toBeVisible();
    await expect(page.locator('link[href*="assets/dist/assets/main-"]')).toHaveCount(1);
    await expect(page.locator('script[src*="assets/dist/assets/main-"]')).toHaveCount(1);
    await expect(page.locator(".es-blog-site-header-feed-link")).toHaveAttribute("aria-label", "Feed RSS");
    await expect(page.locator(".es-blog-theme-switcher")).toHaveAttribute("aria-label", "Tema");

    const iconControlSizes = await page.evaluate(() => {
      const feed = document.querySelector(".es-blog-site-header-feed-link").getBoundingClientRect();
      const theme = document.querySelector(".es-blog-theme-switcher__trigger").getBoundingClientRect();
      const feedLabel = document.querySelector(".es-blog-site-header-feed-link__label").getBoundingClientRect();
      const themeLabel = document.querySelector(".es-blog-theme-switcher__trigger-label").getBoundingClientRect();

      return {
        feedWidth: Math.round(feed.width),
        themeWidth: Math.round(theme.width),
        feedLabelWidth: Math.round(feedLabel.width),
        themeLabelWidth: Math.round(themeLabel.width),
      };
    });

    expect(iconControlSizes.feedWidth).toBeLessThanOrEqual(44);
    expect(iconControlSizes.themeWidth).toBeLessThanOrEqual(44);
    expect(iconControlSizes.feedLabelWidth).toBeLessThanOrEqual(1);
    expect(iconControlSizes.themeLabelWidth).toBeLessThanOrEqual(1);
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
    await expect(page.locator(".entry__body-layout")).toBeVisible();
    await expect(page.locator(".entry__widget-area--left")).toBeVisible();
    await expect(page.locator(".entry__widget-area--right")).toBeVisible();
    await expect(page.locator(".es-table-of-contents")).toBeVisible();
    await expect(page.locator(".es-table-of-contents")).toHaveAttribute("data-density", "compact");
    await expect(page.locator(".es-table-of-contents")).toHaveAttribute("data-scrollable", "true");
    await expect(page.locator(".es-table-of-contents__title")).toHaveText("Nesta página");
    const rightRailWidth = await page.locator(".entry__widget-area--right").evaluate((rail) => rail.getBoundingClientRect().width);
    const tocWidth = await page.locator(".es-table-of-contents").evaluate((toc) => toc.getBoundingClientRect().width);

    expect(rightRailWidth).toBeGreaterThan(250);
    expect(Math.round(tocWidth)).toBe(Math.round(rightRailWidth));

    await expect(page.locator('.es-table-of-contents__item[data-level="3"]')).toHaveCount(
      articleSections.filter(([level]) => level === "h3").length,
    );
    await expect(page.locator('.entry__content h2[id="primeira-secao"]')).toBeVisible();
    await expect(page.locator('.entry__content h3[id="detalhe-interno"]')).toBeVisible();
    await expect(page.locator(".es-table-of-contents__link")).toHaveCount(articleSections.length);

    const tocOverflow = await page.locator(".es-table-of-contents__list").evaluate((list) => {
      const toc = list.closest(".es-table-of-contents");
      const tocStyle = getComputedStyle(toc);

      return getComputedStyle(list).overflowY === "auto" && tocStyle.maxHeight !== "none";
    });
    const tocDensity = await page.locator(".es-table-of-contents").evaluate((toc) => {
      const list = toc.querySelector(".es-table-of-contents__list");
      const link = toc.querySelector(".es-table-of-contents__link");
      const listStyle = getComputedStyle(list);
      const linkStyle = getComputedStyle(link);

      return {
        listGap: Number.parseFloat(listStyle.rowGap),
        listMarginTop: Number.parseFloat(listStyle.marginTop),
        fontSize: Number.parseFloat(linkStyle.fontSize),
        fontWeight: Number.parseFloat(linkStyle.fontWeight),
        minHeight: Number.parseFloat(linkStyle.minHeight),
      };
    });

    expect(tocOverflow).toBe(true);
    expect(tocDensity.listGap).toBeLessThanOrEqual(3);
    expect(tocDensity.listMarginTop).toBe(0);
    expect(tocDensity.fontSize).toBeLessThanOrEqual(14);
    expect(tocDensity.fontWeight).toBeLessThanOrEqual(400);
    expect(tocDensity.minHeight).toBeLessThanOrEqual(32);
    await expect(page.locator(".es-article-tags")).toBeVisible();
    await expect(page.locator(".es-social-share-bar--article")).toBeVisible();
    await expect(page.locator(".es-post-navigation")).toBeVisible();
    await expect(page.locator(".es-related-articles")).toBeVisible();
  });

  test("renders free material archive, category and single capture surfaces", async ({ page }) => {
    const expectedMaterialId = runWpCli([
      "post",
      "list",
      "--post_type=material_gratuito",
      `--name=${fixture.materialSlug}`,
      "--field=ID",
    ]);

    await page.goto("/materiais-gratuitos/");
    await expect(page.locator(".es-blog-archive-header__eyebrow")).toHaveText("Materiais gratuitos");
    await expect(page.locator(".es-blog-archive-header__title")).toHaveText("Materiais Gratuitos");
    await expect(page.locator(".es-blog-archive-header__description")).toContainText(
      "Descrição editável da página de materiais gratuitos.",
    );
    await expect(page.locator(".es-resource-browser__filters")).toBeVisible();
    await expect(page.locator(".free-material-card", { hasText: "E2E Free Material" })).toBeVisible();
    await expect(page.locator(".free-material-card", { hasText: "E2E Extra Free Material" })).toBeVisible();

    await page.locator('[data-es-resource-filter][value="e2e-materials"]').check();
    await expect(page.locator(".free-material-card", { hasText: "E2E Free Material" })).toBeVisible();
    await expect(page.locator(".free-material-card", { hasText: "E2E Extra Free Material" })).toBeHidden();

    await page.locator('[data-es-resource-filter][value="e2e-extra-materials"]').check();
    await expect(page.locator(".free-material-card", { hasText: "E2E Extra Free Material" })).toBeVisible();

    await page.locator("[data-es-resource-clear]").click();
    await expect(page.locator(".free-material-card", { hasText: "E2E Free Material" })).toBeVisible();
    await expect(page.locator(".free-material-card", { hasText: "E2E Extra Free Material" })).toBeVisible();

    await page.goto(`/materiais-gratuitos/categoria/${fixture.materialCategorySlug}/`);
    await expect(page.locator(".es-blog-archive-header__eyebrow")).toHaveText("Categoria de material gratuito");
    await expect(page.locator(".free-material-card").first()).toContainText("E2E Free Material");

    await page.goto(
      `/materiais-gratuitos/${fixture.materialSlug}/?utm_source=e2e&utm_medium=playwright&utm_campaign=gap-2`,
    );
    await expect(page.locator('article[itemtype="https://schema.org/CreativeWork"]')).toBeVisible();
    await expect(page.locator(".free-material-terms")).toHaveCount(0);
    await expect(page.locator(".es-resource-capture-hero__visual")).toBeVisible();
    await expect(page.locator(".es-resource-capture-hero__description")).toHaveCount(0);
    await expect(page.locator(".es-resource-capture-hero__proof")).toHaveCount(0);
    await expect(page.locator(".es-resource-capture-hero")).not.toContainText(
      "Second material fixture for category filter tests.",
    );
    await expect(page.locator(".es-resource-capture-panel")).toContainText("Complete o formulário");
    await expect(page.locator(".es-resource-capture-panel")).toContainText(
      "para receber o material.",
    );
    const captureForm = page.locator('.es-resource-capture-panel form[action$="/wp-admin/admin-post.php"]');

    await expect(captureForm).toBeVisible();
    await expect(captureForm).toHaveAttribute("method", "post");
    await expect(captureForm.locator('input[name="action"]')).toHaveValue(
      "brevo_leads_capture_free_material",
    );
    await expect(captureForm.locator('input[name="_wpnonce"]')).toHaveCount(1);
    await expect(captureForm.locator('input[name="material_id"]')).toHaveValue(expectedMaterialId);
    await expect(captureForm.locator('input[name="brevo_leads_capture_website"]')).toHaveValue("");
    await expect(captureForm.locator('input[name="brevo_leads_capture_website"]')).toHaveAttribute(
      "tabindex",
      "-1",
    );
    await expect(captureForm.locator('input[name="utm_source"]')).toHaveValue("e2e");
    await expect(captureForm.locator('input[name="utm_medium"]')).toHaveValue("playwright");
    await expect(captureForm.locator('input[name="utm_campaign"]')).toHaveValue("gap-2");
    await expect(captureForm.locator('input[name="utm_term"]')).toHaveValue("");
    await expect(captureForm.locator('input[name="utm_content"]')).toHaveValue("");
    await expect(page.locator("#free-material-capture-name")).toBeVisible();
    await expect(page.locator("#free-material-capture-email")).toBeVisible();
    await expect(page.locator("#free-material-capture-whatsapp")).toBeVisible();
    await expect(page.locator(".es-resource-capture-panel button")).toHaveText("Receive material");
    await expect(page.locator(".es-resource-detail__title")).toHaveText(
      "Conhecimento aplicado para acelerar sua jornada e evitar erros caros.",
    );
    await expect(page.locator(".es-resource-final-cta")).toContainText(
      "Acesse o conteúdo e aplique as ideias hoje mesmo.",
    );
    await expect(page.locator(".es-resource-final-cta .es-button")).toHaveAttribute("href", "#capture");
    await page.locator(".es-resource-final-cta .es-button").scrollIntoViewIfNeeded();
    await page.locator(".es-resource-final-cta .es-button").click();
    await expect(page).toHaveURL(/#capture$/);
    await expect
      .poll(async () =>
        page.locator("#capture").evaluate((element) => Math.round(element.getBoundingClientRect().top)),
      )
      .toBeLessThanOrEqual(8);
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
