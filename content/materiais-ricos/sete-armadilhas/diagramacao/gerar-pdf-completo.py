#!/usr/bin/env python3
"""Gera a versão completa do material 7 Armadilhas."""

from __future__ import annotations

import importlib.util
import re
from dataclasses import dataclass
from pathlib import Path
from xml.sax.saxutils import escape

from reportlab.lib.pagesizes import A4
from reportlab.lib.units import mm
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfgen import canvas


ROOT = Path(__file__).resolve().parents[4]
CONTENT = ROOT / "content/materiais-ricos/sete-armadilhas/conteudo.md"
VISUAL_SOURCE = Path(__file__).with_name("gerar-prova-visual.py")
OUTPUT = ROOT / "output/pdf/7-armadilhas-fundador-operacao.pdf"
COO_LP_URL = "https://rafaelcarvalho.tv/coo-as-a-service/"


def load_visual_system():
    spec = importlib.util.spec_from_file_location("sete_armadilhas_visual", VISUAL_SOURCE)
    if spec is None or spec.loader is None:
        raise RuntimeError(f"Não foi possível carregar {VISUAL_SOURCE}")
    module = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(module)
    return module


V = load_visual_system()
PAGE_W, PAGE_H = A4
MARGIN = 20 * mm


@dataclass
class Chapter:
    number: int
    title: str
    sections: dict[str, str]


def section(markdown: str, title: str) -> str:
    match = re.search(
        rf"^## {re.escape(title)}\s*$\n\n(.*?)(?=^## |\Z)",
        markdown,
        flags=re.MULTILINE | re.DOTALL,
    )
    if not match:
        raise ValueError(f"Seção não encontrada: {title}")
    return match.group(1).strip()


def parse_content() -> tuple[list[str], str, list[Chapter], dict[str, str]]:
    markdown = CONTENT.read_text(encoding="utf-8")
    intro_start = markdown.index("Uma empresa raramente nasce")
    intro_end = markdown.index("\n## Como usar este material")
    intro = [part.strip() for part in re.split(r"\n\s*\n", markdown[intro_start:intro_end]) if part.strip()]

    chapter_pattern = re.compile(
        r"^## (\d+)\. (.+?)\n\n(.*?)(?=^---\s*$)",
        flags=re.MULTILINE | re.DOTALL,
    )
    chapters: list[Chapter] = []
    for match in chapter_pattern.finditer(markdown):
        number = int(match.group(1))
        if not 1 <= number <= 7:
            continue
        body = match.group(3).strip()
        subsections: dict[str, str] = {}
        for submatch in re.finditer(
            r"^### (.+?)\n\n(.*?)(?=^### |\Z)",
            body,
            flags=re.MULTILINE | re.DOTALL,
        ):
            subsections[submatch.group(1).strip()] = submatch.group(2).strip()
        if len(subsections) != 6:
            raise ValueError(f"Armadilha {number} possui {len(subsections)} subseções")
        chapters.append(Chapter(number, match.group(2).strip(), subsections))

    if len(chapters) != 7:
        raise ValueError(f"Esperadas 7 armadilhas; encontradas {len(chapters)}")

    closing = {
        "attention": section(markdown, "Qual armadilha merece atenção primeiro?"),
        "exercise": section(markdown, "Um exercício para a próxima semana"),
        "next": section(markdown, "O próximo passo é aprofundar o diagnóstico"),
        "conversation": section(markdown, "Quando faz sentido conversar sobre a operação"),
    }
    return intro, section(markdown, "Como usar este material"), chapters, closing


def style(name: str, size: float, leading: float, color, font: str = "AvenirNext"):
    return V.paragraph_style(name, size, leading, color, font)


def safe_markup(text: str, paragraph_style, width: float, safety: float = 1.28) -> str:
    """Insere quebras conservadoras para compensar métricas do TTC no PDF."""
    words = text.strip().replace("\n", " ").split()
    if not words:
        return ""
    font = paragraph_style.fontName
    size = paragraph_style.fontSize
    limit = width / safety
    lines: list[str] = []
    current: list[str] = []
    for word in words:
        candidate = " ".join([*current, word])
        if current and pdfmetrics.stringWidth(candidate, font, size) > limit:
            lines.append(" ".join(current))
            current = [word]
        else:
            current.append(word)
    if current:
        lines.append(" ".join(current))
    return "<br/>".join(escape(line) for line in lines)


