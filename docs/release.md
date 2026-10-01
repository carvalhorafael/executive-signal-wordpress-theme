# Release

A decisao de release e humana, mas a publicacao e automatica depois do merge em `main`.

## Fluxo padrao

1. Acumule PRs pequenos em `develop`.
2. Quando a release for decidida, peca explicitamente para preparar a release com a versao desejada.
3. Ainda no fluxo final de PR para `develop`, execute `npm run release:prepare -- X.Y.Z`. O comando atualiza as versoes e regenera os catalogos de traducao.
4. Revise e commite os arquivos gerados, depois aguarde o CI completo de `develop` passar.
5. Abra a PR de `develop` para `main` sem mudancas funcionais adicionais.
6. Depois que a checagem de versao e o pacote validado da PR passarem, faca merge em `main`.
7. O workflow `Release` cria a tag `vX.Y.Z`, valida o pacote, cria a GitHub Release e anexa o ZIP do tema sem repetir a suite funcional completa.

## Validacao local

Antes de abrir o PR para `main`, garanta paridade entre:

- `package.json` -> `version`
- `package-lock.json` -> `version` e versao do pacote raiz
- `style.css` -> `Version`
- `readme.txt` -> `Stable tag`
- `languages/executive-signal-wordpress-theme.pot` -> `Project-Id-Version`
- `languages/pt_BR.po` -> `Project-Id-Version`
- `languages/pt_BR.mo` -> catalogo compilado

Prepare e valide localmente:

```bash
npm run release:prepare -- 0.4.0
npm run release:check-version -- v0.4.0
npm run release:package
```

## Automacao

O workflow `Release` roda em `push` para `main`. Ele:

- le a versao de `package.json`;
- monta a tag `vX.Y.Z`;
- falha se essa tag ja existir;
- valida a paridade de versao em todos os metadados e catalogos de traducao;
- executa `npm run release:package`;
- cria a tag anotada;
- publica a GitHub Release;
- anexa `dist/executive-signal-wordpress-theme.zip`.

Nao crie tags manualmente no fluxo normal. Se uma release falhar depois do merge em `main`, trate como recuperacao operacional e registre a decisao antes de criar ou reenviar tags manualmente.

## Atualizacao no WordPress

O tema usa `Update URI` em `style.css` e `inc/updater.php` para consultar a ultima GitHub Release publica do repositorio. Quando a tag da ultima release for maior que a versao instalada, o WordPress deve exibir a atualizacao do tema no painel e permitir atualizar usando o ZIP anexado a release.
