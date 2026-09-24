# Desenvolvimento

## Requisitos

- Docker
- Node.js 20+
- npm
- token do GitHub Packages com leitura dos pacotes `@carvalhorafael/*`

## GitHub Packages

Configure o registry local antes da primeira instalacao:

```bash
cp .npmrc.example .npmrc
```

Depois substitua `SEU_TOKEN_GITHUB` por um token com acesso de leitura aos packages do Executive Signal Design System. O arquivo `.npmrc` real nao deve ser versionado.

## Primeira execucao

```bash
npm install
npm run dev
```

`npm run dev` sobe o WordPress local com `wp-env`, instala as dependencias PHP dentro do container e inicia o Vite.

URLs locais:

- Site: http://localhost:8888/
- Admin: http://localhost:8888/wp-admin
- Testes: http://localhost:8889/
- Usuario: `admin`
- Senha: `password`

Use o ambiente em `8888` para conteudo manual de desenvolvimento. Os testes automatizados rodam contra `8889` via `tests-cli`, para que fixtures de E2E nao sobrescrevam posts, paginas, midia e menus usados na avaliacao manual.

## Validacao

```bash
npm run test:quick
```

Use durante iteracoes pequenas. Esse comando roda apenas build Vite e sintaxe PHP.

```bash
npm test
```

Esse comando executa build, i18n, sintaxe PHP, PHPCS, Theme Check, PHPUnit e Playwright.

Use antes de push ou PR. Para o gate completo de pacote:

```bash
npm run validate
```
