import { mkdir, readFile, writeFile } from "node:fs/promises";
import { dirname, resolve } from "node:path";

const root = resolve(import.meta.dirname, "..");
const potPath = resolve(root, "languages/executive-signal-wordpress-theme.pot");
const poPath = resolve(root, "languages/pt_BR.po");

const pot = await readFile(potPath, "utf8");

const translations = new Map([
  ["Page not found", "Página não encontrada"],
  [
    "The requested page could not be found. Try searching for what you need.",
    "A página solicitada não foi encontrada. Tente buscar pelo que você precisa.",
  ],
  ["Executive Signal %s", "Executive Signal %s"],
  ["Blog", "Blog"],
  [
    "Controls the introductory copy shown on the posts page.",
    "Controla os textos introdutórios exibidos na página de posts.",
  ],
  ["Blog eyebrow", "Texto superior do blog"],
  ["Blog title", "Título do blog"],
  ["Blog description", "Descrição do blog"],
  ["Skip to content", "Pular para o conteúdo"],
  ["Primary menu", "Menu principal"],
  ["Open menu", "Abrir menu"],
  ["Open submenu", "Abrir submenu"],
  ["Close menu", "Fechar menu"],
  ["Executive Signal", "Executive Signal"],
  ["Briefings for sharper decisions.", "Briefings para decisões mais claras."],
  [
    "A running archive of strategic signals, operating notes and market context for leaders who need the useful part first.",
    "Um arquivo contínuo de sinais estratégicos, notas operacionais e contexto de mercado para líderes que precisam da parte útil primeiro.",
  ],
  ["Archive", "Arquivo"],
  ["Category", "Categoria"],
  ["Tag", "Tag"],
  ["Category articles", "Artigos da categoria"],
  ["Tag articles", "Artigos da tag"],
  ["Articles grouped under %s.", "Artigos agrupados em %s."],
  ["Signals tagged with %s.", "Sinais marcados com %s."],
  ["Author", "Autor"],
  [
    "Articles, notes and strategic signals published by this author.",
    "Artigos, notas e sinais estratégicos publicados por este autor.",
  ],
  ["Author website", "Site do autor"],
  ["Author articles", "Artigos do autor"],
  [
    "Browse articles grouped by topic, author or period.",
    "Navegue por artigos agrupados por tema, autor ou período.",
  ],
  ["Archive articles", "Artigos do arquivo"],
  ["Latest briefings", "Briefings mais recentes"],
  [
    "Browse the latest signals, notes and articles available in this archive.",
    "Navegue pelos sinais, notas e artigos mais recentes disponíveis neste arquivo.",
  ],
  ["Search", "Busca"],
  ["Search...", "Buscar..."],
  ["Cmd K", "Cmd K"],
  ["RSS", "RSS"],
  ["RSS feed", "Feed RSS"],
  [
    "Find briefings, operational notes and strategic signals across the archive.",
    "Encontre briefings, notas operacionais e sinais estratégicos em todo o arquivo.",
  ],
  ["Search results", "Resultados de busca"],
  ["Error 404", "Erro 404"],
  ["Helpful links", "Links úteis"],
  ["Return to homepage", "Voltar para a página inicial"],
  ["Browse the blog", "Ver o blog"],
  ["Popular topics", "Temas populares"],
  ["No results", "Sem resultados"],
  ["1 comment", "1 comentário"],
  ["%s comments", "%s comentários"],
  [
    "Join the conversation with context, questions or practical notes.",
    "Participe da conversa com contexto, perguntas ou notas práticas.",
  ],
  ["Comments pagination", "Paginação de comentários"],
  ["Previous comments", "Comentários anteriores"],
  ["Next comments", "Próximos comentários"],
  [
    "No comments yet. Be the first to add a useful signal.",
    "Ainda não há comentários. Seja a primeira pessoa a adicionar um sinal útil.",
  ],
  [
    "Add a clear comment that helps the next reader decide what matters.",
    "Adicione um comentário claro que ajude a próxima pessoa a decidir o que importa.",
  ],
  ["Publish comment", "Publicar comentário"],
  ["Your comment is awaiting moderation.", "Seu comentário está aguardando moderação."],
  ["Reply", "Responder"],
  ["Light", "Claro"],
  ["Dark", "Escuro"],
  ["System", "Sistema"],
  ["Theme", "Tema"],
  ["Change theme", "Mudar tema"],
  ["Theme mode", "Modo do tema"],
  ["Open submenu for %s", "Abrir submenu de %s"],
  ["Latest articles", "Artigos mais recentes"],
  ["Executive Signal hero", "Hero do Executive Signal"],
  [
    "Hero section for the Executive Signal homepage.",
    "Seção hero para a página inicial do Executive Signal.",
  ],
  ["Signal grid", "Grade de sinais"],
  ["Grid for editorial or operational signals.", "Grade para sinais editoriais ou operacionais."],
  ["Report preview", "Prévia de relatório"],
  ["Preview band for a report or briefing.", "Faixa de prévia para relatório ou briefing."],
  ["Executive Signal CTA", "CTA do Executive Signal"],
  ["Focused call to action section.", "Seção de chamada para ação focada."],
  ["Executive Signal landing page", "Landing page do Executive Signal"],
  ["Initial homepage composition.", "Composição inicial da página inicial."],
  ["Footer menu", "Menu do rodapé"],
  ["Executive solid", "Executive sólido"],
  ["Executive panel", "Executive painel"],
  ["Executive accent", "Executive destaque"],
  ["Footer column 1", "Coluna 1 do rodapé"],
  ["Footer column 2", "Coluna 2 do rodapé"],
  ["Footer column 3", "Coluna 3 do rodapé"],
  ["Footer column 4", "Coluna 4 do rodapé"],
  ["Footer bottom", "Linha final do rodapé"],
  ["Widgets added here appear in %s.", "Widgets adicionados aqui aparecem em %s."],
  ["Footer column %d", "Coluna %d do rodapé"],
  ["Page navigation", "Navegação da página"],
  ["Wide page", "Página ampla"],
  ["Centered page", "Página centralizada"],
  [
    "Copyright © %s - Developed by Rafael Carvalho®",
    "Copyright © %s - Desenvolvido por Rafael Carvalho®",
  ],
  ["Post information", "Informações do post"],
  ["Article information", "Informações do artigo"],
  ["Date", "Data"],
  ["By", "Por"],
  ["Article tags", "Tags do artigo"],
  ["Tagged in", "Tags"],
  ["Previous", "Anterior"],
  ["Next", "Próxima"],
  ["Posts pagination", "Paginação de posts"],
  ["Page %1$s of %2$s", "Página %1$s de %2$s"],
  ["Empty archive", "Arquivo vazio"],
  ["No articles yet", "Ainda não há artigos"],
  [
    "This archive does not have published articles yet. Browse the latest briefings instead.",
    "Este arquivo ainda não tem artigos publicados. Veja os briefings mais recentes.",
  ],
  ["Archive recovery links", "Links de recuperação do arquivo"],
  ["Browse latest articles", "Ver artigos mais recentes"],
  ["Turn signal into decision.", "Transforme sinal em decisão."],
  [
    "Use this section for a newsletter, briefing request or executive workflow call to action.",
    "Use esta seção para newsletter, solicitação de briefing ou chamada para ação de um fluxo executivo.",
  ],
  [
    "Decisions, signals and briefings with executive clarity.",
    "Decisões, sinais e briefings com clareza executiva.",
  ],
  [
    "A focused WordPress surface for publishing high-signal operational intelligence.",
    "Uma superfície WordPress focada para publicar inteligência operacional de alto sinal.",
  ],
  ["Weekly brief", "Brief semanal"],
  [
    "A concise view of what changed and what matters next.",
    "Uma visão concisa do que mudou e do que importa agora.",
  ],
  ["Signals worth attention", "Sinais que merecem atenção"],
  ["Market", "Mercado"],
  ["Track shifts before they become obvious.", "Acompanhe mudanças antes que elas fiquem óbvias."],
  ["Operation", "Operação"],
  ["Connect execution signals to decisions.", "Conecte sinais de execução a decisões."],
  ["Search results for: %s", "Resultados de busca para: %s"],
  ["Nothing found", "Nada encontrado"],
  ["Try a different search or return to the homepage.", "Tente uma busca diferente ou volte para a página inicial."],
  ["Read article", "Ler artigo"],
  ["Read next", "Leia também"],
  ["Related articles", "Artigos relacionados"],
  [
    "Continue with articles from the same editorial context.",
    "Continue com artigos do mesmo contexto editorial.",
  ],
  ["WhatsApp", "WhatsApp"],
  ["LinkedIn", "LinkedIn"],
  ["X", "X"],
  ["Share article", "Compartilhar artigo"],
  ["Copy link", "Copiar link"],
  ["Copied", "Copiado"],
  ["Article navigation", "Navegação do artigo"],
  ["Previous article", "Artigo anterior"],
  ["Next article", "Próximo artigo"],
  ["Text primary", "Texto principal"],
  ["Background", "Fundo"],
  ["Panel", "Painel"],
  ["Accent", "Destaque"],
  ["Warning", "Aviso"],
  ["Text tertiary", "Texto terciário"],
]);

