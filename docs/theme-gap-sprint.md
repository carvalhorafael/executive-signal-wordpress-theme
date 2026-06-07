# Sprint de lacunas do tema

Este documento acompanha as principais lacunas identificadas na varredura do tema WordPress. A intencao e manter o contexto do sprint versionado e marcar cada frente conforme for resolvida.

## Checklist

- [x] Extrair o dominio de materiais gratuitos para um plugin dedicado.
- [ ] Fechar o contrato de captura/Brevo usado pelo formulario de materiais gratuitos.
- [ ] Aprofundar `theme.json` para cobrir estilos padrao de blocos e elementos que hoje vivem apenas em CSS.
- [ ] Ampliar o smoke de captura para testar submissao ou falha controlada.

## 1. Extrair materiais gratuitos para plugin

Status: resolvido na extracao para `/Users/rafaelcarvalho/Development/plugins-wordpress/free-materials`. O tema agora consome o plugin `free-materials` no ambiente local/testes e nao registra mais `material_gratuito`, `material_categoria` nem metadados.

### Problema atual

O tema registra o post type `material_gratuito`, a taxonomia `material_categoria`, metadados de captura e regras de rewrite em `inc/free-materials.php`.

Isso funciona para o site atual, mas e uma excecao a fronteira arquitetural do projeto: o tema deve ser camada de apresentacao, enquanto tipos de conteudo, taxonomias e metadados duraveis devem sobreviver a troca de tema. O Theme Check tambem classifica `register_post_type()` e `register_taxonomy()` dentro de tema como plugin territory.

### Contrato desejado

Criar um plugin dedicado, por exemplo `free-materials`, responsavel pelo dominio persistente de materiais gratuitos.

O slug pode ser generico se o plugin for privado/controlado por este projeto. Internamente, funcoes, constantes, hooks e options ainda devem usar um prefixo unico, como `executive_signal_free_materials_`, para evitar conflito com outros plugins WordPress.

O plugin deve ser dono de:

- registro do post type `material_gratuito`;
- registro da taxonomia `material_categoria`;
- registro dos metadados:
  - `_executive_signal_material_capture_label`;
  - `_brevo_leads_capture_list_id`;
  - `_brevo_leads_capture_delivery_url`;
- meta box ou UI editorial equivalente para esses metadados;
- regras de rewrite do dominio e flush controlado na ativacao/desativacao;
- constantes ou funcoes publicas estaveis para o tema consumir sem duplicar strings canonicas.

O tema deve continuar dono de:

- templates `single-material_gratuito.php`, `taxonomy-material_categoria.php` e `page-materiais-gratuitos.php`;
- markup, classes, layout e adaptacao ao Executive Signal Design System;
- helpers estritamente de apresentacao, como renderizacao de termos e CTA;
- fallbacks defensivos de constantes apenas para evitar erro fatal quando o plugin estiver ausente.

### Como funcionaria em runtime

1. O plugin carrega antes do tema no ciclo normal do WordPress.
2. No hook `init`, o plugin registra `material_gratuito`, `material_categoria`, metadados e rewrites.
3. O tema detecta se o plugin ou o post type estao disponiveis.
4. Se estiverem disponiveis, o tema renderiza os templates especializados normalmente.
5. Se nao estiverem disponiveis, o tema nao tenta registrar nem emular o dominio por conta propria; materiais gratuitos simplesmente nao existem como superficie funcional ate que o plugin esteja instalado e ativo.

### Plano de migracao

1. Criar o plugin com header proprio, namespace/prefixo unico e versionamento separado.
2. Mover para o plugin o registro de CPT, taxonomia, metadados, meta box, save handler e flush de rewrite.
3. Expor funcoes/constantes publicas estaveis do plugin para o tema, evitando que o tema precise conhecer detalhes duplicados.
4. Alterar o tema para remover `register_post_type()`, `register_taxonomy()`, `register_post_meta()`, meta box e save handler.
5. Manter no tema apenas helpers de leitura e renderizacao, usando constantes do plugin quando existirem e fallbacks seguros quando nao existirem.
6. Atualizar testes:
   - testes do plugin cobrem registro de CPT/taxonomia/metadados/meta box/rewrite;
   - testes do tema cobrem consumo do contrato com o plugin ativo;
   - E2E continua validando as superficies publicas com o plugin ativo.
7. Atualizar `wp-env`/CI para instalar e ativar o plugin durante testes do tema.
8. Remover as excecoes de plugin territory em `scripts/run-theme-check.php`.
9. Atualizar docs de arquitetura e decisoes, marcando a excecao antiga como resolvida.

### Criterios de pronto

- [x] `npm run theme:check` no tema nao precisa mais ignorar `register_post_type()` nem `register_taxonomy()`.
- [x] Desativar o tema nao remove a disponibilidade administrativa dos materiais gratuitos quando o plugin continua ativo.
- [x] O tema renderiza materiais gratuitos com o plugin ativo.
- [x] O tema nao registra nem tenta emular materiais gratuitos quando o plugin esta ausente.
- [x] Os slugs e metadados existentes continuam identicos, sem migracao destrutiva de dados.
- [x] `npm test` e `npm run validate` passam no tema.
- [x] O plugin tem suite minima propria para CPT, taxonomia, metadados e rewrite.

### Decisoes em aberto

- Repositorio do plugin: criado em `/Users/rafaelcarvalho/Development/plugins-wordpress/free-materials`.
- Distribuicao do plugin: estrutura criada para release propria via GitHub.
- Captura/Brevo: o plugin `free-materials` nao processa submissao; a captura continua como decisao separada.
- Dependencia administrativa: avaliar se o tema deve exibir apenas um aviso discreto no admin recomendando instalar/ativar `free-materials`.

## 2. Fechar contrato de captura/Brevo

Status: resolvido no tema. O processamento pertence ao plugin `brevo-leads-capture`; o tema apenas renderiza o formulario conforme o contrato publico desse plugin.

Contrato no tema:

- postar para `admin_url( 'admin-post.php' )`;
- enviar `action=brevo_leads_capture_free_material`;
- gerar nonce para `brevo_leads_capture_free_material`;
- enviar `material_id`;
- renderizar campos `name`, `email` e `whatsapp`;
- renderizar honeypot vazio `brevo_leads_capture_website`;
- encaminhar UTMs suportadas quando existirem;
- renderizar a mensagem publica do plugin perto do formulario quando `brevo-leads-capture` estiver ativo;
- nao registrar handler, nao falar com Brevo e nao mapear codigos de erro dentro do tema.

## 3. Aprofundar `theme.json`

O tema ja usa `theme.json` v3 para paleta, tipografia, spacing e layout. O proximo passo e revisar `src/styles/theme.css` e mover estilos padrao de blocos/elementos para `theme.json` quando isso reduzir especificidade e melhorar a experiencia no editor.

## 4. Ampliar smoke de captura

Os testes E2E validam que o formulario existe, mas ainda nao validam o resultado da submissao. Depois de fechar o contrato de captura, o smoke deve cobrir sucesso ou falha controlada.
