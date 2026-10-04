# Performance, SEO e acessibilidade

Este documento define a rotina de auditoria de performance do tema Executive Signal.

## Objetivo

O fluxo deve permitir:

- medir páginas representativas com o tema e os assets de produção;
- comparar um baseline antes e depois de uma otimização;
- validar em URL pública o que usuários e mecanismos de busca recebem;
- separar problemas do tema de problemas de conteúdo, plugins, terceiros, hospedagem ou design system;
- promover apenas limites estáveis para o CI obrigatório.

## Camadas de auditoria

O projeto usa três camadas complementares:

1. Lighthouse CI local para iteração reproduzível;
2. PageSpeed Insights e CrUX para validação de URLs públicas;
3. Playwright e axe para regressão funcional e acessibilidade automatizada.

Lighthouse local e PageSpeed público podem divergir nos números absolutos. O ambiente público inclui rede, cache, CDN, conteúdo editorial, plugins e terceiros que não são reproduzidos integralmente no `wp-env`. Durante a implementação, compare tendências coletadas em condições equivalentes. Use a auditoria pública para a conclusão sobre a experiência real.

## Lighthouse local

A auditoria local usa o WordPress de testes na porta `8889`, as fixtures determinísticas do smoke e os assets compilados pelo Vite. Ela não usa `npm run dev`, porque HMR e assets de desenvolvimento distorcem a medição.

Execute a preparação e a auditoria:

```bash
npm run perf:prepare
npm run perf:lighthouse
```

Ou use o atalho, que também registra o tamanho bruto e gzip dos assets compilados:

```bash
npm run perf:local
```

O arquivo `lhci.config.cjs` faz três execuções em emulação mobile para:

- `/`;
- `/blog/`;
- `/performance-audit-article/`;
- `/coo-as-a-service/`;
- `/palestras/`.

Os relatórios são gravados em `reports/lighthouse-ci/`. A variável `WP_ENV_TESTS_PORT` altera a porta, e `PERF_LOCAL_BASE_URL` pode substituir a origem completa.

Os limites iniciais são advisory e geram `warn`, não erro. Eles existem para destacar desvios enquanto o projeto coleta baselines estáveis:

| Contrato | Limite inicial |
| --- | ---: |
| Performance | 70 |
| Acessibilidade | 90 |
| Boas práticas | 90 |
| SEO | 90 |
| LCP | 4 s |
| CLS | 0,25 |
| TBT | 600 ms |

## PageSpeed Insights e CrUX

Use URLs públicas atualizadas em staging ou produção:

```bash
npm run perf:pagespeed -- \
  https://www.exemplo.com/ \
  https://www.exemplo.com/blog/ \
  https://www.exemplo.com/artigo/
```

O comando executa mobile e desktop por padrão. Também é possível limitar estratégia ou categoria:

```bash
npm run perf:pagespeed -- \
  --strategy=mobile \
  --category=performance,accessibility \
  https://www.exemplo.com/
```

Variáveis disponíveis:

- `GOOGLE_PSI_API_KEY`: chave da API fora do repositório;
- `PERF_URLS`: lista de URLs separadas por vírgula;
- `PERF_LOCALE`: locale da resposta, `pt-BR` por padrão.

O script lê `.env` quando a chave ainda não estiver exportada. O arquivo real não deve ser versionado.

Cada execução gera em `reports/performance/`:

- JSON bruto da API;
- resumo Markdown com scores e métricas Lighthouse de laboratório;
- Core Web Vitals do CrUX, distinguindo dados da URL e fallback de origem;
- peso e quantidade dos recursos;
- terceiros, oportunidades e auditorias reprovadas.

Dados CrUX podem não existir para páginas ou origens com tráfego insuficiente. Nesse caso, o relatório registra que os dados de campo estão indisponíveis, sem tratar a ausência como zero.

## Matriz pública mínima

Uma rodada completa deve medir:

1. home;
2. home do blog;
3. pelo menos um artigo representativo;
4. COO as a Service;
5. palestras.

Materiais gratuitos, catálogo de cursos e página individual de curso entram quando o objetivo da rodada envolver essas superfícies e os plugins companheiros estiverem em versões compatíveis.

## Formato de uma rodada

1. Confirme branch, build, plugins companheiros e conteúdo das URLs.
2. Rode `npm run perf:local` antes da implementação.
3. Registre a mediana das três execuções por página.
4. Classifique cada achado:
   - `theme`: template, markup, enqueue, CSS, JS ou imagem embarcada;
   - `design-system`: pacote `tokens`, `css`, `web` ou `patterns`;
   - `plugin`: funcionalidade fornecida por plugin companheiro;
   - `content`: mídia, embed ou conteúdo administrado no WordPress;
   - `third-party`: YouTube, analytics, fontes ou outro fornecedor;
   - `hosting`: TTFB, cache, CDN, compressão ou headers.
5. Corrija primeiro itens de alto impacto cuja responsabilidade seja do tema.
6. Repita a auditoria local nas mesmas condições.
7. Depois da publicação, execute PageSpeed nas URLs públicas.
8. Registre antes, depois, decisão e responsabilidade na issue ou PR.

