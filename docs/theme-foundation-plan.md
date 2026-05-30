# Plano de fundacao do tema WordPress

Este plano transforma a decisao arquitetural inicial em uma sequencia executavel para criar a base do `executive-signal-wordpress-theme`.

## Principios

- O tema e uma camada de apresentacao WordPress, nao a fonte de regras de negocio.
- `functions.php` deve ser pequeno e carregar modulos focados em `inc/`.
- `theme.json` deve ser a ponte principal com o editor de blocos.
- Patterns e template parts devem concentrar a composicao visual reutilizavel.
- O pacote distribuivel deve ser validado como instalavel em um WordPress limpo.

## Checklist

1. [x] Criar governanca inicial do repo
   - `AGENTS.md` adaptado ao Executive Signal.
   - Este plano versionado em `docs/theme-foundation-plan.md`.
   - Validacao: `git status` e commit isolado.

2. [x] Criar metadados e estrutura base do tema
   - `style.css`, `readme.txt`, `LICENSE.md`, `screenshot.png` placeholder e `theme.json`.
   - Templates PHP minimos: `index.php`, `front-page.php`, `header.php`, `footer.php`, `page.php`, `single.php`, `archive.php`, `404.php`, `search.php`.
   - Validacao: `php -l` nos arquivos PHP criados.

3. [ ] Criar bootstrap modular
   - `functions.php` com constantes e requires.
   - `inc/setup.php`, `inc/assets.php`, `inc/vite.php`, `inc/patterns.php`, `inc/template-tags.php`.
   - Validacao: sintaxe PHP e teste PHP inicial para supports/menus.

4. [ ] Criar camada inicial de templates e patterns
   - `template-parts/content*.php`.
   - `patterns/hero.php`, `patterns/signal-grid.php`, `patterns/report-preview.php`, `patterns/cta.php`, `patterns/landing-page.php`.
   - Validacao: patterns registrados via PHPUnit.

5. [ ] Adicionar pipeline de assets
   - `src/main.js`, `src/editor.js`, CSS inicial e `vite.config.js`.
   - Scripts npm para `dev`, `build`, `wp:start`, `wp:stop`.
   - Validacao: `npm run build`.

6. [ ] Adicionar qualidade PHP
   - `composer.json`, `phpcs.xml.dist`, `phpunit.xml.dist`, `tests/php/bootstrap.php`.
   - Testes para setup, patterns e escaping de helpers.
   - Validacao: `npm run lint:php:syntax`, `npm run lint:php`, `npm run test:php`.

7. [ ] Adicionar internacionalizacao
   - `languages/`, scripts `i18n:*` e catalogo `pt_BR` inicial.
   - Validacao: `npm run i18n:check`.

8. [ ] Adicionar empacotamento e release gates
   - `scripts/build-theme-zip.mjs`, `scripts/validate-theme-zip.mjs`, `scripts/validate-release-version.mjs`.
   - `npm run theme:zip`, `npm run theme:validate-zip`, `npm run release:check-version`.
   - Validacao: ZIP contem apenas allowlist e metadados em paridade.

9. [ ] Adicionar CI e release automation
   - `.github/workflows/ci.yml`.
   - `.github/workflows/release.yml`.
   - Validacao: workflow YAML revisado e scripts locais equivalentes executados.

10. [ ] Adicionar smoke tests e documentacao operacional
    - `playwright.config.js`, smoke de homepage/editor quando aplicavel.
    - `docs/architecture.md`, `docs/development.md`, `docs/release.md`, `docs/theme-decisions.md`.
    - Validacao: `npm test` ou, se dependencias externas impedirem, registrar exatamente o bloqueio e os comandos pendentes.
