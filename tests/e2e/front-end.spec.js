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
  coursesPageSlug: "cursos",
  courseCategorySlugs: [
    "e2e-course-strategy",
    "e2e-course-operations",
    "e2e-course-sales",
    "e2e-course-leadership",
  ],
  courseSlugs: [
    "e2e-course-signal-strategy",
    "e2e-course-operating-rhythm",
    "e2e-course-sales-system",
    "e2e-course-leadership-dashboard",
    "e2e-course-execution-review",
  ],
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
  tryWpCli(["plugin", "activate", "online-courses"]);
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

  const existingCoursesPageId = tryWpCli(["post", "list", "--post_type=page", `--name=${fixture.coursesPageSlug}`, "--field=ID"]);

  if (existingCoursesPageId) {
    runWpCli([
      "post",
      "update",
      existingCoursesPageId,
      "--post_status=publish",
      "--post_title=Cursos",
      "--post_content=Programas online para transformar sinais executivos em rotina de gestão.",
    ]);
  } else {
    runWpCli([
      "post",
      "create",
      "--post_type=page",
      "--post_status=publish",
      `--post_name=${fixture.coursesPageSlug}`,
      "--post_title=Cursos",
      "--post_content=Programas online para transformar sinais executivos em rotina de gestão.",
      "--porcelain",
    ]);
  }

  const coursesPageId = runWpCli(["post", "list", "--post_type=page", `--name=${fixture.coursesPageSlug}`, "--field=ID"]);
  runWpCli(["post", "meta", "update", coursesPageId, "_wp_page_template", "page-cursos.php"]);

  tryWpCli(["term", "create", "category", "E2E Theme", "--slug=e2e-theme"]);
  tryWpCli(["term", "create", "post_tag", "E2E Tag", "--slug=e2e-tag"]);
  fixture.courseCategorySlugs.forEach((slug) => {
    deleteTermsBySlug("course_category", slug);
    deleteTermsBySlug("course_category", `${slug}-2`);
  });
  deleteTermsBySlug("material_categoria", fixture.materialCategorySlug);
  deleteTermsBySlug("material_categoria", `${fixture.materialCategorySlug}-2`);
  deleteTermsBySlug("material_categoria", fixture.extraMaterialCategorySlug);
  deleteTermsBySlug("material_categoria", `${fixture.extraMaterialCategorySlug}-2`);
  deleteTermsBySlug("material_categoria", "80");
  tryWpCli(["term", "create", "material_categoria", "E2E Materials", "--slug=e2e-materials"]);
  tryWpCli(["term", "create", "material_categoria", "E2E Extra Materials", "--slug=e2e-extra-materials"]);
  tryWpCli(["term", "create", "course_category", "E2E Estratégia", "--slug=e2e-course-strategy"]);
  tryWpCli(["term", "create", "course_category", "E2E Operações", "--slug=e2e-course-operations"]);
  tryWpCli(["term", "create", "course_category", "E2E Vendas", "--slug=e2e-course-sales"]);
  tryWpCli(["term", "create", "course_category", "E2E Liderança", "--slug=e2e-course-leadership"]);

  const categoryId = runWpCli(["term", "get", "category", fixture.categorySlug, "--by=slug", "--field=term_id"]);
  const courseCategoryIds = Object.fromEntries(
    fixture.courseCategorySlugs.map((slug) => [
      slug,
      runWpCli(["term", "get", "course_category", slug, "--by=slug", "--field=term_id"]),
    ]),
  );
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

  const courses = [
    {
      slug: "e2e-course-signal-strategy",
      title: "E2E Signal Strategy",
      excerpt: "Curso fixture para decisões estratégicas com sinais executivos.",
      content:
        "<h2>O que você vai organizar</h2><p>Conteúdo fixture do curso para validar a página individual de cursos.</p><h2>Como aplicar</h2><p>Use sinais executivos para reduzir ruído e priorizar decisões.</p>",
      category: "e2e-course-strategy",
      checkout: "https://checkout.example.com/signal-strategy",
    },
    {
      slug: "e2e-course-operating-rhythm",
      title: "E2E Operating Rhythm",
      excerpt: "Curso fixture para cadência operacional e rituais de gestão.",
      content: "Conteúdo fixture do curso E2E Operating Rhythm.",
      category: "e2e-course-operations",
      checkout: "https://checkout.example.com/operating-rhythm",
    },
    {
      slug: "e2e-course-sales-system",
      title: "E2E Sales System",
      excerpt: "Curso fixture para gestão comercial orientada por indicadores.",
      content: "Conteúdo fixture do curso E2E Sales System.",
      category: "e2e-course-sales",
      checkout: "https://checkout.example.com/sales-system",
    },
    {
      slug: "e2e-course-leadership-dashboard",
      title: "E2E Leadership Dashboard",
      excerpt: "Curso fixture para leitura executiva de dashboards.",
      content: "Conteúdo fixture do curso E2E Leadership Dashboard.",
      category: "e2e-course-leadership",
      checkout: "https://checkout.example.com/leadership-dashboard",
    },
    {
      slug: "e2e-course-execution-review",
      title: "E2E Execution Review",
      excerpt: "Curso fixture para revisar execução e remover ruídos de gestão.",
      content: "Conteúdo fixture do curso E2E Execution Review.",
      category: "e2e-course-operations",
      checkout: "https://checkout.example.com/execution-review",
    },
  ];

  courses.forEach((course) => {
    const existingCourseId = tryWpCli(["post", "list", "--post_type=course", `--name=${course.slug}`, "--field=ID"]);
    const courseId =
      existingCourseId ||
      runWpCli([
        "post",
        "create",
        "--post_type=course",
        "--post_status=publish",
        `--post_name=${course.slug}`,
        `--post_title=${course.title}`,
        `--post_excerpt=${course.excerpt}`,
        `--post_content=${course.content}`,
        "--porcelain",
      ]);

    runWpCli([
      "post",
      "update",
      courseId,
      "--post_status=publish",
      `--post_title=${course.title}`,
      `--post_excerpt=${course.excerpt}`,
      `--post_content=${course.content}`,
    ]);
    runWpCli([
      "eval",
      `wp_set_object_terms(${Number(courseId)}, array(${Number(courseCategoryIds[course.category])}), 'course_category', false);`,
    ]);
    runWpCli(["post", "meta", "update", courseId, "_online_courses_checkout_url", course.checkout]);

    if (!tryWpCli(["post", "meta", "get", courseId, "_thumbnail_id"])) {
      runWpCli([
        "media",
        "import",
        "/var/www/html/wp-content/themes/executive-signal-wordpress-theme/screenshot.png",
        `--post_id=${courseId}`,
        `--title=${course.title} Image`,
        "--featured_image",
        "--porcelain",
      ]);
    }
  });

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

  test("renders courses listing with editable page copy and category filters", async ({ page }) => {
    await page.goto("/cursos/");
    await expect(page.locator("body")).toHaveClass(/page-template-page-cursos/);
    await expect(page.locator(".es-blog-archive-header__eyebrow")).toHaveText("Cursos");
    await expect(page.locator(".es-blog-archive-header__title")).toHaveText("Cursos");
    await expect(page.locator(".es-blog-archive-header__description")).toContainText(
      "Programas online para transformar sinais executivos em rotina de gestão.",
    );
    await expect(page.locator(".es-resource-browser__filters")).toBeVisible();
    await expect(page.locator(".course-card")).toHaveCount(5);
    await expect(page.locator(".course-card", { hasText: "E2E Signal Strategy" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Operating Rhythm" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Sales System" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Leadership Dashboard" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Execution Review" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Signal Strategy" })).toContainText("Ver curso");
    await expect(page.locator(".course-card__checkout")).toHaveCount(0);

    await page.locator('[data-es-resource-filter][value="e2e-course-operations"]').check();
    await expect(page.locator(".course-card", { hasText: "E2E Operating Rhythm" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Execution Review" })).toBeVisible();
    await expect(page.locator(".course-card", { hasText: "E2E Signal Strategy" })).toBeHidden();

    await page.locator('[data-es-resource-filter][value="e2e-course-sales"]').check();
    await expect(page.locator(".course-card", { hasText: "E2E Sales System" })).toBeVisible();

    await page.locator("[data-es-resource-clear]").click();
    await expect(page.locator(".course-card")).toHaveCount(5);
  });

  test("renders single course page with existing design-system components", async ({ page }) => {
    await page.goto(`/cursos/${fixture.courseSlugs[0]}/`);
    await expect(page.locator('article[itemtype="https://schema.org/Course"]')).toBeVisible();
    await expect(page.locator(".es-sales-hero__eyebrow")).toHaveText("Curso online");
    await expect(page.locator(".es-sales-hero__title")).toHaveText("E2E Signal Strategy");
    await expect(page.locator(".es-sales-hero__description")).toContainText(
      "Curso fixture para decisões estratégicas com sinais executivos.",
    );
    await expect(page.locator(".course-single__hero-visual img")).toBeVisible();
    await expect(page.locator(".es-event-info-strip")).toContainText("Visão geral do curso");
    await expect(page.locator(".es-event-info-strip")).toContainText("E2E Estratégia");
    await expect(page.locator(".es-offer-band")).toContainText("Comece este curso quando estiver pronto.");
    await expect(page.locator(".es-offer-band .es-button")).toHaveAttribute(
      "href",
      "https://checkout.example.com/signal-strategy",
    );
    await expect(page.locator(".course-single__topics .es-nav-link")).toContainText("E2E Estratégia");
    await expect(page.locator("#course-content")).toContainText("Sobre este curso");
    await expect(page.locator(".es-article-prose h2").first()).toHaveText("O que você vai organizar");
    await expect(page.locator(".es-course-enrollment")).toHaveCount(0);
    await expect(page.locator(".es-course-curriculum")).toHaveCount(0);
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