const pluralTranslations = new Map([
  [
    "%s published article",
    {
      one: "%s artigo publicado",
      other: "%s artigos publicados",
    },
  ],
  [
    "%s article found",
    {
      one: "%s artigo encontrado",
      other: "%s artigos encontrados",
    },
  ],
  [
    "%s article",
    {
      one: "%s artigo",
      other: "%s artigos",
    },
  ],
  [
    "%s result found",
    {
      one: "%s resultado encontrado",
      other: "%s resultados encontrados",
    },
  ],
]);

const escapePoString = (value) => value.replaceAll("\\", "\\\\").replaceAll('"', '\\"');

const output = pot
  .replace(
    '"Language-Team: Executive Signal\\n"\n',
    '"Language-Team: Executive Signal\\n"\n"Language: pt_BR\\n"\n"Plural-Forms: nplurals=2; plural=(n > 1);\\n"\n',
  )
  .replaceAll(
    /msgid "((?:[^"\\]|\\.)*)"\nmsgid_plural "((?:[^"\\]|\\.)*)"\nmsgstr\[0\] ""\nmsgstr\[1\] ""\n/g,
    (match, singular) => {
      const translation = pluralTranslations.get(singular);

      if (!translation) {
        return match;
      }

      return `msgid "${singular}"\nmsgid_plural "${match.match(/msgid_plural "((?:[^"\\]|\\.)*)"/)[1]}"\nmsgstr[0] "${escapePoString(translation.one)}"\nmsgstr[1] "${escapePoString(translation.other)}"\n`;
    },
  )
  .replaceAll(/msgstr ""\n(?=\n|\n#|\nmsgid)/g, (match, offset, source) => {
    const before = source.slice(0, offset);
    const msgidMatch = before.match(/msgid "((?:[^"\\]|\\.)*)"\n(?:msgid_plural "((?:[^"\\]|\\.)*)"\n)?$/);

    if (!msgidMatch || msgidMatch[1] === "") {
      return match;
    }

    if (msgidMatch[2]) {
      const translation = pluralTranslations.get(msgidMatch[1]);

      if (translation) {
        return `msgstr[0] "${escapePoString(translation.one)}"\nmsgstr[1] "${escapePoString(translation.other)}"\n`;
      }

      return `msgstr[0] "${msgidMatch[1]}"\nmsgstr[1] "${msgidMatch[2]}"\n`;
    }

    return `msgstr "${escapePoString(translations.get(msgidMatch[1]) ?? msgidMatch[1])}"\n`;
  });

await mkdir(dirname(poPath), { recursive: true });
await writeFile(poPath, output);

console.log(`Synced ${poPath}`);
