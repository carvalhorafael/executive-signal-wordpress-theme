import { mkdir, readFile, writeFile } from "node:fs/promises";
import { dirname, resolve } from "node:path";

const root = resolve(import.meta.dirname, "..");
const potPath = resolve(root, "languages/executive-signal-wordpress-theme.pot");
const poPath = resolve(root, "languages/pt_BR.po");

const pot = await readFile(potPath, "utf8");

const output = pot
  .replace('msgstr ""\n"Language-Team: Executive Signal\\\\n"', 'msgstr ""\n"Language-Team: Executive Signal\\\\n"\n"Language: pt_BR\\\\n"')
  .replaceAll(/msgstr ""\n(?=\n|\n#|\nmsgid)/g, (match, offset, source) => {
    const before = source.slice(0, offset);
    const msgidMatch = before.match(/msgid "((?:[^"\\]|\\.)*)"\n(?:msgid_plural "((?:[^"\\]|\\.)*)"\n)?$/);

    if (!msgidMatch || msgidMatch[1] === "") {
      return match;
    }

    if (msgidMatch[2]) {
      return `msgstr[0] "${msgidMatch[1]}"\nmsgstr[1] "${msgidMatch[2]}"\n`;
    }

    return `msgstr "${msgidMatch[1]}"\n`;
  });

await mkdir(dirname(poPath), { recursive: true });
await writeFile(poPath, output);

console.log(`Synced ${poPath}`);
