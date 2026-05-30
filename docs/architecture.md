# Arquitetura

O `executive-signal-wordpress-theme` e um tema WordPress hibrido: usa templates PHP classicos para roteamento e composicao base, mas apoia o editor de blocos com `theme.json`, block patterns e editor styles.

## Camadas

- `functions.php`: bootstrap minimo, constantes e carregamento dos modulos.
- `inc/setup.php`: theme supports, menus e editor styles.
- `inc/assets.php` e `inc/vite.php`: carregamento de assets em desenvolvimento e producao.
- `inc/patterns.php`: registro dos block patterns do Executive Signal.
- `inc/template-tags.php`: helpers compartilhados de template.
- `inc/updater.php`: integracao de update via GitHub Releases para distribuicao fora do WordPress.org.
- `template-parts/`: markup reutilizavel para paginas, posts e estados vazios.
- `patterns/`: composicoes editoriais reutilizaveis no Gutenberg.
- `src/`: fonte de JavaScript e CSS compilado por Vite.

## Fronteira de responsabilidade

O tema deve cuidar de apresentacao. Regras de negocio, tipos de conteudo permanentes, automacoes e integracoes que precisam sobreviver a troca de tema devem ir para plugin ou sistema externo.
