import { readFile, writeFile } from "node:fs/promises";
import { resolve } from "node:path";
import { spawnSync } from "node:child_process";

const root = resolve(import.meta.dirname, "..");
const requestedVersion = process.argv[2]?.replace(/^v/, "");

if (!requestedVersion || !/^\d+\.\d+\.\d+$/.test(requestedVersion)) {
  throw new Error("Expected a semver version in the format X.Y.Z or vX.Y.Z.");
}

const packagePath = resolve(root, "package.json");
const packageLockPath = resolve(root, "package-lock.json");
const stylePath = resolve(root, "style.css");
const readmePath = resolve(root, "readme.txt");

const packageJson = JSON.parse(await readFile(packagePath, "utf8"));
const packageLock = JSON.parse(await readFile(packageLockPath, "utf8"));

const compareVersions = (left, right) => {
  const leftParts = left.split(".").map(Number);
  const rightParts = right.split(".").map(Number);

  for (let index = 0; index < 3; index += 1) {
    if (leftParts[index] !== rightParts[index]) {
      return leftParts[index] - rightParts[index];
    }
  }

  return 0;
};

if (compareVersions(requestedVersion, packageJson.version) <= 0) {
  throw new Error(
    `Release version ${requestedVersion} must be greater than ${packageJson.version}.`,
  );
}

const replaceVersionLine = async (path, pattern, label) => {
  const contents = await readFile(path, "utf8");

  if (!pattern.test(contents)) {
    throw new Error(`Could not find the version field in ${label}.`);
  }

  await writeFile(
    path,
    contents.replace(pattern, (_match, prefix) => `${prefix}${requestedVersion}`),
  );
};

packageJson.version = requestedVersion;
packageLock.version = requestedVersion;

if (!packageLock.packages?.[""]) {
  throw new Error("Could not find the root package in package-lock.json.");
}

packageLock.packages[""].version = requestedVersion;

await writeFile(packagePath, `${JSON.stringify(packageJson, null, 2)}\n`);
await writeFile(packageLockPath, `${JSON.stringify(packageLock, null, 2)}\n`);
await replaceVersionLine(stylePath, /^(Version:\s*).+$/m, "style.css");
await replaceVersionLine(readmePath, /^(Stable tag:\s*).+$/m, "readme.txt");

const run = (command, args) => {
  const result = spawnSync(command, args, {
    cwd: root,
    shell: process.platform === "win32",
    stdio: "inherit",
  });

  if (result.status !== 0) {
    process.exit(result.status ?? 1);
  }
};

run("npm", ["run", "i18n"]);
run("npm", ["run", "release:check-version", "--", `v${requestedVersion}`]);

console.log(`Release ${requestedVersion} prepared. Review and commit the changed files.`);
