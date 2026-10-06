#!/usr/bin/env python3
"""Gera a prova visual de cinco páginas do material Sete Armadilhas."""

from __future__ import annotations

import math
from pathlib import Path

from PIL import Image, ImageEnhance
from reportlab.lib.colors import Color
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.lib.utils import ImageReader
from reportlab.lib.units import mm
from reportlab.pdfbase import pdfmetrics
from reportlab.pdfbase.ttfonts import TTFont
from reportlab.pdfgen import canvas
from reportlab.platypus import Paragraph


ROOT = Path(__file__).resolve().parents[4]
OUTPUT = ROOT / "output/pdf/sete-armadilhas-prova-visual.pdf"
TMP = ROOT / "tmp/pdfs/sete-armadilhas-prova"
PHOTO = ROOT / "assets/images/rafael-carvalho-coo-as-a-service.jpeg"
FONT = Path("/System/Library/Fonts/Avenir Next.ttc")

PAGE_W, PAGE_H = A4
MARGIN = 20 * mm


def oklch(lightness: float, chroma: float, hue: float) -> Color:
    """Converte OKLCH para sRGB com clipping simples."""
    angle = math.radians(hue)
    a = chroma * math.cos(angle)
    b = chroma * math.sin(angle)
    l_ = lightness + 0.3963377774 * a + 0.2158037573 * b
    m_ = lightness - 0.1055613458 * a - 0.0638541728 * b
    s_ = lightness - 0.0894841775 * a - 1.2914855480 * b
    l, m, s = l_**3, m_**3, s_**3
    red = 4.0767416621 * l - 3.3077115913 * m + 0.2309699292 * s
    green = -1.2684380046 * l + 2.6097574011 * m - 0.3413193965 * s
    blue = -0.0041960863 * l - 0.7034186147 * m + 1.7076147010 * s

    def gamma(value: float) -> float:
        value = max(0.0, min(1.0, value))
        if value <= 0.0031308:
            return 12.92 * value
        return 1.055 * value ** (1 / 2.4) - 0.055

    return Color(gamma(red), gamma(green), gamma(blue))


PAPER = oklch(0.97, 0.008, 250)
SURFACE = oklch(0.99, 0.006, 250)
SURFACE_STRONG = oklch(0.94, 0.012, 250)
INK = oklch(0.18, 0.018, 250)
SLATE = oklch(0.42, 0.028, 255)
MUTED = oklch(0.55, 0.03, 255)
OLIVE = oklch(0.46, 0.115, 116)
OLIVE_ON_DARK = oklch(0.82, 0.115, 116)
DARK = Color(9 / 255, 12 / 255, 17 / 255)
LIGHT_INK = oklch(0.94, 0.012, 250)
LIGHT_SLATE = oklch(0.70, 0.025, 255)
BORDER = oklch(0.78, 0.018, 250)


def register_fonts() -> None:
    pdfmetrics.registerFont(TTFont("AvenirNext", str(FONT), subfontIndex=7))
    pdfmetrics.registerFont(TTFont("AvenirNext-Medium", str(FONT), subfontIndex=5))
    pdfmetrics.registerFont(TTFont("AvenirNext-Demi", str(FONT), subfontIndex=2))
    pdfmetrics.registerFont(TTFont("AvenirNext-Bold", str(FONT), subfontIndex=0))


def paragraph_style(
    name: str,
    size: float,
    leading: float,
    color: Color,
    font: str = "AvenirNext",
    tracking: float = 0,
) -> ParagraphStyle:
    return ParagraphStyle(
        name,
        fontName=font,
        fontSize=size,
        leading=leading,
        textColor=color,
        alignment=TA_LEFT,
        spaceAfter=0,
        spaceBefore=0,
        borderWidth=0,
        allowWidows=0,
        allowOrphans=0,
        wordWrap="LTR",
        splitLongWords=False,
        tracking=tracking,
    )


def draw_paragraph(
    page: canvas.Canvas,
    text: str,
    x: float,
    top: float,
    width: float,
    style: ParagraphStyle,
    max_height: float = 260 * mm,
) -> float:
    para = Paragraph(text, style)
    _, height = para.wrap(width, max_height)
    para.drawOn(page, x, top - height)
    return height


