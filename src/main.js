import "./styles/main.css";
import { enhanceArticleFAQ } from "@carvalhorafael/executive-signal-web/behavior";

const header = document.querySelector("[data-site-header]");

if (header) {
  header.dataset.enhanced = "true";
}

const themeSwitcher = document.querySelector("[data-theme-switcher]");
const themeStorageKey = "executive-signal-theme";
const themeOptions = ["light", "dark", "system"];

const setThemeMode = (theme) => {
  if (!themeOptions.includes(theme)) {
    return;
  }

  document.documentElement.dataset.esTheme = theme;

  try {
    window.localStorage.setItem(themeStorageKey, theme);
  } catch {
    // Theme preference is optional; the page still updates for this session.
  }

  document.querySelectorAll("[data-theme-option]").forEach((option) => {
    const active = option.dataset.themeOption === theme;

    option.dataset.active = active ? "true" : "false";
    option.setAttribute("aria-checked", active ? "true" : "false");
  });

  const currentLabel = document.querySelector("[data-theme-switcher-current]");
  const selectedOption = document.querySelector(`[data-theme-option="${theme}"]`);

  if (currentLabel && selectedOption) {
    currentLabel.textContent = selectedOption.querySelector("span")?.textContent.trim() ?? "";
  }
};

if (themeSwitcher) {
  let storedTheme = "light";

  try {
    const candidate = window.localStorage.getItem(themeStorageKey);

    if (themeOptions.includes(candidate)) {
      storedTheme = candidate;
    }
  } catch {
    storedTheme = "light";
  }

  setThemeMode(storedTheme);

  themeSwitcher.addEventListener("click", (event) => {
    const option = event.target.closest("[data-theme-option]");

    if (!option) {
      return;
    }

    setThemeMode(option.dataset.themeOption);
    themeSwitcher.removeAttribute("open");
  });

  document.addEventListener("click", (event) => {
    if (!themeSwitcher.contains(event.target)) {
      themeSwitcher.removeAttribute("open");
    }
  });
}

const headerSearchField = document.querySelector(".es-header-search__field, .es-blog-site-header__actions .search-field");

if (headerSearchField) {
  document.addEventListener("keydown", (event) => {
    const target = event.target;
    const isEditableTarget =
      target instanceof HTMLElement &&
      (target.isContentEditable || ["INPUT", "TEXTAREA", "SELECT"].includes(target.tagName));

    if (isEditableTarget || event.key.toLowerCase() !== "k" || (!event.metaKey && !event.ctrlKey)) {
      return;
    }

    event.preventDefault();
    headerSearchField.focus();
    headerSearchField.select();
  });
}

document.querySelectorAll("[data-nav-submenu]").forEach((submenu) => {
  const toggle = submenu.querySelector(".es-blog-site-header__submenu-toggle");

  if (!toggle) {
    return;
  }

  toggle.addEventListener("click", () => {
    const shouldOpen = submenu.dataset.open !== "true";

    document.querySelectorAll("[data-nav-submenu]").forEach((item) => {
      item.dataset.open = "false";
      item.querySelector(".es-blog-site-header__submenu-toggle")?.setAttribute("aria-expanded", "false");
    });

    submenu.dataset.open = shouldOpen ? "true" : "false";
    toggle.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
  });
});

document.addEventListener("click", (event) => {
  if (event.target.closest("[data-nav-submenu]")) {
    return;
  }

  document.querySelectorAll("[data-nav-submenu]").forEach((submenu) => {
    submenu.dataset.open = "false";
    submenu.querySelector(".es-blog-site-header__submenu-toggle")?.setAttribute("aria-expanded", "false");
  });
});

const mobileNavToggle = document.querySelector("[data-mobile-nav-toggle]");
const mobileNav = document.querySelector("[data-mobile-nav]");

if (header && mobileNavToggle && mobileNav) {
  const mobileNavLabel = mobileNavToggle.querySelector("[data-mobile-nav-label]");
  const setMobileNavigation = (isOpen) => {
    header.dataset.mobileOpen = isOpen ? "true" : "false";
    mobileNavToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");

    const label = isOpen ? mobileNavToggle.dataset.closeLabel : mobileNavToggle.dataset.openLabel;

    if (mobileNavLabel && label) {
      mobileNavLabel.textContent = label;
    }

    if (!isOpen) {
      document.querySelectorAll("[data-nav-submenu]").forEach((submenu) => {
        submenu.dataset.open = "false";
        submenu.querySelector(".es-blog-site-header__submenu-toggle")?.setAttribute("aria-expanded", "false");
      });
    }
  };

  mobileNavToggle.addEventListener("click", () => {
    setMobileNavigation(header.dataset.mobileOpen !== "true");
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && header.dataset.mobileOpen === "true") {
      setMobileNavigation(false);
      mobileNavToggle.focus();
    }
  });

  document.addEventListener("click", (event) => {
    if (header.contains(event.target)) {
      return;
    }

    setMobileNavigation(false);
  });
}

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
