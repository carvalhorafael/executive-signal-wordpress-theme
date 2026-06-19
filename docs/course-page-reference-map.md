# Course page reference map

Reference reviewed: https://www.udemy.com/course/inteligencia-artificial-para-iniciantes/

Date: 2026-06-19

## Notes on the reference pass

The Udemy page text and HTML were accessible enough to map the content model and section order. Browser screenshots were blocked by Udemy's security verification, so this document does not depend on pixel-level visual details. The mapping below uses the visible/extracted page structure: course masthead, course metadata, preview prompt, learning outcomes, topics, curriculum, requirements, long description, target audience and instructor content.

This should be treated as a structural reference, not a visual clone. Executive Signal should keep its own restrained sales-page language and design-system contracts.

## Components We Should Have

### 1. Course Hero

Purpose: introduce the course and establish conversion context above the fold.

Reference elements:
- Course title.
- Short promise/summary.
- Category/topic breadcrumb.
- Badge such as bestseller/status.
- Rating, review count and student count.
- Instructor attribution.
- Last updated and language metadata.

Recommended Executive Signal component path:
- Use `es-sales-hero` for the macro layout.
- Use `es-badge` for status/category.
- Use `es-metric-strip` or compact inline meta for rating, students, update date and language.

### 2. Course Preview Media

Purpose: give visitors a quick sense of the course before checkout.

Reference elements:
- Course cover/preview image.
- "Preview this course" action.
- Video preview trigger.

Recommended Executive Signal component path:
- Use or adapt `es-preview-modal-trigger`.
- If no playable preview exists, use the course featured image as a static preview panel.

### 3. Enrollment CTA / Checkout Panel

Purpose: keep the main conversion action close to the decision-making context.

Reference elements:
- Price/offer area.
- Primary enroll/checkout button.
- Secondary purchase options or trust notes.
- Money-back/access reassurance on marketplaces.

Recommended Executive Signal component path:
- Use `es-offer-band` if the CTA appears in page flow.
- For the course single page, a sticky sidebar enrollment panel is likely better on desktop, with an inline CTA on mobile.

### 4. Course Facts Strip

Purpose: summarize practical facts before the visitor reads the details.

Reference elements:
- Total sections.
- Lecture count.
- Total duration.
- Level/language/update metadata.

Recommended Executive Signal component path:
- Use `es-event-info-strip` or `es-metric-strip`.

### 5. Learning Outcomes

Purpose: answer "what will I be able to do after this?"

Reference elements:
- "What you'll learn" section.
- Dense checklist of outcomes.

Recommended Executive Signal component path:
- Use `es-value-stack` for a compact value list, or a local section using `es-content-grid` if the content is a simple checklist.

### 6. Topic Chips

Purpose: expose course taxonomy and help discovery.

Reference elements:
- Related topics chips, such as AI, Data Science and Development.

Recommended Executive Signal component path:
- Use existing badge/link styling with `es-badge` or `es-nav-link`.
- Data should come from `course_category` or future course taxonomies.

### 7. Course Curriculum

Purpose: show the structure of the course and reduce uncertainty before checkout.

Reference elements:
- Section count, lecture count and total duration summary.
- "Expand all sections" action.
- Accordion sections.
- Lecture rows with title, preview marker and duration.

Recommended Executive Signal component path:
- Needs a dedicated course curriculum component in the design system.
- `es-curriculum-grid` exists, but it represents card-based curriculum blocks, not an expandable syllabus with lecture rows.
- `es-faq-accordion` exists, but its semantics are question/answer, not course module/lesson.

### 8. Requirements

Purpose: state prerequisites and reduce bad-fit purchases.

Reference elements:
- Simple bullet list.

Recommended Executive Signal component path:
- Use `es-section-block` with prose/list styling, or reuse `es-value-stack` for a short list.

### 9. Course Description / Long-Form Sales Copy

Purpose: explain the course, outcomes, difference, benefits and expected result.

Reference elements:
- Rich description with headings, paragraphs and lists.
- "Show more/show less" behavior on long content.

Recommended Executive Signal component path:
- Use `renderArticleProse` / `es-article-prose` style conventions for content.
- Add local collapse behavior only if the content becomes too long on mobile.

### 10. Audience Fit

Purpose: clarify who the course is for.

Reference elements:
- "Who this course is for" list.

Recommended Executive Signal component path:
- Use `es-audience-fit` and `es-audience-fit-card` if grouping by audience segment.
- Use prose/list styling if it remains a single simple list.

### 11. Instructor Bio

Purpose: establish authority and trust.

Reference elements:
- Instructor name.
- Role/credential line.
- Bio and experience proof.
- Student count/rating proof.

