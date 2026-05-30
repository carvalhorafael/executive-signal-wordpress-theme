# Executive Signal WordPress Theme

Tema WordPress do Executive Signal.

Este repositorio contem uma base de tema hibrido: templates PHP classicos, suporte ao editor de blocos via `theme.json`, block patterns, assets compilados com Vite e validacoes automatizadas para qualidade, empacotamento e release.

## Requisitos

- Docker
- Node.js 20+
- npm

O ambiente WordPress local roda com `@wordpress/env`.

## Desenvolvimento local

Instale as dependencias:

```bash
npm install
```

Inicie o WordPress local:

```bash
npm run wp:start
```

Instale as dependencias PHP dentro do container:

```bash
npm run composer:install
```

Inicie o servidor de desenvolvimento do tema:

```bash
npm run dev
```

URLs locais:

- Site: http://localhost:8888/
- Admin: http://localhost:8888/wp-admin
- Login: http://localhost:8888/wp-login.php

Credenciais padrao do `wp-env`:

- Usuario: `admin`
- Senha: `password`

## Comandos principais

```bash
npm run build
```

Compila os assets com Vite em `assets/dist`.

```bash
npm test
```

Executa build, i18n, sintaxe PHP, PHPCS, Theme Check, PHPUnit e Playwright.

```bash
npm run validate
```

Executa a suite completa e valida o pacote distribuivel do tema.

```bash
npm run theme:zip
```

Gera o ZIP instalavel em `dist/executive-signal-wordpress-theme.zip`.

```bash
npm run wp:stop
```

Para os containers do WordPress local.

## Estrutura

- `functions.php`: bootstrap minimo do tema.
- `inc/`: modulos PHP de setup, assets, Vite, patterns, helpers e updater.
- `patterns/`: block patterns do Executive Signal.
- `template-parts/`: partes reutilizaveis dos templates.
- `src/`: JavaScript e CSS fonte.
- `assets/dist/`: build gerado pelo Vite.
- `languages/`: catalogos de internacionalizacao.
- `tests/php/`: testes PHPUnit.
- `tests/e2e/`: testes Playwright.
- `docs/`: documentacao operacional e decisoes do projeto.

## Fluxo de trabalho

O fluxo padrao usa `develop` como branch de integracao.

Antes de criar uma branch de trabalho, sincronize `develop` com `origin/develop`. Toda branch de trabalho deve partir de `origin/develop` atualizado.

```bash
git fetch origin --prune
git checkout develop
git pull --ff-only origin develop
git checkout -b codex/nome-da-tarefa
```

Abra pull requests de branches de trabalho para `develop`. Mudancas em `main` devem chegar por pull request vindo de `develop`, normalmente durante uma release.

## Release

A release e acionada por tag humana no formato `vX.Y.Z`.

Antes de criar a tag, mantenha a versao sincronizada em:

- `package.json`
- `style.css`
- `readme.txt`

Valide a paridade:

```bash
npm run release:check-version -- v0.1.0
```

Depois do merge da release em `main`, crie e envie a tag:

```bash
git checkout main
git pull --ff-only origin main
git tag -a v0.1.0 -m "Release v0.1.0"
git push origin v0.1.0
```

O workflow de release valida o tema e publica o ZIP na GitHub Release.

## Documentacao adicional

- `AGENTS.md`: regras de trabalho para agentes e contribuidores.
- `docs/architecture.md`: arquitetura do tema.
- `docs/development.md`: detalhes de desenvolvimento local.
- `docs/release.md`: processo de release.
- `docs/theme-decisions.md`: decisoes tecnicas do tema.
