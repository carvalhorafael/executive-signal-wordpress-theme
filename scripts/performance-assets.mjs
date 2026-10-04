import { readFile } from "node:fs/promises";
import { gzipSync } from "node:zlib";
import { extname, resolve } from "node:path";
import { mkdir, readdir, stat, writeFile } from "node:fs/promises";

const buildDir = resolve("assets/dist");
const reportDir = resolve("reports/assets");

async function listFiles(directory) {
  const entries = await readdir(directory, { withFileTypes: true });
  const files = [];

  for (const entry of entries) {
    const path = resolve(directory, entry.name);

    if (entry.isDirectory()) {
      files.push(...(await listFiles(path)));
    } else if (entry.isFile()) {
      files.push(path);
    }
  }

  return files;
}

function relativeToBuild(path) {
  return path.slice(buildDir.length + 1);
}

function formatBytes(bytes) {
  if (bytes < 1024) {
    return `${bytes} B`;
  }

  return `${(bytes / 1024).toFixed(2)} KB`;
}

async function describeFile(path) {
  const contents = await readFile(path);
  const fileStat = await stat(path);

  return {
    file: relativeToBuild(path),
    type: extname(path).slice(1) || "other",
    bytes: fileStat.size,
    gzipBytes: gzipSync(contents).byteLength,
  };
}

function aggregate(files) {
  const totals = {};

  for (const file of files) {
    totals[file.type] ||= { bytes: 0, gzipBytes: 0, files: 0 };
    totals[file.type].bytes += file.bytes;
    totals[file.type].gzipBytes += file.gzipBytes;
    totals[file.type].files += 1;
  }

  return totals;
}

function markdownReport(report) {
  const lines = [
    "# Production asset sizes",
    "",
    `Generated at: ${report.generatedAt}`,
    "",
    "No budget is enforced. This report establishes a baseline for later decisions.",
    "",
    "## Totals by type",
    "",
    "| Type | Files | Raw | Gzip |",
    "| --- | ---: | ---: | ---: |",
  ];

  for (const [type, total] of Object.entries(report.totals)) {
    lines.push(
      `| ${type} | ${total.files} | ${formatBytes(total.bytes)} | ${formatBytes(total.gzipBytes)} |`,
    );
  }

  lines.push(
    "",
    "## Files",
    "",
    "| File | Raw | Gzip |",
    "| --- | ---: | ---: |",
  );

  for (const file of report.files) {
    lines.push(`| ${file.file} | ${formatBytes(file.bytes)} | ${formatBytes(file.gzipBytes)} |`);
  }

  return `${lines.join("\n")}\n`;
}

async function main() {
  const paths = await listFiles(buildDir);
  const files = await Promise.all(paths.map((path) => describeFile(path)));
  files.sort((a, b) => b.bytes - a.bytes);

  const report = {
    generatedAt: new Date().toISOString(),
    files,
    totals: aggregate(files),
  };

  await mkdir(reportDir, { recursive: true });
  await writeFile(resolve(reportDir, "latest.json"), `${JSON.stringify(report, null, 2)}\n`);
  await writeFile(resolve(reportDir, "latest.md"), markdownReport(report));

  console.log(markdownReport(report));
}

main().catch((error) => {
  console.error(`Asset report failed: ${error.message}`);
  process.exitCode = 1;
});
