# Release

A decisao de release e humana, mas a publicacao e automatica depois do merge em `main`.

## Fluxo padrao

1. Acumule PRs pequenos em `develop`.
2. Quando a release for decidida, peca explicitamente para preparar a release com a versao desejada.
3. Crie uma branch de release a partir de `develop`.
4. Atualize a versao em:
   - `package.json` -> `version`
   - `style.css` -> `Version`
   - `readme.txt` -> `Stable tag`
5. Abra PR da branch de release para `main`.
6. Depois que o CI passar, faca merge em `main`.
7. O workflow `Release` cria a tag `vX.Y.Z`, valida o pacote, cria a GitHub Release e anexa o ZIP do tema.

## Validacao local

Antes de abrir o PR para `main`, garanta paridade entre:

- `package.json` -> `version`
- `style.css` -> `Version`
- `readme.txt` -> `Stable tag`

Valide localmente:

```bash
npm run release:check-version -- v0.1.0
npm run validate
```

## Automacao

O workflow `Release` roda em `push` para `main`. Ele:

- le a versao de `package.json`;
- monta a tag `vX.Y.Z`;
- falha se essa tag ja existir;
- valida a paridade de versao com `style.css` e `readme.txt`;
- executa `npm run validate`;
- cria a tag anotada;
- publica a GitHub Release;
- anexa `dist/executive-signal-wordpress-theme.zip`.

Nao crie tags manualmente no fluxo normal. Se uma release falhar depois do merge em `main`, trate como recuperacao operacional e registre a decisao antes de criar ou reenviar tags manualmente.

## Atualizacao no WordPress

O tema usa `Update URI` em `style.css` e `inc/updater.php` para consultar a ultima GitHub Release publica do repositorio. Quando a tag da ultima release for maior que a versao instalada, o WordPress deve exibir a atualizacao do tema no painel e permitir atualizar usando o ZIP anexado a release.
