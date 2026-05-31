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
- Usuario: `admin`
- Senha: `password`

## Validacao

```bash
npm test
```

Esse comando executa build, i18n, sintaxe PHP, PHPCS, Theme Check, PHPUnit e Playwright.

Para o gate completo de pacote:

```bash
npm run validate
```
