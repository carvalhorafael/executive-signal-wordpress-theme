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

const headerSearchField = document.querySelector(".es-header-search__field");

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

enhanceArticleFAQ();