Recommended Executive Signal component path:
- Use `es-instructor-bio`.

### 12. Social Proof

Purpose: support the decision with proof without turning the page into a generic marketplace.

Reference elements:
- Rating and review count in the hero.
- Student count.
- Marketplace pages usually also expose review content lower on the page.

Recommended Executive Signal component path:
- Use `es-metric-strip` for quantitative proof.
- Use `es-proof-gallery` or `es-quote-band` only if we have real testimonials, screenshots or named proof.

### 13. Guarantee / Trust Callout

Purpose: reduce checkout anxiety near the CTA.

Reference elements:
- Marketplace trust and refund/access reassurance.

Recommended Executive Signal component path:
- Use `es-guarantee-callout` if the offer has an explicit guarantee.
- If there is no formal guarantee, use restrained checkout microcopy instead.

### 14. Related Courses / Next Step

Purpose: provide a secondary route when this course is not the right fit.

Reference elements:
- Udemy pages often include related course discovery, although this was not central in the extracted content.

Recommended Executive Signal component path:
- Use existing course cards from the listing page.
- Keep this below the primary sales content.

## Design System Gaps

### 1. Course Enrollment Panel

Status: gap.

Design system issue: https://github.com/carvalhorafael/executive-signal-design-system/issues/57

Why existing components are not enough:
- `es-offer-band` is an in-flow promotional band.
- A course page needs a compact checkout panel that can be sticky on desktop and collapse into an inline/mobile CTA.
- The panel should support checkout URL, price/offer copy when available, course facts, trust notes and one primary action.

Suggested package target:
- `@carvalhorafael/executive-signal-css`
- `@carvalhorafael/executive-signal-web`

Expected contract:
- `es-course-enrollment`
- slots/elements for media thumbnail, price/offer, primary action, secondary note, guarantee/trust text and metadata.
- responsive behavior documented for sticky desktop and inline mobile rendering.

### 2. Course Curriculum Accordion

Status: gap.

Design system issue: https://github.com/carvalhorafael/executive-signal-design-system/issues/58

Why existing components are not enough:
- `es-curriculum-grid` supports curriculum cards, not an expandable syllabus.
- `es-faq-accordion` supports disclosure behavior, but its semantics and styling target Q&A, not modules and lessons.
- The reference needs module summaries plus lecture rows with duration and optional preview markers.

Suggested package target:
- `@carvalhorafael/executive-signal-css`
- `@carvalhorafael/executive-signal-web`

Expected contract:
- `es-course-curriculum`
- `es-course-curriculum__summary`
- `es-course-curriculum__controls`
- `es-course-curriculum__section`
- `es-course-curriculum__section-trigger`
- `es-course-curriculum__lecture`
- support for duration, preview badge and section-level totals.
- optional progressive behavior for expand/collapse all.

### 3. Course Rating Summary

Status: probable gap, only needed if we display real ratings/reviews.

Why existing components are not enough:
- `es-metric-strip` can show rating and student count as text metrics.
- There is no documented star rating or review-summary component.

Suggested package target:
- `@carvalhorafael/executive-signal-css`
- `@carvalhorafael/executive-signal-web`

Expected contract:
- `es-rating-summary` with accessible text, optional star visualization and review count.
- Should degrade cleanly to text-only if no rating data exists.

### 4. Course Topic Chip Group

Status: small gap or theme-local adaptation.

Why existing components may be enough:
- `es-badge` and `es-nav-link` can cover simple taxonomy chips.

Decision:
- Keep local in the theme for the first course page if chips are just WordPress taxonomy links.
- Promote to the design system only if multiple consumers need a reusable taxonomy-chip group contract.

### 5. Long Description Clamp

Status: possible gap, not required for MVP.

Why existing components may be enough:
- `es-article-prose` can render the long description.
- A marketplace-style "show more/show less" may not be necessary for Executive Signal if the page is paced better.

Decision:
- Do not create a design-system component initially.
- Add only if real course descriptions become long enough that mobile scanning suffers.

## Initial Page Composition Recommendation

For the first implementation, use this order:

1. Course hero with category, title, excerpt, instructor/update metadata and course facts.
2. Preview media plus enrollment panel.
3. Learning outcomes.
4. Course curriculum accordion.
5. Requirements.
6. Long description.
7. Audience fit.
8. Instructor bio.
9. Guarantee/trust callout if there is real policy copy.
10. Related courses if more than one course exists.

Minimum design-system work before a polished implementation:

1. Create or request `course-enrollment` in the design system.
2. Create or request `course-curriculum` in the design system.

Everything else can start by consuming existing Executive Signal contracts or remain local until reuse pressure appears.
