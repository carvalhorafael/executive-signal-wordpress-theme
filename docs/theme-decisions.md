# Decisoes do tema

## 2026-05-30: Tema hibrido como base inicial

Decisao: iniciar como tema hibrido classico, com templates PHP, `theme.json`, block patterns e assets Vite.

Motivo: o Executive Signal precisa de controle fino de templates e distribuicao, mas tambem deve aproveitar o editor de blocos. Um block theme puro pode ser avaliado depois, quando os contratos de conteudo e patterns estiverem mais estaveis.

## 2026-05-30: Conteudo permanente fora do tema

Decisao: o tema nao deve ser a fonte de tipos de conteudo e regras de negocio duraveis.

Motivo: trocar tema nao deve apagar ou quebrar dominio de produto. Se o projeto precisar de CPTs, taxonomias ou metadados permanentes, criar plugin ou registrar uma excecao explicita aqui.

## 2026-05-31: Textos visiveis sempre internacionalizados

Decisao: todo texto visivel introduzido pelo tema deve usar funcoes de internacionalizacao do WordPress com o text domain `executive-signal-wordpress-theme`.

Motivo: o idioma base do projeto e pt_BR, mas o tema deve manter catalogo real em `languages/` e nao depender de strings hardcoded em templates PHP, patterns ou componentes do tema.

## 2026-05-31: Hero de artigo sem imagem destacada

Decisao: o topo do artigo individual usa apenas texto, metadados e categoria. A imagem destacada nao aparece no hero do artigo.

Motivo: muitas imagens destacadas vem de thumbnails de YouTube com proporcoes diferentes. Remover a imagem do hero evita cortes inconsistentes e deixa o layout mais previsivel para artigos com titulos longos.

## 2026-05-31: Textos do blog no Customizer

Decisao: os textos principais da listagem do blog podem ser configurados em Aparencia > Personalizar.

Motivo: esses textos sao copy editorial do site, nao regra de negocio. O Customizer permite ajuste pelo administrador sem criar dependencia de plugin ou alterar templates.

## 2026-05-31: `Update URI` mantido no tema

Decisao: manter `Update URI` em `style.css`.

Motivo: o tema sera distribuido fora do diretorio WordPress.org e usa o atualizador proprio em `inc/updater.php`. O Theme Check marca isso como `REQUIRED`, mas `scripts/run-theme-check.php` ja trata esse caso como excecao esperada para distribuicao privada/publica via GitHub Release.

## 2026-05-31: `custom-header` e `custom-background` nao suportados por enquanto

Decisao: nao registrar `custom-header` nem `custom-background` neste momento.

Motivo: o tema consome tokens e contratos visuais do Executive Signal Design System. Permitir imagens de cabecalho ou background arbitrarios no Customizer criaria uma superficie visual fora do design system sem necessidade atual.

## 2026-05-31: Busca centralizada em `searchform.php`

Decisao: formularios de busca devem passar por `get_search_form()` e `searchform.php`.

Motivo: isso preserva os filtros do WordPress, reduz duplicacao entre header, busca e estados vazios, e elimina formularios de busca hardcoded nos templates principais.

## 2026-06-04: Materiais gratuitos acoplados inicialmente ao tema

Decisao: registrar o tipo de conteudo `material_gratuito`, a taxonomia `material_categoria` e os metadados de captura dentro do tema nesta fase inicial.

Motivo: embora tipos de conteudo duraveis devam preferencialmente viver em plugin, o site sera operado por uma unica pessoa e criar um plugin isolado agora adicionaria custo de desenvolvimento, CI, release e integracao maior que o beneficio imediato.

Limites da excecao:

- manter registro, metadados, helpers e filtros concentrados em `inc/free-materials.php`;
- manter templates e estilos em arquivos claramente identificaveis para facilitar extracao futura;
- preservar nomes estaveis de post type, taxonomia e metadados;
- nao colocar regras de captura, CRM, e-mail marketing ou automacoes de negocio dentro do tema;
- reavaliar extracao para plugin quando houver outro consumidor, troca de tema planejada ou regras de captura mais complexas.
