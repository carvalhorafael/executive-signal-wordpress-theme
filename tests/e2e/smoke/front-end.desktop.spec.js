import AxeBuilder from "@axe-core/playwright";
import { expect, test } from "@playwright/test";

test.describe("required desktop smoke", () => {
  test("loads the homepage shell and compiled assets without console errors", async ({ page }) => {
    const consoleErrors = [];
    page.on("console", (message) => {
      if (message.type() === "error") {
        consoleErrors.push(message.text());
      }
    });

    await page.goto("/");

    await expect(page.locator("[data-es-blog-site-header]")).toBeVisible();
    await expect(page.locator('link[href*="assets/dist/assets/main-"]')).toHaveCount(1);
    await expect(page.locator('script[src*="assets/dist/assets/main-"]')).toHaveCount(1);
    expect(consoleErrors).toEqual([]);
  });

  test("passes a representative accessibility smoke check", async ({ page }) => {
    await page.goto("/");

    const results = await new AxeBuilder({ page })
      .exclude("#wpadminbar")
      .withTags(["wcag2a", "wcag2aa", "wcag21a", "wcag21aa"])
      .analyze();

    expect(results.violations).toEqual([]);
  });

  test("renders and submits the COO capture integration", async ({ page }) => {
    await page.route("**/wp-json/crm-leads-capture/v1/capture/coo-as-a-service/nonce", (route) =>
      route.fulfill({ contentType: "application/json", status: 200, body: JSON.stringify({ success: true, nonce: "e2e-coo-nonce" }) }),
    );
    await page.route("**/wp-json/crm-leads-capture/v1/capture/coo-as-a-service", (route) =>
      route.fulfill({ contentType: "application/json", status: 200, body: JSON.stringify({ success: true, message: "Recebi seu contexto." }) }),
    );

    await page.goto("/coo-as-a-service/?utm_source=e2e&utm_medium=playwright");

    const form = page.locator('[data-crm-leads-capture="coo-as-a-service"]');
    await expect(form).toBeVisible();
    await expect(form.locator('input[name="crm_leads_capture_profile"]')).toHaveValue("coo-as-a-service");
    await expect(form.locator('input[name="utm_source"]')).toHaveValue("e2e");
    await form.getByLabel("Nome").fill("Pessoa E2E");
    await form.getByLabel("E-mail corporativo").fill("pessoa@example.com");
    await form.getByLabel("Empresa", { exact: true }).fill("Empresa E2E");
    await form.getByLabel("Seu papel").selectOption("ceo");
    await form.getByLabel("Onde a operação mais depende de você hoje?").fill("Decisões ainda convergem para a liderança.");
    await form.getByRole("checkbox").check();
    await form.getByRole("button", { name: "Quero conversar sobre minha operação" }).click();

    await expect(form.locator("[data-crm-leads-capture-message]")).toContainText("Recebi seu contexto");
  });

  test("renders and submits the speaking capture integration", async ({ page }) => {
    await page.route("**/wp-json/crm-leads-capture/v1/capture/speaker-invitation/nonce", (route) =>
      route.fulfill({ contentType: "application/json", status: 200, body: JSON.stringify({ success: true, nonce: "e2e-speaker-nonce" }) }),
    );
    await page.route("**/wp-json/crm-leads-capture/v1/capture/speaker-invitation", (route) =>
      route.fulfill({ contentType: "application/json", status: 200, body: JSON.stringify({ success: true, message: "Recebi os detalhes do evento." }) }),
    );

    await page.goto("/palestras/?utm_source=e2e&utm_medium=playwright");

    const form = page.locator('[data-crm-leads-capture="speaker-invitation"]');
    await expect(form).toBeVisible();
    await expect(form.locator('input[name="crm_leads_capture_profile"]')).toHaveValue("speaker-invitation");
    await form.getByLabel("Nome", { exact: true }).fill("Pessoa E2E");
    await form.getByLabel("E-mail").fill("pessoa@example.com");
    await form.getByLabel("WhatsApp").fill("21999999999");
    await form.getByLabel("Empresa ou organização").fill("Organização E2E");
    await form.getByLabel("Qual é o objetivo do encontro?").fill("Provocar decisões melhores.");
    await form.getByLabel("Formato").selectOption("presencial");
    await form.getByRole("checkbox").check();
    await form.getByRole("button", { name: "Enviar convite para avaliação" }).click();

    await expect(form.locator("[data-crm-leads-capture-message]")).toContainText("Recebi os detalhes do evento");
  });

  test("renders the free-material capture contract", async ({ page }) => {
    await page.goto("/materiais-gratuitos/e2e-free-material/?utm_source=e2e&utm_medium=playwright");

    const form = page.locator('.es-resource-capture-panel form[action$="/wp-admin/admin-post.php"]');
    await expect(form).toBeVisible();
    await expect(form.locator('input[name="action"]')).toHaveValue("crm_leads_capture_free_material");
    await expect(form.locator('input[name="material_id"]')).not.toHaveValue("");
    await expect(form.locator('input[name="utm_source"]')).toHaveValue("e2e");
    await expect(form.locator('input[name="utm_medium"]')).toHaveValue("playwright");
    await expect(form.locator('input[name="_wpnonce"]')).toHaveCount(1);
  });
});