def draw_label(page: canvas.Canvas, text: str, x: float, y: float, color: Color) -> None:
    label = page.beginText(x, y)
    label.setFillColor(color)
    label.setFont("AvenirNext-Demi", 7.2)
    label.setCharSpace(1.15)
    label.textLine(text.upper())
    page.drawText(label)


def centered_baseline(font: str, size: float, center_y: float) -> float:
    """Retorna a baseline que centraliza visualmente uma linha em um eixo Y."""
    ascent, descent = pdfmetrics.getAscentDescent(font, size)
    return center_y - (ascent + descent) / 2


def draw_centered_line(
    page: canvas.Canvas,
    text: str,
    center_x: float,
    center_y: float,
    font: str,
    size: float,
    color: Color,
) -> None:
    page.setFillColor(color)
    page.setFont(font, size)
    page.drawCentredString(center_x, centered_baseline(font, size, center_y), text)


def draw_link(
    page: canvas.Canvas,
    label: str,
    url: str,
    x: float,
    y: float,
) -> float:
    font = "AvenirNext-Demi"
    size = 8.1
    width = pdfmetrics.stringWidth(label, font, size)
    page.setFillColor(OLIVE)
    page.setFont(font, size)
    page.drawString(x, y, label)
    page.linkURL(url, (x, y - 2, x + width, y + size + 2), relative=0)
    return width


def footer(page: canvas.Canvas, number: int) -> None:
    y = 12 * mm
    page.setStrokeColor(BORDER)
    page.setLineWidth(0.45)
    page.line(MARGIN, y + 5 * mm, PAGE_W - MARGIN, y + 5 * mm)
    page.setFillColor(MUTED)
    page.setFont("AvenirNext-Medium", 7.2)
    page.drawString(MARGIN, y, "RAFAEL CARVALHO  ·  OPERAÇÃO QUE ESCALA")
    page.drawRightString(PAGE_W - MARGIN, y, f"{number:02d}")


def page_background(page: canvas.Canvas, color: Color = PAPER) -> None:
    page.setFillColor(color)
    page.rect(0, 0, PAGE_W, PAGE_H, stroke=0, fill=1)


def prepare_photo() -> Path:
    TMP.mkdir(parents=True, exist_ok=True)
    target = TMP / "rafael-author.jpg"
    with Image.open(PHOTO) as image:
        image = image.convert("RGB")
        image = ImageEnhance.Contrast(image).enhance(1.08)
        image = ImageEnhance.Color(image).enhance(0.72)
        image.save(target, quality=92, optimize=True)
    return target


def draw_cover(page: canvas.Canvas) -> None:
    page_background(page, DARK)
    content_shift = 40 * mm

    draw_label(page, "Rafael Carvalho · Caderno 01", MARGIN, PAGE_H - 22 * mm, LIGHT_SLATE)

    page.setFillColor(OLIVE)
    page.setFont("AvenirNext-Bold", 104)
    page.drawString(MARGIN - 2 * mm, PAGE_H - 82 * mm - content_shift, "7")

    title_style = paragraph_style("cover-title", 31, 32, LIGHT_INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "As Armadilhas que<br/>Mantêm o Fundador<br/>Preso à Operação",
        68 * mm,
        PAGE_H - 39 * mm - content_shift,
        PAGE_W - 88 * mm,
        title_style,
    )

    subtitle_style = paragraph_style("cover-subtitle", 12.2, 17.2, LIGHT_SLATE, "AvenirNext")
    draw_paragraph(
        page,
        "Por que contratar pessoas, criar processos e aumentar as reuniões pode não reduzir a dependência da empresa em você.",
        MARGIN,
        PAGE_H - 117 * mm - content_shift,
        104 * mm,
        subtitle_style,
    )

    draw_label(page, "Material gratuito", MARGIN, 47 * mm, OLIVE_ON_DARK)
    page.setFillColor(LIGHT_INK)
    page.setFont("AvenirNext-Medium", 10.5)
    page.drawString(MARGIN, 38 * mm, "Rafael Carvalho")
    page.setFillColor(LIGHT_SLATE)
    page.setFont("AvenirNext", 8.8)
    page.drawString(MARGIN, 31.5 * mm, "COO as a Service para empresas em crescimento")


