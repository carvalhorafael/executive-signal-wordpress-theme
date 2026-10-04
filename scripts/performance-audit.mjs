import { readFileSync } from "node:fs";
import { mkdir, writeFile } from "node:fs/promises";
import { basename, resolve } from "node:path";

const apiEndpoint = "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed";
const defaultCategories = ["performance", "accessibility", "best-practices", "seo"];
const defaultStrategies = ["mobile", "desktop"];
const reportDir = resolve("reports/performance");

const fieldMetricDefinitions = {
  LARGEST_CONTENTFUL_PAINT_MS: { label: "LCP", divisor: 1000, unit: "s", digits: 2 },
  INTERACTION_TO_NEXT_PAINT: { label: "INP", divisor: 1, unit: "ms", digits: 0 },
  CUMULATIVE_LAYOUT_SHIFT_SCORE: { label: "CLS", divisor: 100, unit: "", digits: 2 },
  FIRST_CONTENTFUL_PAINT_MS: { label: "FCP", divisor: 1000, unit: "s", digits: 2 },
  EXPERIMENTAL_TIME_TO_FIRST_BYTE: { label: "TTFB", divisor: 1, unit: "ms", digits: 0 },
};

function loadLocalEnv() {
  if (process.env.GOOGLE_PSI_API_KEY) {
    return;
  }

  try {
    const envFile = readFileSync(resolve(".env"), "utf8");

    for (const line of envFile.split(/\r?\n/)) {
      const trimmed = line.trim();

      if (!trimmed || trimmed.startsWith("#")) {
        continue;
      }

      const separator = trimmed.indexOf("=");

      if (separator === -1) {
        continue;
      }

      const key = trimmed.slice(0, separator).trim();
      const value = trimmed.slice(separator + 1).trim().replace(/^['"]|['"]$/g, "");

      if (key && process.env[key] === undefined) {
        process.env[key] = value;
      }
    }
  } catch {
    // The local .env file is optional.
  }
}

function parseArgs(argv) {
  const options = {
    categories: defaultCategories,
    strategies: defaultStrategies,
    locale: process.env.PERF_LOCALE || "pt-BR",
    urls: [],
  };

  for (const arg of argv) {
    if (arg.startsWith("--strategy=")) {
      options.strategies = arg.slice("--strategy=".length).split(",").filter(Boolean);
      continue;
    }

    if (arg.startsWith("--category=")) {
      options.categories = arg.slice("--category=".length).split(",").filter(Boolean);
      continue;
    }

    if (arg.startsWith("--locale=")) {
      options.locale = arg.slice("--locale=".length);
      continue;
    }

    options.urls.push(arg);
  }

  if (process.env.PERF_URLS) {
    options.urls.push(
      ...process.env.PERF_URLS.split(",").map((url) => url.trim()).filter(Boolean),
    );
  }

  return options;
}

function validateOptions(options) {
  if (options.urls.length === 0) {
    throw new Error(
      "Informe pelo menos uma URL pública. Exemplo: npm run perf:pagespeed -- https://www.exemplo.com/",
    );
  }

  for (const strategy of options.strategies) {
    if (!defaultStrategies.includes(strategy)) {
      throw new Error(`Estratégia inválida: ${strategy}. Use mobile, desktop ou mobile,desktop.`);
    }
  }

  for (const category of options.categories) {
    if (!defaultCategories.includes(category)) {
      throw new Error(`Categoria inválida: ${category}.`);
    }
  }

  for (const url of options.urls) {
    const parsed = new URL(url);

    if (!["http:", "https:"].includes(parsed.protocol)) {
      throw new Error(`URL inválida para PageSpeed Insights: ${url}`);
    }
  }
}

function categoryScore(category) {
  return typeof category?.score === "number" ? Math.round(category.score * 100) : null;
}

function auditValue(lhr, id) {
  const audit = lhr.audits?.[id];

  if (!audit) {
    return null;
  }

  return {
    id,
    title: audit.title,
    displayValue: audit.displayValue || "",
    numericValue: typeof audit.numericValue === "number" ? audit.numericValue : null,
    score: typeof audit.score === "number" ? audit.score : null,
  };
}

function fieldMetric(metricId, metric) {
  const definition = fieldMetricDefinitions[metricId];

  if (!definition || typeof metric?.percentile !== "number") {
    return null;
  }

  const value = metric.percentile / definition.divisor;

  return {
    id: metricId,
    label: definition.label,
    value,
    displayValue: `${value.toFixed(definition.digits)}${definition.unit ? ` ${definition.unit}` : ""}`,
    category: metric.category || null,
  };
}

function summarizeFieldData(result) {
  const urlExperience = result.loadingExperience;
  const originExperience = result.originLoadingExperience;
  const hasUrlMetrics = Object.keys(urlExperience?.metrics || {}).length > 0;
  const hasOriginMetrics = Object.keys(originExperience?.metrics || {}).length > 0;
  const experience = hasUrlMetrics ? urlExperience : hasOriginMetrics ? originExperience : null;

  if (!experience) {
    return {
      source: "unavailable",
      id: null,
      overallCategory: null,
      metrics: [],
    };
  }

  return {
    source: hasUrlMetrics && !experience.origin_fallback ? "url" : "origin",
    id: experience.id || experience.initial_url || null,
    overallCategory: experience.overall_category || null,
    metrics: Object.entries(experience.metrics || {})
      .map(([metricId, metric]) => fieldMetric(metricId, metric))
      .filter(Boolean),
  };
}

function summarizeResources(lhr) {
  const items = lhr.audits?.["resource-summary"]?.details?.items;

  if (!Array.isArray(items)) {
    return [];
  }

  return items.map((item) => ({
    resourceType: item.resourceType || "unknown",
    requestCount: item.requestCount || 0,
    transferSize: item.transferSize || 0,
  }));
}

function summarizeThirdParties(lhr) {
  const items = lhr.audits?.["third-party-summary"]?.details?.items;

  if (!Array.isArray(items)) {
    return [];
  }

  return items
    .filter((item) => item.entity)
    .map((item) => ({
      entity: item.entity,
      transferSize: item.transferSize || 0,
      mainThreadTime: item.mainThreadTime || 0,
      blockingTime: item.blockingTime || 0,
    }))
    .sort((a, b) => b.transferSize - a.transferSize)
    .slice(0, 10);
}

function summarize(result) {
  const lhr = result.lighthouseResult || {};
  const categories = lhr.categories || {};
  const metrics = [
    "first-contentful-paint",
    "largest-contentful-paint",
    "cumulative-layout-shift",
    "total-blocking-time",
    "speed-index",
    "interactive",
    "server-response-time",
  ].map((id) => auditValue(lhr, id)).filter(Boolean);

  const opportunities = Object.values(lhr.audits || {})
    .filter(
      (audit) =>
        audit?.details?.type === "opportunity" &&
        typeof audit.score === "number" &&
        audit.score < 0.9,
    )
    .map((audit) => ({
      id: audit.id,
      title: audit.title,
      displayValue: audit.displayValue || "",
      numericValue: typeof audit.numericValue === "number" ? audit.numericValue : null,
      score: audit.score,
    }))
    .sort((a, b) => (b.numericValue || 0) - (a.numericValue || 0))
    .slice(0, 12);

  const failingAudits = Object.values(lhr.audits || {})
    .filter((audit) => audit && typeof audit.score === "number" && audit.score < 0.9)
    .map((audit) => ({
      id: audit.id,
      title: audit.title,
      displayValue: audit.displayValue || "",
      score: audit.score,
    }))
    .slice(0, 30);

  return {
    requestedUrl: lhr.requestedUrl,
    finalDisplayedUrl: lhr.finalDisplayedUrl || lhr.finalUrl,
    fetchTime: lhr.fetchTime,
    lighthouseVersion: lhr.lighthouseVersion || null,
    categories: {
      performance: categoryScore(categories.performance),
      accessibility: categoryScore(categories.accessibility),
      bestPractices: categoryScore(categories["best-practices"]),
      seo: categoryScore(categories.seo),
    },
    metrics,
    fieldData: summarizeFieldData(result),
    resources: summarizeResources(lhr),
    thirdParties: summarizeThirdParties(lhr),
    opportunities,
    failingAudits,
    warnings: lhr.runWarnings || [],
  };
}

function buildApiUrl(url, strategy, options) {
  const apiUrl = new URL(apiEndpoint);
  apiUrl.searchParams.set("url", url);
  apiUrl.searchParams.set("strategy", strategy);
  apiUrl.searchParams.set("locale", options.locale);

  for (const category of options.categories) {
    apiUrl.searchParams.append("category", category);
  }

  if (process.env.GOOGLE_PSI_API_KEY) {
    apiUrl.searchParams.set("key", process.env.GOOGLE_PSI_API_KEY);
  }

  return apiUrl;
}

async function runOne(url, strategy, options) {
  const response = await fetch(buildApiUrl(url, strategy, options));
  const body = await response.text();

  if (!response.ok) {
    const hint = response.status === 429 && !process.env.GOOGLE_PSI_API_KEY
      ? " Configure GOOGLE_PSI_API_KEY para usar a cota do projeto Google Cloud."
      : "";

    throw new Error(
      `PageSpeed Insights falhou para ${url} (${strategy}): HTTP ${response.status}.${hint} ${body.slice(0, 500)}`,
    );
  }

  const raw = JSON.parse(body);

  return {
    url,
    strategy,
    analyzedAt: new Date().toISOString(),
    summary: summarize(raw),
    raw,
  };
}

function slugifyUrl(url) {
  const parsed = new URL(url);
  const path = parsed.pathname === "/"
    ? "home"
    : parsed.pathname.replace(/^\/|\/$/g, "").replaceAll("/", "-");

  return `${parsed.hostname}-${path}`.replace(/[^a-z0-9.-]+/gi, "-").toLowerCase();
}

function formatBytes(bytes) {
  if (!Number.isFinite(bytes) || bytes === 0) {
    return "0 B";
  }

  if (bytes < 1024) {
    return `${bytes} B`;
  }

  if (bytes < 1024 * 1024) {
    return `${(bytes / 1024).toFixed(1)} KB`;
  }

  return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
}

function markdownReport(results) {
  const lines = [
    "# Performance Audit",
    "",
    `Generated at: ${new Date().toISOString()}`,
    "",
    "## Lighthouse lab scores",
    "",
    "| URL | Strategy | Performance | Accessibility | Best Practices | SEO |",
    "| --- | --- | ---: | ---: | ---: | ---: |",
  ];

  for (const result of results) {
    const categories = result.summary.categories;
    lines.push(
      `| ${result.url} | ${result.strategy} | ${categories.performance ?? "n/a"} | ${categories.accessibility ?? "n/a"} | ${categories.bestPractices ?? "n/a"} | ${categories.seo ?? "n/a"} |`,
    );
  }

  for (const result of results) {
    const summary = result.summary;
    lines.push("", `## ${result.url} (${result.strategy})`, "");
    lines.push(`- Final URL: ${summary.finalDisplayedUrl || "n/a"}`);
    lines.push(`- Lighthouse: ${summary.lighthouseVersion || "n/a"}`);

    lines.push("", "### Lab metrics", "");
    for (const metric of summary.metrics) {
      lines.push(`- ${metric.title}: ${metric.displayValue || metric.numericValue || "n/a"}`);
    }

    lines.push("", "### CrUX field data", "");
    if (summary.fieldData.source === "unavailable") {
      lines.push("- No URL-level or origin-level field data available.");
    } else {
      lines.push(`- Source: ${summary.fieldData.source}`);
      lines.push(`- Dataset: ${summary.fieldData.id || "n/a"}`);
      lines.push(`- Overall category: ${summary.fieldData.overallCategory || "n/a"}`);
      for (const metric of summary.fieldData.metrics) {
        lines.push(`- ${metric.label}: ${metric.displayValue} (${metric.category || "n/a"})`);
      }
    }

    lines.push("", "### Resources", "");
    if (summary.resources.length === 0) {
      lines.push("- Resource summary unavailable.");
    } else {
      lines.push("| Type | Requests | Transfer |", "| --- | ---: | ---: |");
      for (const resource of summary.resources) {
        lines.push(
          `| ${resource.resourceType} | ${resource.requestCount} | ${formatBytes(resource.transferSize)} |`,
        );
      }
    }

    lines.push("", "### Third parties", "");
    if (summary.thirdParties.length === 0) {
      lines.push("- No third-party summary reported.");
    } else {
      lines.push("| Entity | Transfer | Main thread | Blocking |", "| --- | ---: | ---: | ---: |");
      for (const thirdParty of summary.thirdParties) {
        lines.push(
          `| ${thirdParty.entity} | ${formatBytes(thirdParty.transferSize)} | ${Math.round(thirdParty.mainThreadTime)} ms | ${Math.round(thirdParty.blockingTime)} ms |`,
        );
      }
    }

    lines.push("", "### Top opportunities", "");
    if (summary.opportunities.length === 0) {
      lines.push("- No PageSpeed opportunity audit failed.");
    } else {
      for (const opportunity of summary.opportunities.slice(0, 8)) {
        const value = opportunity.displayValue ? ` (${opportunity.displayValue})` : "";
        lines.push(`- ${opportunity.title}${value} [${opportunity.id}]`);
      }
    }

    lines.push("", "### Failing audits", "");
    if (summary.failingAudits.length === 0) {
      lines.push("- No failing audit under score 0.9.");
    } else {
      for (const audit of summary.failingAudits.slice(0, 12)) {
        const value = audit.displayValue ? ` (${audit.displayValue})` : "";
        lines.push(`- ${audit.title}${value} [${audit.id}]`);
      }
    }

    if (summary.warnings.length > 0) {
      lines.push("", "### Run warnings", "");
      for (const warning of summary.warnings) {
        lines.push(`- ${warning}`);
      }
    }
  }

  return `${lines.join("\n")}\n`;
}

async function main() {
  loadLocalEnv();

  const options = parseArgs(process.argv.slice(2));
  validateOptions(options);
  await mkdir(reportDir, { recursive: true });

  const results = [];

  for (const url of options.urls) {
    for (const strategy of options.strategies) {
      console.log(`Auditing ${url} (${strategy})...`);
      results.push(await runOne(url, strategy, options));
    }
  }

  const timestamp = new Date().toISOString().replace(/[:.]/g, "-");
  const suffix = options.urls.length === 1 ? slugifyUrl(options.urls[0]) : "batch";
  const jsonPath = resolve(reportDir, `pagespeed-${timestamp}-${suffix}.json`);
  const mdPath = resolve(reportDir, `pagespeed-${timestamp}-${suffix}.md`);

  await writeFile(jsonPath, `${JSON.stringify({ results }, null, 2)}\n`);
  await writeFile(mdPath, markdownReport(results));

  console.log(`Wrote ${basename(jsonPath)}`);
  console.log(`Wrote ${basename(mdPath)}`);
}

main().catch((error) => {
  console.error(error.message);
  process.exitCode = 1;
});
