import { expect, test } from "@playwright/test";

test("opens the mobile navigation and its submenu", async ({ page }) => {
  await page.goto("/");

  const header = page.locator("[data-es-blog-site-header]");
  const menuToggle = page.locator(".es-blog-site-header__menu-toggle");
  const navigation = page.locator(".es-blog-site-header__nav");

  await expect(menuToggle).toBeVisible();
  await expect(navigation).toBeHidden();
  await menuToggle.click();
  await expect(header).toHaveAttribute("data-mobile-open", "true");
  await expect(navigation).toBeVisible();

  const submenuToggle = page.locator(".es-blog-site-header__submenu-toggle").first();
  await expect(submenuToggle).toBeVisible();
  await submenuToggle.click();
  await expect(submenuToggle).toHaveAttribute("aria-expanded", "true");
});