def draw_author(page: canvas.Canvas, photo_path: Path) -> None:
    page_background(page)
    draw_label(page, "Sobre o autor · 01", MARGIN, PAGE_H - 22 * mm, OLIVE)

    headline = paragraph_style("author-head", 25, 28, INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "Experiência real em operações<br/>que cresceram.",
        MARGIN,
        PAGE_H - 34 * mm,
        150 * mm,
        headline,
    )

    photo_size = 72 * mm
    photo_x = MARGIN
    photo_y = PAGE_H - 156 * mm
    page.setFillColor(OLIVE)
    page.roundRect(photo_x + 4 * mm, photo_y + 4 * mm, photo_size, photo_size, 4 * mm, stroke=0, fill=1)
    page.drawImage(
        ImageReader(str(photo_path)),
        photo_x,
        photo_y,
        width=photo_size,
        height=photo_size,
        preserveAspectRatio=True,
        mask="auto",
    )

    bio_style = paragraph_style("author-bio", 8.7, 13.1, SLATE)
    draw_paragraph(
        page,
        "Rafael Carvalho é empreendedor e executivo há<br/>mais de 20 anos. Sua trajetória foi construída<br/>na interseção entre tecnologia, educação e<br/>gestão.<br/><br/>Cofundou a Edools e, posteriormente, atuou<br/>como COO da HeroSpark, onde liderou uma<br/>operação com mais de 200 profissionais e áreas<br/>como Vendas, Marketing, Customer Success,<br/>Suporte e Gestão de Pessoas.<br/><br/>Hoje, aplica esse repertório ao lado de<br/>fundadores e CEOs que precisam reduzir<br/>decisões concentradas no líder e construir uma<br/>operação com mais clareza, autonomia e<br/>capacidade de execução.",
        105 * mm,
        PAGE_H - 78 * mm,
        85 * mm,
        bio_style,
    )

    credentials_y = 61 * mm
    credentials_h = 48 * mm
    page.setFillColor(SURFACE_STRONG)
    page.roundRect(MARGIN, credentials_y, PAGE_W - 2 * MARGIN, credentials_h, 4 * mm, stroke=0, fill=1)
    draw_label(page, "Repertório aplicado", MARGIN + 7 * mm, credentials_y + 37 * mm, OLIVE)

    columns = [
        ("20+ ANOS", "Tecnologia, educação<br/>e gestão"),
        ("200+ PESSOAS", "Lideradas na operação<br/>da HeroSpark"),
        ("EMPREENDEDOR", "Fundador e executivo em<br/>diferentes estágios"),
    ]
    column_w = (PAGE_W - 2 * MARGIN - 14 * mm) / 3
    for index, (title, description) in enumerate(columns):
        x = MARGIN + 7 * mm + index * column_w
        if index:
            page.setStrokeColor(BORDER)
            page.setLineWidth(0.45)
            page.line(x - 5 * mm, credentials_y + 8 * mm, x - 5 * mm, credentials_y + 31 * mm)
        page.setFillColor(INK)
        page.setFont("AvenirNext-Demi", 9.2)
        page.drawString(x, credentials_y + 24 * mm, title)
        credential_style = paragraph_style(f"credential-{index}", 7.8, 10.8, SLATE)
        draw_paragraph(page, description, x, credentials_y + 19 * mm, column_w - 8 * mm, credential_style)

    draw_label(page, "Canais", MARGIN, 43 * mm, MUTED)
    link_y = 34 * mm
    link_x = MARGIN
    links = [
        ("LINKEDIN", "https://www.linkedin.com/in/rafaelmcarvalho/"),
        ("INSTAGRAM", "https://www.instagram.com/eu.rafaelcarvalho/"),
        ("YOUTUBE", "https://www.youtube.com/@RafaelCarvalhoMCC"),
    ]
    for index, (label, url) in enumerate(links):
        width = draw_link(page, label, url, link_x, link_y)
        link_x += width + 12 * mm
        if index < len(links) - 1:
            page.setFillColor(BORDER)
            page.circle(link_x - 6 * mm, link_y + 1.2 * mm, 0.7 * mm, stroke=0, fill=1)

    footer(page, 2)


