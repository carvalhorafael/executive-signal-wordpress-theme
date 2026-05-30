# Release

A release e acionada por tag humana no formato `vX.Y.Z`.

Antes de criar a tag, garanta paridade entre:

- `package.json` -> `version`
- `style.css` -> `Version`
- `readme.txt` -> `Stable tag`

Valide localmente:

```bash
npm run release:check-version -- v0.1.0
npm run validate
```

Depois que a branch de release for mergeada em `main`:

```bash
git checkout main
git pull --ff-only origin main
git tag -a v0.1.0 -m "Release v0.1.0"
git push origin v0.1.0
```

O workflow `Release` valida o pacote e publica uma GitHub Release com `dist/executive-signal-wordpress-theme.zip`.
