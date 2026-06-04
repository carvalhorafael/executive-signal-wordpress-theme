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

enhanceArticleFAQ();