def draw_thesis(page: canvas.Canvas) -> None:
    page_background(page)
    draw_label(page, "Tese central · 01", MARGIN, PAGE_H - 22 * mm, OLIVE)

    headline = paragraph_style("thesis-head", 26, 29, INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "A empresa cresceu.<br/>O modelo de gestão acompanhou?",
        MARGIN,
        PAGE_H - 34 * mm,
        148 * mm,
        headline,
    )

    body = paragraph_style("thesis-body", 10.5, 15.5, SLATE)
    draw_paragraph(
        page,
        "No começo, a presença do fundador reduz a distância entre perceber um problema e agir sobre ele. Com o crescimento, surgem novas pessoas, áreas e dependências. O número de decisões aumenta, enquanto o tempo e a atenção do fundador continuam limitados.",
        MARGIN,
        PAGE_H - 77 * mm,
        150 * mm,
        body,
    )

    map_top = PAGE_H - 126 * mm
    node_w = 36 * mm
    node_h = 10 * mm
    left_x = MARGIN
    founder_x = 86 * mm
    founder_y = map_top - 28 * mm
    execution_x = 148 * mm
    execution_y = founder_y

    labels = ["DECISÕES", "PRIORIDADES", "CONFLITOS", "EXCEÇÕES"]
    for index, label in enumerate(labels):
        y = map_top - index * 15 * mm
        page.setFillColor(SURFACE)
        page.setStrokeColor(BORDER)
        page.setLineWidth(0.5)
        page.roundRect(left_x, y, node_w, node_h, 2.5 * mm, stroke=1, fill=1)
        draw_centered_line(
            page,
            label,
            left_x + node_w / 2,
            y + node_h / 2,
            "AvenirNext-Demi",
            7.2,
            SLATE,
        )
        page.setStrokeColor(BORDER)
        page.line(left_x + node_w, y + node_h / 2, founder_x, founder_y + 11 * mm)

    page.setFillColor(INK)
    page.circle(founder_x + 15 * mm, founder_y + 11 * mm, 15 * mm, stroke=0, fill=1)
    founder_center_x = founder_x + 15 * mm
    founder_center_y = founder_y + 11 * mm
    draw_centered_line(
        page,
        "FUNDADOR",
        founder_center_x,
        founder_center_y + 4.2 * mm,
        "AvenirNext-Demi",
        8,
        LIGHT_INK,
    )
    draw_centered_line(
        page,
        "INTEGRA E",
        founder_center_x,
        founder_center_y - 1.1 * mm,
        "AvenirNext",
        4.8,
        LIGHT_SLATE,
    )
    draw_centered_line(
        page,
        "DESTRAVA",
        founder_center_x,
        founder_center_y - 4.2 * mm,
        "AvenirNext",
        4.8,
        LIGHT_SLATE,
    )

    page.setStrokeColor(OLIVE)
    page.setLineWidth(1.2)
    page.line(founder_x + 30 * mm, founder_y + 11 * mm, execution_x, execution_y + 11 * mm)
    page.setFillColor(OLIVE)
    page.roundRect(execution_x, execution_y + 2 * mm, 39 * mm, 18 * mm, 4 * mm, stroke=0, fill=1)
    execution_center_x = execution_x + 19.5 * mm
    execution_center_y = execution_y + 11 * mm
    draw_centered_line(
        page,
        "EXECUÇÃO",
        execution_center_x,
        execution_center_y + 2.5 * mm,
        "AvenirNext-Demi",
        8,
        LIGHT_INK,
    )
    draw_centered_line(
        page,
        "AVANÇA OU ESPERA",
        execution_center_x,
        execution_center_y - 2.6 * mm,
        "AvenirNext",
        6.4,
        LIGHT_INK,
    )

    quote_y = 29 * mm
    page.setFillColor(oklch(0.93, 0.025, 116))
    page.roundRect(MARGIN, quote_y, PAGE_W - 2 * MARGIN, 39 * mm, 4 * mm, stroke=0, fill=1)
    draw_label(page, "Diagnóstico", MARGIN + 7 * mm, quote_y + 29 * mm, OLIVE)
    quote = paragraph_style("quote", 12.4, 15.5, INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "O problema não é apenas excesso de trabalho.<br/>É uma operação que cresceu além do modelo de gestão<br/>que a trouxe até aqui.",
        MARGIN + 7 * mm,
        quote_y + 23 * mm,
        PAGE_W - 2 * MARGIN - 14 * mm,
        quote,
    )
    footer(page, 3)


