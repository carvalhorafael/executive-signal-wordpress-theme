---
name: Executive Signal WordPress Theme
description: Superfícies editoriais e comerciais para decisões executivas com clareza.
colors:
  background: "oklch(97% 0.008 250deg)"
  surface: "oklch(99% 0.006 250deg / 0.98)"
  surface-strong: "oklch(94% 0.012 250deg / 0.98)"
  text-primary: "oklch(18% 0.018 250deg)"
  text-secondary: "oklch(42% 0.028 255deg)"
  text-tertiary: "oklch(55% 0.03 255deg)"
  accent: "oklch(46% 0.115 116deg)"
  danger: "oklch(52% 0.11 10deg)"
typography:
  display:
    fontFamily: "Avenir Next, Segoe UI, sans-serif"
    fontSize: "clamp(3rem, 6.4vw, 6.5rem)"
    fontWeight: 600
    lineHeight: 0.98
    letterSpacing: "-0.045em"
  headline:
    fontFamily: "Avenir Next, Segoe UI, sans-serif"
    fontSize: "clamp(2rem, 4vw, 3.75rem)"
    fontWeight: 600
    lineHeight: 1.05
    letterSpacing: "-0.03em"
  body:
    fontFamily: "Avenir Next, Segoe UI, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
    lineHeight: 1.65
  label:
    fontFamily: "Avenir Next, Segoe UI, sans-serif"
    fontSize: "0.75rem"
    fontWeight: 560
    lineHeight: 1.2
    letterSpacing: "0.14em"
rounded:
  item: "0.5rem"
  control: "0.625rem"
  panel: "0.75rem"
  pill: "999px"
spacing:
  xs: "0.5rem"
  sm: "0.75rem"
  md: "1.5rem"
  lg: "2.5rem"
  xl: "4rem"
components:
  button-primary:
    backgroundColor: "{colors.accent}"
    textColor: "{colors.surface}"
    rounded: "{rounded.control}"
    padding: "0.875rem 1.25rem"
  panel:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text-primary}"
    rounded: "{rounded.panel}"
    padding: "1.5rem"
---

# Design System: Executive Signal WordPress Theme

## Overview

**Creative North Star: "A sessão executiva estruturada"**

O Executive Signal deve parecer uma conversa de trabalho entre lideranças experientes: precisa, calma e orientada a decisões. A estrutura visual cria ritmo e confiança por meio de um grid rigoroso, respiro generoso e contraste tipográfico, sem recorrer à teatralidade de uma landing page de infoproduto.

Nas superfícies comerciais, a página conduz do sintoma à decisão. O design não tenta provar autoridade com decoração. Ele a demonstra pela clareza com que organiza problemas, consequências, escolhas e próximos passos.

**Key Characteristics:**

- Hierarquia tipográfica firme e concisa.
- Superfícies claras com neutros frios e accent oliva usado como sinal.
- Alternância de ritmo em vez de uma sequência uniforme de cards.
- Componentes responsivos, acessíveis e visualmente estáveis.
- Fotografia autoral quando a pessoa é parte essencial da oferta.

## Colors

A paleta combina neutros azulados muito claros com texto profundo e um accent oliva raro, reservado para ação, orientação e estado.

### Primary

- **Oliva de decisão:** identifica ações principais, links e pontos de orientação.

### Neutral

- **Papel frio:** fundo geral com luminosidade alta sem branco puro.
- **Superfície executiva:** áreas elevadas e formulários.
- **Grafite azulado:** texto principal e títulos.
- **Ardósia:** texto de apoio e metadados.

### Named Rules

**The Signal Rule.** O accent não decora. Ele aponta uma ação, uma decisão ou uma mudança de estado.

## Typography

**Display Font:** Avenir Next, com Segoe UI e sans-serif como fallback

**Body Font:** Avenir Next, com Segoe UI e sans-serif como fallback
**Label/Mono Font:** o mono do sistema é restrito a dados técnicos e não participa da narrativa comercial.

**Character:** uma única família humanista sustenta a página com variação forte de escala e peso. A voz é contemporânea e executiva, sem assumir a aparência editorial de uma revista.

### Hierarchy

- **Display:** títulos de abertura, com poucas linhas e quebra deliberada.
- **Headline:** títulos de seção que revelam uma conclusão, não rótulos genéricos.
- **Body:** leitura limitada a aproximadamente 68 caracteres por linha.
- **Label:** apenas marcações curtas, com tracking controlado e caixa alta.

### Named Rules

**The Diagnostic Headline Rule.** Todo título deve avançar o raciocínio da página. Títulos que apenas repetem o assunto são proibidos.

## Elevation

O sistema usa profundidade híbrida. A hierarquia nasce primeiro de contraste tonal e bordas discretas. Sombras suaves aparecem em formulários, painéis de decisão e estados interativos, nunca para transformar todo conteúdo em objeto flutuante.

### Shadow Vocabulary

- **Baixa:** separação sutil para controles e elementos interativos.
- **Média:** formulário e superfícies de conversão.
- **Alta:** reservada a uma única superfície dominante por região.

### Named Rules

**The Flat Narrative Rule.** Conteúdo narrativo permanece no fluxo da página. Elevação é reservada para interação ou decisão.

## Components

### Buttons

- **Shape:** cantos controlados, não pílulas indiscriminadas.
- **Primary:** accent sólido, contraste alto e texto direto.
- **Hover / Focus:** mudança tonal clara e anel de foco visível.
- **Secondary:** superfície neutra com borda, sem competir com a ação principal.

### Cards / Containers

- **Corner Style:** painéis suavemente arredondados.
- **Background:** superfície ou superfície forte, conforme hierarquia.
- **Shadow Strategy:** tonalidade primeiro, sombra depois.
- **Border:** uma borda de baixo contraste.
- **Internal Padding:** fluido, de 1rem em telas estreitas a 2rem em telas amplas.

### Inputs / Fields

- **Style:** rótulo persistente, superfície clara, borda visível e altura confortável para toque.
- **Focus:** borda reforçada e anel de accent.
- **Error / Disabled:** mensagem próxima ao campo ou formulário, anunciada por tecnologia assistiva.

### Navigation

O header comercial usa poucos destinos, texto curto e um CTA. Em telas pequenas, os links secundários cedem espaço ao logo e à ação principal.

### Diagnostic Map

Uma visualização simples mostra decisões e riscos convergindo para o fundador. Ela explica dependência operacional sem gráficos falsos ou métricas inventadas.

## Do's and Don'ts

### Do:

- **Do** começar pelo sintoma reconhecível e conduzir a uma conclusão executiva.
- **Do** usar o accent oliva apenas para ações e sinais reais.
- **Do** manter corpo de texto entre 65 e 75 caracteres por linha.
- **Do** usar fotografia real de Rafael em vez de banco de imagens.
- **Do** preservar foco visível, navegação por teclado e redução de movimento.

### Don't:

- **Don't** criar uma landing page agressiva de infoproduto.
- **Don't** reproduzir consultoria corporativa genérica, com promessas abstratas e imagens de banco.
- **Don't** montar a página como uma grade repetitiva de cards SaaS.
- **Don't** vender um framework como solução universal.
- **Don't** usar buzzwords, urgência artificial, contadores, garantias ou claims sem evidência.
- **Don't** usar texto em gradiente, glassmorphism decorativo ou faixas laterais coloridas.