Quando um achado revelar um gap reutilizável do Executive Signal Design System, siga a política de issues cruzadas do `AGENTS.md`. Não esconda gaps de plugin, infraestrutura ou design system em workarounds permanentes do tema.

## Registro em issue ou PR

Os arquivos em `reports/` são evidência temporária e não são versionados. Preserve na issue ou PR apenas o resumo necessário:

```md
## Performance audit

- URL:
- Data:
- Ambiente:
- Estratégia:
- Ferramenta:

| Métrica | Antes | Depois | Observação |
| --- | ---: | ---: | --- |
| Performance |  |  |  |
| LCP |  |  |  |
| CLS |  |  |  |
| TBT ou INP |  |  |  |
| CSS transferido |  |  |  |
| JavaScript transferido |  |  |  |

## Classificação

| Achado | Responsável | Decisão |
| --- | --- | --- |
|  | theme/design-system/plugin/content/third-party/hosting |  |
```

Identifique sempre se uma métrica veio do Lighthouse de laboratório ou do CrUX de campo.

## Entrada gradual no CI

Performance não faz parte de `npm test` nesta primeira etapa.

O fluxo previsto é:

1. coletar pelo menos três baselines locais estáveis;
2. revisar a variação por URL e métrica;
3. criar um job separado de performance no CI;
4. publicar relatórios como artefato;
5. promover primeiro contratos estruturais estáveis para `error`;
6. manter métricas sensíveis a máquina, rede, cache e terceiros como advisory até haver evidência suficiente.

PageSpeed público não deve bloquear toda PR. Ele depende de serviço externo, cota da API, estado publicado e condições de rede.

## Orçamento de assets

O bundle do Vite deve ser observado junto com Lighthouse, especialmente o CSS compartilhado. Um orçamento estático só deve virar gate depois de decidir se o contrato do tema continuará sendo um bundle único ou se estilos específicos serão carregados por template.

Até essa decisão, `npm run perf:assets` registra tamanho bruto e gzip em `reports/assets/`, sem criar um limite arbitrário.

## Baseline inicial, 2026-10-04

Coletado com três execuções por URL em emulação mobile de 390 px, usando o WordPress de testes na porta `8889` e os assets compilados. Os valores abaixo são das execuções representativas selecionadas pelo Lighthouse CI.

| URL | Performance | Acessibilidade | Boas práticas | SEO | LCP | CLS | TBT |
| --- | ---: | ---: | ---: | ---: | ---: | ---: | ---: |
| `/` | 100 | 96 | 100 | 92 | 1,5 s | 0 | 0 ms |
| `/blog/` | 100 | 96 | 100 | 91 | 1,4 s | 0 | 0 ms |
| `/performance-audit-article/` | 100 | 96 | 100 | 92 | 1,5 s | 0 | 0 ms |
| `/coo-as-a-service/` | 100 | 100 | 100 | 92 | 1,4 s | 0 | 0 ms |
| `/palestras/` | 100 | 100 | 100 | 92 | 1,5 s | 0 | 0 ms |

O build produziu:

| Tipo | Tamanho bruto | Gzip |
| --- | ---: | ---: |
| CSS de front-end | 250,38 KB | 32,49 KB |
| JavaScript de front-end | 6,03 KB | 1,65 KB |
| CSS total, incluindo editor | 417,20 KB | 52,89 KB |

### Achados classificados

| Achado | Evidência local | Classificação | Encaminhamento |
| --- | --- | --- | --- |
| Texto terciário abaixo de AA no tema claro | contraste de 4,44:1 na home, no arquivo do blog e no artigo | `design-system` | tema [#66](https://github.com/carvalhorafael/executive-signal-wordpress-theme/issues/66) e design system [#75](https://github.com/carvalhorafael/executive-signal-design-system/issues/75) |
| CSS compartilhado bloqueando renderização | economia estimada entre 410 e 720 ms | `theme` e possível `design-system` | observar a variação e decidir entre orçamento único ou carregamento por template antes de alterar o contrato |
| CSS não usado por página | economia estimada entre 28 e 30 KiB transferidos | `theme` e possível `design-system` | tratar junto com a decisão de empacotamento do CSS |
| Imagens de palestras sem formato e dimensões ideais | economia estimada de 85 KiB em formato moderno e 46 KiB em responsividade | `theme` e `third-party` | investigar o contrato de thumbnails do YouTube em uma rodada de otimização |
| Meta description ausente | todas as fixtures locais | `content` ou plugin de SEO | não criar regra editorial durável no tema apenas para elevar o score local |
| Cache de longa duração ausente | recursos servidos pelo `wp-env` | `hosting` local | não criar workaround no tema |

O PageSpeed público foi solicitado para home, COO as a Service, palestras, blog e o artigo mais recente disponível. A API respondeu `HTTP 429` antes da primeira análise porque a cota anônima diária estava esgotada e não havia `GOOGLE_PSI_API_KEY` configurada. Portanto, este baseline não apresenta score público nem dados CrUX; a ausência não deve ser interpretada como zero ou aprovação.