def draw_chapter(page: canvas.Canvas) -> None:
    page_background(page)
    draw_label(page, "Armadilha 02", MARGIN, PAGE_H - 22 * mm, OLIVE)

    page.setFillColor(OLIVE)
    page.setFont("AvenirNext-Bold", 52)
    page.drawRightString(PAGE_W - MARGIN, PAGE_H - 37 * mm, "02")

    headline = paragraph_style("chapter-head", 24, 27, INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "Delegar tarefas sem<br/>delegar decisões",
        MARGIN,
        PAGE_H - 34 * mm,
        132 * mm,
        headline,
    )

    left_w = 84 * mm
    right_x = 120 * mm
    right_w = PAGE_W - MARGIN - right_x
    body = paragraph_style("chapter-body", 8.9, 13.3, SLATE)
    subhead = paragraph_style("chapter-sub", 12, 14.5, INK, "AvenirNext-Demi")

    top = PAGE_H - 91 * mm
    used = draw_paragraph(page, "Como a armadilha aparece", MARGIN, top, left_w, subhead)
    used += 4 * mm
    used += draw_paragraph(
        page,
        "O fundador distribui atividades, mas continua<br/>definindo a solução, aprovando cada etapa e<br/>decidindo como agir diante de qualquer desvio.<br/><br/>A tarefa mudou de mãos; a responsabilidade<br/>real, não.",
        MARGIN,
        top - used,
        left_w,
        body,
    )

    mechanism_top = top - used - 9 * mm
    used2 = draw_paragraph(page, "O mecanismo que mantém o fundador preso", MARGIN, mechanism_top, left_w, subhead)
    used2 += 4 * mm
    draw_paragraph(
        page,
        "Quem precisa da aprovação do fundador para<br/>avançar aprende a preparar decisões para ele,<br/>não a assumir decisões dentro de um mandato<br/>claro.<br/><br/>A equipe executa mais, mas ainda não responde<br/>pela escolha.",
        MARGIN,
        mechanism_top - used2,
        left_w,
        body,
    )

    panel_y = 88 * mm
    panel_h = 111 * mm
    page.setFillColor(SURFACE_STRONG)
    page.roundRect(right_x, panel_y, right_w, panel_h, 4 * mm, stroke=0, fill=1)
    draw_label(page, "Sinais observáveis", right_x + 6 * mm, panel_y + panel_h - 11 * mm, OLIVE)
    bullet_style = paragraph_style("bullet", 8.2, 11.7, SLATE)
    bullets = [
        "O gestor chega com informações,<br/>mas sem uma recomendação.",
        "Entregas param enquanto<br/>aguardam uma aprovação.",
        "O fundador revisa decisões<br/>de baixo risco.",
        "A equipe pergunta o que deve<br/>fazer em situações recorrentes.",
        "A responsabilidade volta para<br/>quem deu a última aprovação.",
    ]
    y = panel_y + panel_h - 22 * mm
    for bullet in bullets:
        page.setFillColor(OLIVE)
        page.circle(right_x + 7 * mm, y - 1.8 * mm, 1.1 * mm, stroke=0, fill=1)
        height = draw_paragraph(page, bullet, right_x + 12 * mm, y, right_w - 18 * mm, bullet_style)
        y -= height + 5 * mm

    question_y = 27 * mm
    page.setFillColor(INK)
    page.roundRect(MARGIN, question_y, PAGE_W - 2 * MARGIN, 42 * mm, 4 * mm, stroke=0, fill=1)
    draw_label(page, "Pergunta de diagnóstico", MARGIN + 7 * mm, question_y + 31 * mm, OLIVE_ON_DARK)
    question = paragraph_style("question", 13.2, 16.8, LIGHT_INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "Quais decisões você acredita ter delegado,<br/>mas ainda precisam da sua confirmação para produzir efeito?",
        MARGIN + 7 * mm,
        question_y + 25 * mm,
        PAGE_W - 2 * MARGIN - 20 * mm,
        question,
    )
    footer(page, 4)


