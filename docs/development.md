# Desenvolvimento

## Requisitos

- Docker
- Node.js 20+
- npm

## Primeira execucao

```bash
npm install
npm run wp:start
npm run composer:install
npm run dev
```

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
