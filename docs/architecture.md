# Arquitetura

O `executive-signal-wordpress-theme` e um tema WordPress hibrido: usa templates PHP classicos para roteamento e composicao base, mas apoia o editor de blocos com `theme.json`, block patterns e editor styles.

O tema consome o Executive Signal Design System por pacotes publicados no GitHub Packages. Tokens, CSS compartilhado, contratos web portaveis e metadata de patterns pertencem ao design system; WordPress hooks, templates PHP, `theme.json`, enqueueing e adaptacoes de CMS pertencem a este repositorio.

## Camadas

- `functions.php`: bootstrap minimo, constantes e carregamento dos modulos.
- `inc/setup.php`: theme supports, menus e editor styles.
- `inc/assets.php` e `inc/vite.php`: carregamento de assets em desenvolvimento e producao.
- `inc/admin-notices.php`: avisos administrativos discretos para plugins complementares recomendados.
- `inc/patterns.php`: registro dos block patterns do Executive Signal.
- `inc/template-tags.php`: helpers compartilhados de template.
- `inc/free-materials.php`: helpers de apresentacao para o dominio fornecido pelo plugin `free-materials`.
- `inc/updater.php`: integracao de update via GitHub Releases para distribuicao fora do WordPress.org.
- `template-parts/`: markup reutilizavel para paginas, posts e estados vazios.
- `patterns/`: composicoes editoriais reutilizaveis no Gutenberg.
- `src/`: fonte de JavaScript e CSS compilado por Vite. `src/styles/main.css` importa o design system; `src/styles/theme.css` concentra adaptacoes WordPress.

## Fronteira de responsabilidade

O tema deve cuidar de apresentacao. Regras de negocio, tipos de conteudo permanentes, automacoes e integracoes que precisam sobreviver a troca de tema devem ir para plugin ou sistema externo.

O dominio de materiais gratuitos e fornecido pelo plugin `free-materials`, que registra `material_gratuito`, `material_categoria` e metadados canonicos. O tema apenas renderiza as superficies publicas desse dominio.

Adaptacoes locais que indiquem lacuna reutilizavel no design system devem seguir a politica de issues cruzadas descrita em `AGENTS.md`.