def draw_exercise(page: canvas.Canvas) -> None:
    page_background(page)
    draw_label(page, "Exercício prático", MARGIN, PAGE_H - 22 * mm, OLIVE)
    headline = paragraph_style("exercise-head", 26, 29, INK, "AvenirNext-Demi")
    draw_paragraph(
        page,
        "Transforme a sensação de sobrecarga<br/>em evidência operacional",
        MARGIN,
        PAGE_H - 34 * mm,
        157 * mm,
        headline,
    )
    intro = paragraph_style("exercise-intro", 10.2, 15.2, SLATE)
    draw_paragraph(
        page,
        "Durante cinco dias úteis, registre cada situação em que a operação depender da sua intervenção para avançar. Use uma cópia desta página para cada ocorrência relevante.",
        MARGIN,
        PAGE_H - 80 * mm,
        155 * mm,
        intro,
    )

    fields = [
        ("01", "Qual decisão, problema ou conflito chegou até você?", 25 * mm),
        ("02", "Quem estava envolvido?", 18 * mm),
        ("03", "Por que a situação não avançou sem sua participação?", 25 * mm),
        ("04", "Qual das sete armadilhas parece estar presente?", 18 * mm),
        ("05", "Essa decisão realmente deveria continuar com você?", 23 * mm),
    ]
    y = PAGE_H - 107 * mm
    for number, label, height in fields:
        page.setFillColor(SURFACE)
        page.setStrokeColor(BORDER)
        page.setLineWidth(0.55)
        page.roundRect(MARGIN, y - height, PAGE_W - 2 * MARGIN, height, 3 * mm, stroke=1, fill=1)
        center_y = y - height / 2
        number_font = "AvenirNext-Demi"
        number_size = 9
        label_font = "AvenirNext-Medium"
        label_size = 8.6
        page.setFillColor(OLIVE)
        page.setFont(number_font, number_size)
        page.drawString(
            MARGIN + 5 * mm,
            centered_baseline(number_font, number_size, center_y),
            number,
        )
        page.setFillColor(INK)
        page.setFont(label_font, label_size)
        page.drawString(
            MARGIN + 16 * mm,
            centered_baseline(label_font, label_size, center_y),
            label,
        )
        y -= height + 5 * mm

    callout_y = 26 * mm
    page.setFillColor(oklch(0.93, 0.025, 116))
    page.roundRect(MARGIN, callout_y, PAGE_W - 2 * MARGIN, 25 * mm, 4 * mm, stroke=0, fill=1)
    draw_label(page, "Ao final da semana", MARGIN + 6 * mm, callout_y + 16.5 * mm, OLIVE)
    callout = paragraph_style("exercise-callout", 8.5, 12.2, INK, "AvenirNext-Medium")
    draw_paragraph(
        page,
        "Procure padrões. O objetivo não é contar interrupções, mas entender quais mecanismos<br/>fazem as mesmas dependências retornarem.",
        MARGIN + 6 * mm,
        callout_y + 12 * mm,
        PAGE_W - 2 * MARGIN - 12 * mm,
        callout,
    )
    footer(page, 5)


def build() -> Path:
    register_fonts()
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    photo = prepare_photo()
    document = canvas.Canvas(str(OUTPUT), pagesize=A4, pageCompression=1)
    document.setTitle("As 7 Armadilhas que Mantêm o Fundador Preso à Operação - Prova visual")
    document.setAuthor("Rafael Carvalho")
    document.setSubject("Prova visual da coleção Operação que Escala")

    draw_cover(document)
    document.showPage()
    draw_author(document, photo)
    document.showPage()
    draw_thesis(document)
    document.showPage()
    draw_chapter(document)
    document.showPage()
    draw_exercise(document)
    document.showPage()
    document.save()
    return OUTPUT


if __name__ == "__main__":
    print(build())
