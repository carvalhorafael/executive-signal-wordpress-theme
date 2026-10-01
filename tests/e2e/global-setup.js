import { execFileSync } from "node:child_process";

export default function globalSetup() {
  execFileSync(
    "npx",
    [
      "wp-env",
      "run",
      "tests-cli",
      "wp",
      "eval-file",
      "/var/www/html/wp-content/themes/executive-signal-wordpress-theme/tests/e2e/setup-smoke-fixtures.php",
    ],
    {
      encoding: "utf8",
      stdio: "inherit",
    },
  );
}
