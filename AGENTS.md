# Instrucoes para agentes

## Contexto do projeto

Este repositorio contem o tema WordPress do Executive Signal.

O tema deve ser tratado como uma camada de apresentacao e adaptacao WordPress. Nao coloque regra de negocio duravel dentro do tema sem decisao explicita registrada em `docs/theme-decisions.md`. Quando surgirem tipos de conteudo, taxonomias ou metadados que precisem sobreviver a troca de tema, prefira um plugin dedicado.

O tema consome os pacotes publicados do Executive Signal Design System:

- `@carvalhorafael/executive-signal-tokens`
- `@carvalhorafael/executive-signal-css`
- `@carvalhorafael/executive-signal-web`
- `@carvalhorafael/executive-signal-patterns`

Nao recrie tokens, componentes ou padroes visuais do zero dentro do tema. O WordPress deve ser tratado como consumer/adaptador da biblioteca.

## Gaps e evolucao do Design System

Quando a implementacao do tema revelar que o Executive Signal Design System nao possui um componente, token, contrato `web`, classe CSS, pattern ou comportamento necessario, siga esta politica obrigatoria. Nao deixe o gap apenas em comentario local, TODO solto ou memoria de conversa.

### Antes de criar UI local

1. Verifique se o design system ja cobre o caso nos pacotes `tokens`, `css`, `web` ou `patterns`.
2. Se existir contrato adequado, consuma o contrato existente.
3. Se nao existir contrato adequado, classifique o gap antes de implementar.

### Gap pequeno e estritamente local

Use esta categoria apenas quando a adaptacao depende claramente do WordPress ou do tema e nao parece reutilizavel fora deste repositorio.

- Pode implementar no tema.
- Documente no PR por que ficou local.
- Nao e necessario abrir issue no design system.

### Gap reutilizavel ou workaround temporario

Use esta categoria quando o tema precisar improvisar algo que provavelmente deveria existir no design system.

Obrigatorio:

1. Criar uma issue neste repositorio do tema descrevendo o workaround local.
2. Criar uma issue em `carvalhorafael/executive-signal-design-system` demandando o novo componente, ajuste de componente, token, CSS, contrato `web`, pattern ou comportamento.
3. Linkar as duas issues em ambos os sentidos.
4. Referenciar as duas issues no PR do tema.

A issue do tema deve conter:

- arquivo, template, pattern ou tela onde o workaround foi criado;
- por que o design system atual nao cobre o caso;
- impacto do workaround no tema;
- criterio para remover o workaround depois que o design system evoluir;
- link para a issue do design system.

A issue do design system deve conter:

- contexto de consumo vindo deste tema WordPress;
- link para a issue do tema;
- pacote(s) provavelmente afetado(s): `tokens`, `css`, `web`, `patterns` ou `react`;
- contrato esperado;
- criterio de aceite para o tema conseguir remover o workaround.

### Gap bloqueante ou componente central

Se o gap for um componente central, um contrato visual amplo, uma alteracao em componente existente do design system ou algo que afetara varios consumidores:

- pare a implementacao permanente no tema;
- abra issue no tema e no design system, linkadas;
- implemente primeiro no design system;
- publique uma nova versao dos pacotes;
- depois atualize o tema para consumir a nova versao.

Workaround temporario no tema so e aceitavel se for necessario para desbloquear um fluxo especifico e estiver coberto pelas issues linkadas.

### Fechamento do ciclo

Quando a issue do design system for resolvida:

1. Atualize as dependencias do tema para a versao publicada do design system.
2. Remova o workaround local.
3. Feche a issue do tema citando a versao/pacote que resolveu o gap.

## Ambiente local WordPress

O ambiente local usa `@wordpress/env`, que roda WordPress via Docker.

Comandos principais:

```bash
npm install
npm run dev
```

`npm run dev` deve ser o comando padrao para trabalhar localmente. Ele sobe o WordPress local, instala as dependencias PHP dentro do container e inicia o Vite.

URLs locais:

- Site: http://localhost:8888/
- Admin: http://localhost:8888/wp-admin
- Login: http://localhost:8888/wp-login.php
- Testes: http://localhost:8889/

Use `8888` para desenvolvimento manual. A porta `8889` e o container `tests-cli` sao reservados para testes automatizados; fixtures de E2E podem criar posts, paginas, midia e menus nesse ambiente.