def split_blocks(text: str) -> list[str]:
    return [
        block.strip()
        for block in re.split(r"\n\s*\n", text.strip())
        if block.strip() and block.strip() != "---"
    ]


def draw_blocks(
    page: canvas.Canvas,
    text: str,
    x: float,
    top: float,
    width: float,
    body_style,
    *,
    bullet_style=None,
    gap: float = 4 * mm,
    min_y: float = 24 * mm,
) -> float:
    y = top
    bullet_style = bullet_style or body_style
    for block in split_blocks(text):
        lines = [line.strip() for line in block.splitlines() if line.strip()]
        if lines and all(line.startswith("- ") for line in lines):
            for line in lines:
                page.setFillColor(V.OLIVE)
                page.circle(x + 1.2 * mm, y - 2.1 * mm, 1 * mm, stroke=0, fill=1)
                height = V.draw_paragraph(
                    page,
                    safe_markup(line[2:], bullet_style, width - 6 * mm),
                    x + 6 * mm,
                    y,
                    width - 6 * mm,
                    bullet_style,
                )
                y -= height + 3 * mm
        elif lines and all(re.match(r"^\d+\. ", line) for line in lines):
            for line in lines:
                number, label = line.split(". ", 1)
                page.setFillColor(V.OLIVE)
                page.setFont("AvenirNext-Demi", 8.4)
                page.drawString(x, y - 3.2 * mm, number.zfill(2))
                height = V.draw_paragraph(
                    page,
                    safe_markup(label, bullet_style, width - 10 * mm),
                    x + 10 * mm,
                    y,
                    width - 10 * mm,
                    bullet_style,
                )
                y -= height + 3.5 * mm
        elif lines and all(line.startswith(">") for line in lines):
            quote = " ".join(line.lstrip("> ") for line in lines)
            height = V.draw_paragraph(page, safe_markup(quote, body_style, width), x, y, width, body_style)
            y -= height + gap
        else:
            paragraph = " ".join(lines)
            height = V.draw_paragraph(page, safe_markup(paragraph, body_style, width), x, y, width, body_style)
            y -= height + gap
        if y < min_y:
            raise ValueError(f"Conteúdo ultrapassou a área segura da página: {y / mm:.1f} mm")
    return y


def draw_section_heading(page: canvas.Canvas, text: str, x: float, top: float) -> float:
    heading_style = style("section-heading", 12, 14.5, V.INK, "AvenirNext-Demi")
    height = V.draw_paragraph(page, escape(text), x, top, PAGE_W - x - MARGIN, heading_style)
    return top - height - 4 * mm


def draw_page_header(page: canvas.Canvas, label: str, title: str, number: int, width: float = 150 * mm) -> float:
    V.page_background(page)
    V.draw_label(page, label, MARGIN, PAGE_H - 22 * mm, V.OLIVE)
    title_style = style("page-title", 25, 28, V.INK, "AvenirNext-Demi")
    height = V.draw_paragraph(page, escape(title), MARGIN, PAGE_H - 34 * mm, width, title_style)
    V.footer(page, number)
    return PAGE_H - 34 * mm - height - 12 * mm


def draw_intro_one(page: canvas.Canvas, paragraphs: list[str], page_number: int) -> None:
    y = draw_page_header(page, "Tese central · 01", "A empresa cresceu. O modelo de gestão acompanhou?", page_number)
    body = style("intro-one", 9.6, 14.4, V.SLATE)
    y = draw_blocks(page, "\n\n".join(paragraphs[:4]), MARGIN, y, 154 * mm, body, gap=3.5 * mm, min_y=95 * mm)

    labels = [
        ["MAIS PESSOAS E ÁREAS"],
        ["MAIS DECISÕES"],
        ["MESMO TEMPO", "DO FUNDADOR"],
    ]
    box_y = 49 * mm
    box_h = 18 * mm
    box_w = 48 * mm
    gap = 5 * mm
    for index, lines in enumerate(labels):
        x = MARGIN + index * (box_w + gap)
        page.setFillColor(V.SURFACE if index < 2 else V.INK)
        page.setStrokeColor(V.BORDER)
        page.roundRect(x, box_y, box_w, box_h, 3 * mm, stroke=int(index < 2), fill=1)
        line_gap = 4 * mm
        first_center = box_y + box_h / 2 + (line_gap / 2 if len(lines) == 2 else 0)
        for line_index, label in enumerate(lines):
            V.draw_centered_line(
                page,
                label,
                x + box_w / 2,
                first_center - line_index * line_gap,
                "AvenirNext-Demi",
                6.6,
                V.SLATE if index < 2 else V.LIGHT_INK,
            )
        if index < 2:
            page.setStrokeColor(V.OLIVE)
            page.setLineWidth(1)
            start = x + box_w
            page.line(start, box_y + box_h / 2, start + gap, box_y + box_h / 2)


