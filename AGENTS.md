# Instrucoes para agentes

## Contexto do projeto

Este repositorio contem o tema WordPress do Executive Signal.

O tema deve ser tratado como uma camada de apresentacao e adaptacao WordPress. Nao coloque regra de negocio duravel dentro do tema sem decisao explicita registrada em `docs/theme-decisions.md`. Quando surgirem tipos de conteudo, taxonomias ou metadados que precisem sobreviver a troca de tema, prefira um plugin dedicado.

## Ambiente local WordPress

O ambiente local usa `@wordpress/env`, que roda WordPress via Docker.

Comandos principais:

```bash
npm install
npm run wp:start
npm run dev
```

URLs locais:

- Site: http://localhost:8888/
- Admin: http://localhost:8888/wp-admin
- Login: http://localhost:8888/wp-login.php

Credenciais locais padrao do `wp-env`:

- Usuario: `admin`
- Senha: `password`

Essas credenciais sao apenas do ambiente local de desenvolvimento. Nao usar como referencia para producao, staging ou qualquer ambiente real.

## Fluxo de branches

Regra padrao:

- nao desenvolver diretamente em `main`;
- usar `develop` como branch auxiliar de integracao;
- antes de criar branch de trabalho, buscar `origin` e sincronizar `develop` com `origin/develop`;
- toda branch de trabalho deve partir de `origin/develop` atualizado;
- criar uma branch de trabalho antes de alterar codigo;
- usar prefixo `codex/` para branches criadas por agentes;
- fazer commits pequenos e intencionais;
- fazer push da branch para `origin`;
- abrir PRs pequenos para `develop` por padrao;
- levar mudancas para `main` apenas por PR de release vindo de `develop`.

Antes de comecar uma nova tarefa, sempre verificar:

```bash
git status --short --branch
git branch -vv
```

Se o checkout estiver em `main`, sincronizar `develop` com `origin/develop` e criar a branch de trabalho a partir de `develop`, salvo quando a tarefa for explicitamente uma preparacao de release para `main`. Nao criar branch de trabalho a partir de `main`, `master` ou branch antiga.

## Internacionalizacao

O tema deve respeitar as boas praticas de internacionalizacao do WordPress.

Regras para novos textos:

- usar sempre o text domain `executive-signal-wordpress-theme`;
- nao introduzir texto visivel hardcoded em PHP, templates ou patterns sem funcao de traducao;
- para texto HTML visivel, usar `esc_html__()` ou `esc_html_e()`;
- para atributos, usar `esc_attr__()` ou `esc_attr_e()`;
- para strings que precisam de contexto, usar `_x()`, `esc_html_x()` ou `esc_attr_x()`;
- para plural, usar `_n()` ou `_nx()`;
- para strings com placeholders, adicionar comentario `translators`;
- quando a mudanca adicionar, remover ou alterar strings traduziveis, rodar `npm run i18n` e commitar as alteracoes em `languages/`;
- antes de considerar uma mudanca pronta, usar `npm run i18n:check` ou `npm run validate`.

O idioma base inicial e `pt_BR`. O arquivo `languages/pt_BR.po` funciona como catalogo identidade ate que outro fluxo de traducao seja decidido.

## Politica de testes automatizados

Nao tente testar tudo. A suite deve proteger contratos, fluxos criticos e bugs reais, sem virar uma colecao fragil de testes de detalhe visual.

Camadas esperadas:

- `npm run test:static`: build Vite, sintaxe PHP, PHPCS e Theme Check;
- `npm run test:php`: PHPUnit dentro do WordPress de testes do `wp-env`;
- `npm run test:e2e`: Playwright para smoke do front-end e do editor;
- `npm test`: gate automatizado padrao para PRs;
- `npm run validate`: gate completo de release e empacotamento.

O que deve ser testado:

- setup WordPress do tema: theme supports, menus, text domain, enqueues e bootstrap basico;
- contracts com o editor de blocos: `theme.json`, patterns, classes e atributos esperados;
- helpers PHP proprios: escaping, fallback e geracao de markup;
- interacoes criticas no navegador: homepage, menu mobile, ausencia de erros graves de console e acessibilidade automatizada basica;
- empacotamento: ZIP gerado, allowlist do pacote e instalacao limpa em WordPress de testes.

O que nao deve ser testado aqui:

- regras de negocio de produto que pertencam a plugins ou sistemas externos;
- cada classe CSS, token ou variacao visual;
- snapshots visuais amplos para mudancas cosmeticas pequenas;
- cada texto estatico de pattern, salvo quando o texto fizer parte de contrato funcional.

## Releases e tags

A decisao de criar uma nova release e humana. A automacao comeca quando uma tag `vX.Y.Z` e enviada ao GitHub.

Rotina padrao de release:

1. acumular PRs pequenos em `develop`;
2. quando a release for decidida, criar uma branch de release a partir de `develop`;
3. atualizar a versao em `package.json`;
4. atualizar `Version` em `style.css`;
5. atualizar `Stable tag` em `readme.txt`;
6. abrir PR da branch de release para `main`;
7. mergear em `main` apos o CI completo passar;
8. sincronizar `main` local;
9. criar tag anotada no formato `vX.Y.Z`;
10. fazer push da tag.

O workflow `Release` roda em tags `v*`. Ele deve validar que a tag `vX.Y.Z` bate com:

- `package.json` -> `version`;
- `style.css` -> `Version`;
- `readme.txt` -> `Stable tag`.

Depois disso, executa `npm run validate`, cria a GitHub Release e anexa o ZIP publico do tema.

## Validade de distribuicao

Se o tema for distribuido fora do WordPress.org, use `Update URI` em `style.css` e mantenha uma validacao especifica para esse caso no Theme Check. Nao embuta credenciais no tema para acessar releases privadas. Distribuicao privada precisa de intermediario seguro.
