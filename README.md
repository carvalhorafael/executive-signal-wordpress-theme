# Executive Signal WordPress Theme

Tema WordPress do Executive Signal.

Este repositorio contem uma base de tema hibrido: templates PHP classicos, suporte ao editor de blocos via `theme.json`, block patterns, assets compilados com Vite e validacoes automatizadas para qualidade, empacotamento e release.

O tema consome o Executive Signal Design System pelos pacotes publicados no GitHub Packages. O design system e a fonte de verdade para tokens, CSS compartilhado, contratos de pattern e comportamentos web portaveis; este repositorio adapta esses contratos para WordPress.

## Requisitos

- Docker
- Node.js 20+
- npm
- token do GitHub Packages com leitura dos pacotes `@carvalhorafael/*`

O ambiente WordPress local roda com `@wordpress/env`.

## GitHub Packages

Copie `.npmrc.example` para `.npmrc` e substitua `SEU_TOKEN_GITHUB` por um token com permissao de leitura dos packages:

```bash
cp .npmrc.example .npmrc
```

O arquivo `.npmrc` real nao deve ser commitado.

## Desenvolvimento local

Instale as dependencias:

```bash
npm install
```

Inicie o ambiente local de desenvolvimento:

```bash
npm run dev
```

Esse comando sobe o WordPress local com `wp-env`, instala as dependencias PHP dentro do container e inicia o Vite.

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

## Design System

Pacotes consumidos:

- `@carvalhorafael/executive-signal-tokens`
- `@carvalhorafael/executive-signal-css`
- `@carvalhorafael/executive-signal-web`
- `@carvalhorafael/executive-signal-patterns`

O CSS fonte importa os pacotes em `src/styles/main.css`. Ajustes especificos do WordPress ficam em `src/styles/theme.css`. Se uma tela precisar de markup ou comportamento que ainda nao exista no design system, siga a politica de issues cruzadas descrita em `AGENTS.md`.

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

A decisao de release e humana. Quando for hora de lancar, prepare uma branch de release a partir de `develop`, atualize a versao e abra PR para `main`.

Antes de abrir o PR para `main`, mantenha a versao sincronizada em:

- `package.json`
- `style.css`
- `readme.txt`

Valide a paridade:

```bash
npm run release:check-version -- v0.1.0
```

Depois do merge em `main`, o workflow `Release` cria a tag `vX.Y.Z`, valida o tema e publica o ZIP na GitHub Release.

O tema tambem consulta a ultima GitHub Release pelo updater em `inc/updater.php`. Quando houver uma versao mais nova, o painel do WordPress deve oferecer atualizacao de tema em um clique.

## Documentacao adicional

- `AGENTS.md`: regras de trabalho para agentes e contribuidores.
- `docs/architecture.md`: arquitetura do tema.
- `docs/development.md`: detalhes de desenvolvimento local.
- `docs/release.md`: processo de release.
- `docs/theme-decisions.md`: decisoes tecnicas do tema.
