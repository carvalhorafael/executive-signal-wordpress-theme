import { readFile } from "node:fs/promises";
import { resolve } from "node:path";

const root = resolve(import.meta.dirname, "..");
const tag = process.argv[2] ?? process.env.TAG_NAME;

if (tag && !/^v\d+\.\d+\.\d+$/.test(tag)) {
  throw new Error("Expected a semver tag in the format vX.Y.Z.");
}

const packageJson = JSON.parse(await readFile(resolve(root, "package.json"), "utf8"));
const packageLock = JSON.parse(
  await readFile(resolve(root, "package-lock.json"), "utf8"),
);
const expectedVersion = tag ? tag.slice(1) : packageJson.version;
const styleCss = await readFile(resolve(root, "style.css"), "utf8");
const readme = await readFile(resolve(root, "readme.txt"), "utf8");
const pot = await readFile(
  resolve(root, "languages/executive-signal-wordpress-theme.pot"),
  "utf8",
);
const po = await readFile(resolve(root, "languages/pt_BR.po"), "utf8");
const mo = await readFile(resolve(root, "languages/pt_BR.mo"));

const styleVersion = styleCss.match(/^Version:\s*(.+)$/m)?.[1]?.trim();
const stableTag = readme.match(/^Stable tag:\s*(.+)$/m)?.[1]?.trim();
const catalogVersionPattern =
  /Project-Id-Version:\s*Executive Signal WordPress Theme\s+(\d+\.\d+\.\d+)/;
const potVersion = pot.match(catalogVersionPattern)?.[1];
const poVersion = po.match(catalogVersionPattern)?.[1];
const moVersion = mo.toString("latin1").match(catalogVersionPattern)?.[1];

const mismatches = [
  ["package.json", packageJson.version],
  ["package-lock.json", packageLock.version],
  ["package-lock.json root package", packageLock.packages?.[""]?.version],
  ["style.css", styleVersion],
  ["readme.txt", stableTag],
  ["languages/executive-signal-wordpress-theme.pot", potVersion],
  ["languages/pt_BR.po", poVersion],
  ["languages/pt_BR.mo", moVersion],
].filter(([, version]) => version !== expectedVersion);

if (mismatches.length > 0) {
  console.error(`Release version mismatch for v${expectedVersion}:`);
  for (const [file, version] of mismatches) {
    console.error(`- ${file}: ${version ?? "missing"}`);
  }
  process.exit(1);
}

console.log(`Release version ${expectedVersion} matches every release file.`);
