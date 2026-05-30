import "./styles/main.css";
import { enhanceArticleFAQ } from "@carvalhorafael/executive-signal-web/behavior";

const header = document.querySelector("[data-site-header]");

if (header) {
  header.dataset.enhanced = "true";
}

enhanceArticleFAQ();