def draw_intro_two(page: canvas.Canvas, paragraphs: list[str], page_number: int) -> None:
    y = draw_page_header(
        page,
        "Tese central · 02",
        "É nesse momento que uma vantagem inicial pode se transformar em gargalo.",
        page_number,
    )
    body = style("intro-two", 10, 15, V.SLATE)
    y = draw_blocks(page, "\n\n".join(paragraphs[4:]), MARGIN, y, 154 * mm, body, gap=4 * mm, min_y=82 * mm)

    quote_y = 31 * mm
    quote_h = 39 * mm
    page.setFillColor(V.oklch(0.93, 0.025, 116))
    page.roundRect(MARGIN, quote_y, PAGE_W - 2 * MARGIN, quote_h, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Diagnóstico", MARGIN + 7 * mm, quote_y + 29 * mm, V.OLIVE)
    quote_style = style("intro-quote", 12.4, 15.5, V.INK, "AvenirNext-Demi")
    V.draw_paragraph(
        page,
        "O problema não é apenas excesso de trabalho.<br/>É uma operação que cresceu além do modelo de gestão<br/>que a trouxe até aqui.",
        MARGIN + 7 * mm,
        quote_y + 23 * mm,
        PAGE_W - 2 * MARGIN - 14 * mm,
        quote_style,
    )


def draw_how_to(page: canvas.Canvas, text: str, chapters: list[Chapter], page_number: int) -> None:
    y = draw_page_header(page, "Orientação de leitura", "Como usar este material", page_number)
    body = style("how-body", 9.5, 14.3, V.SLATE)
    y = draw_blocks(page, text, MARGIN, y, 154 * mm, body, gap=3.5 * mm, min_y=102 * mm)

    panel_y = 29 * mm
    panel_h = 64 * mm
    page.setFillColor(V.SURFACE_STRONG)
    page.roundRect(MARGIN, panel_y, PAGE_W - 2 * MARGIN, panel_h, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Mapa do material", MARGIN + 7 * mm, panel_y + panel_h - 10 * mm, V.OLIVE)
    item_style = style("map-item", 7.7, 10.4, V.SLATE, "AvenirNext-Medium")
    for index, chapter in enumerate(chapters):
        column = 0 if index < 4 else 1
        row = index if index < 4 else index - 4
        x = MARGIN + 7 * mm + column * 82 * mm
        top = panel_y + panel_h - 20 * mm - row * 10 * mm
        page.setFillColor(V.OLIVE)
        page.setFont("AvenirNext-Demi", 7.5)
        page.drawString(x, top - 2.8 * mm, f"{chapter.number:02d}")
        V.draw_paragraph(
            page,
            safe_markup(chapter.title, item_style, 67 * mm),
            x + 9 * mm,
            top,
            67 * mm,
            item_style,
        )


def chapter_header(page: canvas.Canvas, chapter: Chapter, suffix: str, page_number: int) -> float:
    V.page_background(page)
    V.draw_label(page, f"Armadilha {chapter.number:02d} · {suffix}", MARGIN, PAGE_H - 22 * mm, V.OLIVE)
    page.setFillColor(V.OLIVE)
    page.setFont("AvenirNext-Bold", 50)
    page.drawRightString(PAGE_W - MARGIN, PAGE_H - 38 * mm, f"{chapter.number:02d}")
    title_style = style(f"chapter-{chapter.number}-title", 23, 26, V.INK, "AvenirNext-Demi")
    height = V.draw_paragraph(page, escape(chapter.title), MARGIN, PAGE_H - 36 * mm, 132 * mm, title_style)
    V.footer(page, page_number)
    return PAGE_H - 36 * mm - height - 16 * mm


def draw_chapter_context(page: canvas.Canvas, chapter: Chapter, page_number: int) -> None:
    y = chapter_header(page, chapter, "contexto", page_number)
    body = style(f"context-{chapter.number}", 9.3, 14, V.SLATE)
    y = draw_section_heading(page, "Como a armadilha aparece", MARGIN, y)
    y = draw_blocks(
        page,
        chapter.sections["Como a armadilha aparece"],
        MARGIN,
        y,
        154 * mm,
        body,
        gap=3.5 * mm,
        min_y=103 * mm,
    )
    page.setStrokeColor(V.BORDER)
    page.setLineWidth(0.45)
    page.line(MARGIN, y - 2 * mm, PAGE_W - MARGIN, y - 2 * mm)
    y -= 12 * mm
    y = draw_section_heading(page, "Por que parece uma boa resposta", MARGIN, y)
    draw_blocks(
        page,
        chapter.sections["Por que parece uma boa resposta"],
        MARGIN,
        y,
        154 * mm,
        body,
        gap=3.5 * mm,
        min_y=24 * mm,
    )


def draw_chapter_mechanism(page: canvas.Canvas, chapter: Chapter, page_number: int) -> None:
    y = draw_page_header(
        page,
        f"Armadilha {chapter.number:02d} · mecanismo",
        "O mecanismo que mantém o fundador preso",
        page_number,
    )
    body = style(f"mechanism-{chapter.number}", 9.5, 14.3, V.SLATE)
    y = draw_blocks(
        page,
        chapter.sections["O mecanismo que mantém o fundador preso"],
        MARGIN,
        y,
        154 * mm,
        body,
        gap=3.5 * mm,
        min_y=124 * mm,
    )

    panel_top = y - 5 * mm
    panel_bottom = 29 * mm
    page.setFillColor(V.SURFACE_STRONG)
    page.roundRect(MARGIN, panel_bottom, PAGE_W - 2 * MARGIN, panel_top - panel_bottom, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Sinais observáveis", MARGIN + 7 * mm, panel_top - 11 * mm, V.OLIVE)
    bullet = style(f"signals-{chapter.number}", 8.8, 12.8, V.SLATE)
    draw_blocks(
        page,
        chapter.sections["Sinais observáveis"],
        MARGIN + 7 * mm,
        panel_top - 22 * mm,
        PAGE_W - 2 * MARGIN - 14 * mm,
        bullet,
        gap=2.5 * mm,
        min_y=panel_bottom + 6 * mm,
    )


def draw_chapter_decision(page: canvas.Canvas, chapter: Chapter, page_number: int) -> None:
    V.page_background(page)
    V.draw_label(page, f"Armadilha {chapter.number:02d} · decisão", MARGIN, PAGE_H - 22 * mm, V.OLIVE)
    title_style = style(f"decision-title-{chapter.number}", 25, 28, V.INK, "AvenirNext-Demi")
    V.draw_paragraph(page, "O que precisa mudar", MARGIN, PAGE_H - 34 * mm, 150 * mm, title_style)

    question = chapter.sections["Pergunta de diagnóstico"].lstrip("> ").strip()
    box_top = PAGE_H - 67 * mm
    box_h = 43 * mm
    page.setFillColor(V.INK)
    page.roundRect(MARGIN, box_top - box_h, PAGE_W - 2 * MARGIN, box_h, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Pergunta de diagnóstico", MARGIN + 7 * mm, box_top - 11 * mm, V.OLIVE_ON_DARK)
    question_style = style(f"question-{chapter.number}", 12.6, 16, V.LIGHT_INK, "AvenirNext-Demi")
    V.draw_paragraph(
        page,
        safe_markup(question, question_style, PAGE_W - 2 * MARGIN - 14 * mm),
        MARGIN + 7 * mm,
        box_top - 18 * mm,
        PAGE_W - 2 * MARGIN - 14 * mm,
        question_style,
    )

    y = box_top - box_h - 12 * mm
    y = draw_section_heading(page, "Mudança a considerar", MARGIN, y)
    change = chapter.sections["Mudança a considerar"]
    length = len(change)
    body_size = 8.6 if length > 900 else 9.1
    leading = 12.7 if length > 900 else 13.7
    body = style(f"change-{chapter.number}", body_size, leading, V.SLATE)
    draw_blocks(
        page,
        change,
        MARGIN,
        y,
        154 * mm,
        body,
        gap=3.1 * mm,
        min_y=25 * mm,
    )
    V.footer(page, page_number)


def draw_attention(page: canvas.Canvas, text: str, page_number: int) -> None:
    y = draw_page_header(page, "Síntese", "Qual armadilha merece atenção primeiro?", page_number)
    body = style("attention-body", 8.9, 13.4, V.SLATE)
    draw_blocks(page, text, MARGIN, y, 154 * mm, body, gap=3.2 * mm, min_y=24 * mm)


def draw_exercise(page: canvas.Canvas, text: str, page_number: int) -> None:
    V.page_background(page)
    V.draw_label(page, "Exercício prático", MARGIN, PAGE_H - 22 * mm, V.OLIVE)
    title_style = style("exercise-title", 25, 28, V.INK, "AvenirNext-Demi")
    V.draw_paragraph(
        page,
        "Transforme a sensação de sobrecarga<br/>em evidência operacional",
        MARGIN,
        PAGE_H - 34 * mm,
        157 * mm,
        title_style,
    )

    intro, remainder = text.split("Para cada ocorrência, anote:", 1)
    fields_text, closing = remainder.split("Ao final da semana, procure padrões.", 1)
    intro_style = style("exercise-intro-full", 9.2, 13.8, V.SLATE)
    V.draw_paragraph(
        page,
        safe_markup(intro, intro_style, 154 * mm),
        MARGIN,
        PAGE_H - 77 * mm,
        154 * mm,
        intro_style,
    )

    fields = [line[2:].strip() for line in fields_text.splitlines() if line.strip().startswith("- ")]
    y = PAGE_H - 103 * mm
    heights = [18, 18, 18, 18, 18, 18]
    for index, (label, height_mm) in enumerate(zip(fields, heights), start=1):
        height = height_mm * mm
        page.setFillColor(V.SURFACE)
        page.setStrokeColor(V.BORDER)
        page.setLineWidth(0.55)
        page.roundRect(MARGIN, y - height, PAGE_W - 2 * MARGIN, height, 3 * mm, stroke=1, fill=1)
        center_y = y - height / 2
        page.setFillColor(V.OLIVE)
        page.setFont("AvenirNext-Demi", 8.6)
        page.drawString(MARGIN + 5 * mm, V.centered_baseline("AvenirNext-Demi", 8.6, center_y), f"{index:02d}")
        label_style = style(f"exercise-field-{index}", 7.9, 10.6, V.INK, "AvenirNext-Medium")
        V.draw_paragraph(
            page,
            safe_markup(label, label_style, 137 * mm),
            MARGIN + 16 * mm,
            center_y + 3.8 * mm,
            137 * mm,
            label_style,
        )
        y -= height + 4 * mm

    callout_y = 23 * mm
    callout_h = 27 * mm
    page.setFillColor(V.oklch(0.93, 0.025, 116))
    page.roundRect(MARGIN, callout_y, PAGE_W - 2 * MARGIN, callout_h, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Ao final da semana", MARGIN + 6 * mm, callout_y + 18 * mm, V.OLIVE)
    closing_text = "Ao final da semana, procure padrões." + closing
    closing_style = style("exercise-close", 7.9, 11.2, V.INK, "AvenirNext-Medium")
    V.draw_paragraph(
        page,
        safe_markup(closing_text, closing_style, PAGE_W - 2 * MARGIN - 12 * mm),
        MARGIN + 6 * mm,
        callout_y + 13 * mm,
        PAGE_W - 2 * MARGIN - 12 * mm,
        closing_style,
    )
    V.footer(page, page_number)


def draw_next_step(page: canvas.Canvas, text: str, page_number: int) -> None:
    y = draw_page_header(page, "Próximo passo", "Aprofunde o diagnóstico", page_number)
    body = style("next-body", 9.8, 14.8, V.SLATE)
    y = draw_blocks(page, text, MARGIN, y, 154 * mm, body, gap=4 * mm, min_y=125 * mm)

    dimensions = [
        "CONCENTRAÇÃO DE DECISÕES",
        "CLAREZA DE PRIORIDADES",
        "AUTONOMIA DA LIDERANÇA",
        "COORDENAÇÃO ENTRE ÁREAS",
        "CADÊNCIA DE ACOMPANHAMENTO",
        "PREVISIBILIDADE DA EXECUÇÃO",
    ]
    panel_y = 35 * mm
    panel_h = 78 * mm
    page.setFillColor(V.INK)
    page.roundRect(MARGIN, panel_y, PAGE_W - 2 * MARGIN, panel_h, 4 * mm, stroke=0, fill=1)
    V.draw_label(page, "Raio-X do Fundador-Gargalo", MARGIN + 7 * mm, panel_y + panel_h - 11 * mm, V.OLIVE_ON_DARK)
    for index, label in enumerate(dimensions):
        column = index % 2
        row = index // 2
        x = MARGIN + 7 * mm + column * 82 * mm
        top = panel_y + panel_h - 25 * mm - row * 16 * mm
        page.setFillColor(V.OLIVE_ON_DARK)
        page.circle(x + 1 * mm, top - 1.8 * mm, 1 * mm, stroke=0, fill=1)
        page.setFillColor(V.LIGHT_INK)
        page.setFont("AvenirNext-Medium", 7.2)
        page.drawString(x + 6 * mm, top - 3.2 * mm, label)


def draw_conversation(page: canvas.Canvas, text: str, page_number: int) -> None:
    V.page_background(page, V.DARK)
    V.draw_label(page, "COO as a Service", MARGIN, PAGE_H - 22 * mm, V.OLIVE_ON_DARK)
    title_style = style("conversation-title", 28, 31, V.LIGHT_INK, "AvenirNext-Demi")
    V.draw_paragraph(
        page,
        "Quando faz sentido conversar<br/>sobre a operação",
        MARGIN,
        PAGE_H - 38 * mm,
        155 * mm,
        title_style,
    )
    blocks = split_blocks(text)
    body = style("conversation-body", 10.2, 15.3, V.LIGHT_SLATE)
    draw_blocks(page, "\n\n".join(blocks[:2]), MARGIN, PAGE_H - 94 * mm, 145 * mm, body, gap=5 * mm, min_y=112 * mm)

    cta_y = 57 * mm
    cta_h = 38 * mm
    page.setFillColor(V.OLIVE)
    page.roundRect(MARGIN, cta_y, PAGE_W - 2 * MARGIN, cta_h, 4 * mm, stroke=0, fill=1)
    page.linkURL(
        COO_LP_URL,
        (MARGIN, cta_y, PAGE_W - MARGIN, cta_y + cta_h),
        relative=0,
        thickness=0,
    )
    V.draw_label(page, "Próxima conversa", MARGIN + 7 * mm, cta_y + 27 * mm, V.LIGHT_INK)
    cta_style = style("conversation-cta", 16, 19, V.LIGHT_INK, "AvenirNext-Demi")
    V.draw_paragraph(page, "Quero conversar sobre minha operação.", MARGIN + 7 * mm, cta_y + 21 * mm, 145 * mm, cta_style)

    page.setFillColor(V.LIGHT_INK)
    page.setFont("AvenirNext-Medium", 9.5)
    page.drawString(MARGIN, 36 * mm, "Rafael Carvalho")
    page.setFillColor(V.LIGHT_SLATE)
    page.setFont("AvenirNext", 8)
    page.drawString(MARGIN, 29.5 * mm, "COO as a Service para empresas em crescimento")
    page.drawRightString(PAGE_W - MARGIN, 29.5 * mm, f"{page_number:02d}")


def build() -> Path:
    intro, how_to, chapters, closing = parse_content()
    V.register_fonts()
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    photo = V.prepare_photo()
    document = canvas.Canvas(str(OUTPUT), pagesize=A4, pageCompression=1)
    document.setTitle("7 Armadilhas que Mantêm o Fundador Preso à Operação")
    document.setAuthor("Rafael Carvalho")
    document.setSubject("Material executivo sobre dependência operacional do fundador")

    page_number = 1
    V.draw_cover(document)
    document.showPage()

    page_number += 1
    V.draw_author(document, photo)
    document.showPage()

    page_number += 1
    draw_intro_one(document, intro, page_number)
    document.showPage()

    page_number += 1
    draw_intro_two(document, intro, page_number)
    document.showPage()

    page_number += 1
    draw_how_to(document, how_to, chapters, page_number)
    document.showPage()

    for chapter in chapters:
        page_number += 1
        draw_chapter_context(document, chapter, page_number)
        document.showPage()
        page_number += 1
        draw_chapter_mechanism(document, chapter, page_number)
        document.showPage()
        page_number += 1
        draw_chapter_decision(document, chapter, page_number)
        document.showPage()

    page_number += 1
    draw_attention(document, closing["attention"], page_number)
    document.showPage()

    page_number += 1
    draw_exercise(document, closing["exercise"], page_number)
    document.showPage()

    page_number += 1
    draw_next_step(document, closing["next"], page_number)
    document.showPage()

    page_number += 1
    draw_conversation(document, closing["conversation"], page_number)
    document.showPage()

    if page_number != 30:
        raise ValueError(f"Paginação inesperada: {page_number}")
    document.save()
    return OUTPUT


if __name__ == "__main__":
    print(build())
