import "./styles/main.css";
import { enhanceArticleFAQ, enhanceBlogSiteHeader } from "@carvalhorafael/executive-signal-web/behavior";

enhanceBlogSiteHeader({ storageKey: "executive-signal-theme" });

document.querySelectorAll("[data-copy-link]").forEach((button) => {
  const label = button.querySelector("[data-copy-link-label]");
  const copyLabel = button.dataset.copyLabel ?? "";
  const copiedLabel = button.dataset.copiedLabel ?? copyLabel;
  const copyToClipboard = async (url) => {
    if (window.navigator.clipboard?.writeText) {
      await window.navigator.clipboard.writeText(url);
      return;
    }

    const field = document.createElement("textarea");
    field.value = url;
    field.setAttribute("readonly", "readonly");
    field.style.position = "fixed";
    field.style.opacity = "0";
    document.body.append(field);
    field.select();
    document.execCommand("copy");
    field.remove();
  };

  button.addEventListener("click", async () => {
    const url = button.dataset.copyLink;

    if (!url) {
      return;
    }

    try {
      await copyToClipboard(url);

      if (label) {
        label.textContent = copiedLabel;
      }

      window.setTimeout(() => {
        if (label) {
          label.textContent = copyLabel;
        }
      }, 1800);
    } catch {
      if (label) {
        label.textContent = copyLabel;
      }
    }
  });
});

document.querySelectorAll("[data-free-material-browser]").forEach((browser) => {
  const filters = Array.from(browser.querySelectorAll("[data-free-material-filter]"));
  const cards = Array.from(browser.querySelectorAll("[data-free-material-card]"));
  const empty = browser.querySelector("[data-free-material-empty]");
  const clear = browser.querySelector("[data-free-material-clear]");

  const applyFilters = () => {
    const selected = filters.filter((filter) => filter.checked).map((filter) => filter.value);
    let visibleCount = 0;

    cards.forEach((card) => {
      const categories = (card.dataset.categories ?? "").split(/\s+/).filter(Boolean);
      const isVisible = selected.length === 0 || selected.some((category) => categories.includes(category));

      card.hidden = !isVisible;

      if (isVisible) {
        visibleCount += 1;
      }
    });

    if (empty) {
      empty.hidden = visibleCount !== 0;
    }
  };

  filters.forEach((filter) => {
    filter.addEventListener("change", applyFilters);
  });

  clear?.addEventListener("click", () => {
    filters.forEach((filter) => {
      filter.checked = false;
    });

    applyFilters();
  });

  applyFilters();
});

enhanceArticleFAQ();