Credenciais locais padrao do `wp-env`:

- Usuario: `admin`
- Senha: `password`

Essas credenciais sao apenas do ambiente local de desenvolvimento. Nao usar como referencia para producao, staging ou qualquer ambiente real.

## GitHub Packages

Para rodar `npm install` ou `npm ci`, o projeto precisa conseguir ler os pacotes `@carvalhorafael/*` no GitHub Packages.

Use `.npmrc.example` como modelo local:

```ini
@carvalhorafael:registry=https://npm.pkg.github.com
//npm.pkg.github.com/:_authToken=SEU_TOKEN_GITHUB
```

O arquivo `.npmrc` real nao deve ser commitado. Em GitHub Actions, use o secret `EXECUTIVE_SIGNAL_PACKAGES_TOKEN` e exponha-o como `NODE_AUTH_TOKEN` apenas no passo de instalacao.

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

- `npm run test:quick`: validacao curta para iteracao pequena, com build Vite e sintaxe PHP;
- `npm run test:static`: build Vite, sintaxe PHP, PHPCS e Theme Check;
- `npm run test:php`: PHPUnit dentro do WordPress de testes do `wp-env`;
- `npm run test:e2e`: Playwright para smoke do front-end e do editor, rodando contra a porta de testes do `wp-env`;
- `npm test`: gate automatizado padrao para PRs;
- `npm run validate`: gate completo de release e empacotamento.

Durante iteracoes pequenas, rode apenas o menor comando que cobre o risco da mudanca. Exemplos: `npm run test:quick` para mudancas simples de asset/PHP, `npm run i18n:check` quando alterar strings traduziveis, ou um teste especifico quando tocar uma area coberta. Reserve `npm test`, `npm run test:prepush` e `npm run validate` para antes de push, PR, release ou mudancas com impacto amplo.

O que deve ser testado:

- setup WordPress do tema: theme supports, menus, text domain, enqueues e bootstrap basico;
- contratos com o design system e o editor de blocos: `theme.json`, patterns, classes, atributos esperados e carregamento de assets;
- helpers PHP proprios: escaping, fallback e geracao de markup;
- interacoes criticas no navegador: homepage, menu mobile, ausencia de erros graves de console e acessibilidade automatizada basica;
- empacotamento: ZIP gerado, allowlist do pacote e instalacao limpa em WordPress de testes.

O que nao deve ser testado aqui:

- regras de negocio de produto que pertencam a plugins ou sistemas externos;
- implementacao interna dos pacotes do design system;
- cada classe CSS, token ou variacao visual;
- snapshots visuais amplos para mudancas cosmeticas pequenas;
- cada texto estatico de pattern, salvo quando o texto fizer parte de contrato funcional.

## Releases e tags

A decisao de criar uma nova release e humana. O usuario deve avisar explicitamente quando quiser preparar uma release, por exemplo: "preparar release 0.2.0".

Depois desse pedido, crie uma branch de release a partir de `develop`, atualize a versao e abra PR para `main`. O merge em `main` dispara a automacao de release.

Rotina padrao de release:

1. acumular PRs pequenos em `develop`;
2. quando a release for decidida pelo usuario, criar uma branch de release a partir de `develop`;
3. atualizar a versao em `package.json`;
4. atualizar `Version` em `style.css`;
5. atualizar `Stable tag` em `readme.txt`;
6. abrir PR da branch de release para `main`;
7. mergear em `main` apos o CI completo passar.

O workflow `Release` roda em `push` para `main`. Ele le `package.json`, resolve a tag `vX.Y.Z`, falha se a tag ja existir e valida que a versao bate com:

- `package.json` -> `version`;
- `style.css` -> `Version`;
- `readme.txt` -> `Stable tag`.

Depois disso, executa `npm run validate`, cria a tag anotada, cria a GitHub Release e anexa o ZIP publico do tema.

Nao crie tags manualmente por padrao. A tag manual so deve ser usada se o workflow de release falhar depois do merge em `main` e houver decisao explicita de recuperacao.

## Validade de distribuicao

Se o tema for distribuido fora do WordPress.org, use `Update URI` em `style.css` e mantenha uma validacao especifica para esse caso no Theme Check. Nao embuta credenciais no tema para acessar releases privadas. Distribuicao privada precisa de intermediario seguro.
