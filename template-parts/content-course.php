<?php
/**
 * Template part for a single course page.
 *
 * @package ExecutiveSignal
 */

$course_id           = get_the_ID();
$course_category     = executive_signal_get_primary_course_category( $course_id );
$course_terms        = get_the_terms( $course_id, EXECUTIVE_SIGNAL_COURSE_TAXONOMY );
$course_checkout_url = executive_signal_get_course_checkout_url( $course_id );
$course_content      = str_replace( ']]>', ']]&gt;', apply_filters( 'the_content', get_the_content() ) );
$course_author_id    = (int) get_post_field( 'post_author', $course_id );
$course_author_name  = get_the_author_meta( 'display_name', $course_author_id );
$course_author_bio   = get_the_author_meta( 'description', $course_author_id );
$course_author_image = get_avatar_url( $course_author_id, array( 'size' => 512 ) );
$mock_outcomes       = array(
	__( 'Clarify the signal behind a messy business problem.', 'executive-signal-wordpress-theme' ),
	__( 'Turn course notes into operating routines and decision checklists.', 'executive-signal-wordpress-theme' ),
	__( 'Use practical prompts, reviews and examples without losing strategic context.', 'executive-signal-wordpress-theme' ),
);
$mock_requirements   = array(
	__( 'No advanced technical setup is required.', 'executive-signal-wordpress-theme' ),
	__( 'Bring one real workflow or decision you want to improve.', 'executive-signal-wordpress-theme' ),
	__( 'Set aside time to apply each lesson to your own context.', 'executive-signal-wordpress-theme' ),
);
$mock_curriculum     = array(
	array(
		'id'       => 'operating-problem',
		'title'    => __( 'Start with the operating problem', 'executive-signal-wordpress-theme' ),
		'meta'     => __( '3 lessons / 12 min', 'executive-signal-wordpress-theme' ),
		'open'     => true,
		'lectures' => array(
			array(
				'id'       => 'read-current-signal',
				'title'    => __( 'Read the current signal', 'executive-signal-wordpress-theme' ),
				'duration' => __( '4 min', 'executive-signal-wordpress-theme' ),
				'preview'  => true,
			),
			array(
				'id'       => 'map-decision-context',
				'title'    => __( 'Map the decision context', 'executive-signal-wordpress-theme' ),
				'duration' => __( '5 min', 'executive-signal-wordpress-theme' ),
			),
			array(
				'id'       => 'choose-practical-target',
				'title'    => __( 'Choose a practical target', 'executive-signal-wordpress-theme' ),
				'duration' => __( '3 min', 'executive-signal-wordpress-theme' ),
			),
		),
	),
	array(
		'id'       => 'notes-into-routines',
		'title'    => __( 'Turn notes into routines', 'executive-signal-wordpress-theme' ),
		'meta'     => __( '3 lessons / 15 min', 'executive-signal-wordpress-theme' ),
		'lectures' => array(
			array(
				'id'       => 'create-review-rhythm',
				'title'    => __( 'Create a review rhythm', 'executive-signal-wordpress-theme' ),
				'duration' => __( '5 min', 'executive-signal-wordpress-theme' ),
			),
			array(
				'id'       => 'build-decision-checklist',
				'title'    => __( 'Build a decision checklist', 'executive-signal-wordpress-theme' ),
				'duration' => __( '5 min', 'executive-signal-wordpress-theme' ),
			),
			array(
				'id'       => 'keep-examples-close',
				'title'    => __( 'Keep examples close to work', 'executive-signal-wordpress-theme' ),
				'duration' => __( '5 min', 'executive-signal-wordpress-theme' ),
			),
		),
	),
	array(
		'id'       => 'apply-and-refine',
		'title'    => __( 'Apply and refine', 'executive-signal-wordpress-theme' ),
		'meta'     => __( '3 lessons / 15 min', 'executive-signal-wordpress-theme' ),
		'lectures' => array(
			array(
				'id'       => 'run-first-review',
				'title'    => __( 'Run the first review', 'executive-signal-wordpress-theme' ),
				'duration' => __( '6 min', 'executive-signal-wordpress-theme' ),
			),
			array(
				'id'       => 'adjust-operating-signal',
				'title'    => __( 'Adjust the operating signal', 'executive-signal-wordpress-theme' ),
				'duration' => __( '4 min', 'executive-signal-wordpress-theme' ),
			),
			array(
				'id'       => 'decide-next-iteration',
				'title'    => __( 'Decide the next iteration', 'executive-signal-wordpress-theme' ),
				'duration' => __( '5 min', 'executive-signal-wordpress-theme' ),
			),
		),
	),
);
$mock_audiences      = array(
	array(
		'label'       => __( 'Operators', 'executive-signal-wordpress-theme' ),
		'title'       => __( 'For people turning strategy into weekly execution.', 'executive-signal-wordpress-theme' ),
		'description' => __( 'Use the course to make priorities, routines and review moments easier to inspect.', 'executive-signal-wordpress-theme' ),
	),
	array(
		'label'       => __( 'Leaders', 'executive-signal-wordpress-theme' ),
		'title'       => __( 'For leaders who need clearer management signals.', 'executive-signal-wordpress-theme' ),
		'description' => __( 'Use the lessons to reduce noise before decisions reach meetings, dashboards or teams.', 'executive-signal-wordpress-theme' ),
	),
);
$related_courses     = new WP_Query(
	array(
		'ignore_sticky_posts'    => true,
		'no_found_rows'          => true,
		'post__not_in'           => array( $course_id ),
		'post_status'            => 'publish',
		'post_type'              => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
		'posts_per_page'         => 3,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
		'tax_query'              => $course_category ? array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Small related-content query on single course pages.
			array(
				'field'    => 'term_id',
				'taxonomy' => EXECUTIVE_SIGNAL_COURSE_TAXONOMY,
				'terms'    => array( $course_category->term_id ),
			),
		) : array(),
	)
);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--single course-single' ); ?> itemscope itemtype="https://schema.org/Course">
	<header class="es-sales-hero course-single__hero" data-align="split">
		<div class="es-sales-hero__content">
			<div class="es-sales-hero__copy">
				<p class="es-sales-hero__eyebrow"><?php esc_html_e( 'Online course', 'executive-signal-wordpress-theme' ); ?></p>
				<?php the_title( '<h1 class="es-sales-hero__title" itemprop="name">', '</h1>' ); ?>
				<?php if ( has_excerpt() ) : ?>
					<p class="es-sales-hero__description" itemprop="description"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
			</div>

			<div class="es-sales-hero__meta">
				<?php if ( $course_category ) : ?>
					<?php executive_signal_render_badge( $course_category->name ); ?>
				<?php endif; ?>
				<?php executive_signal_render_badge( __( 'Self-paced', 'executive-signal-wordpress-theme' ), 'muted' ); ?>
			</div>

			<div class="es-sales-hero__actions">
				<?php if ( $course_checkout_url ) : ?>
					<a class="es-button" data-variant="primary" data-size="lg" href="<?php echo esc_url( $course_checkout_url ); ?>">
						<?php esc_html_e( 'Go to checkout', 'executive-signal-wordpress-theme' ); ?>
					</a>
				<?php endif; ?>
				<a class="es-button" data-variant="ghost" href="#course-content">
					<?php esc_html_e( 'View course details', 'executive-signal-wordpress-theme' ); ?>
				</a>
			</div>

			<?php if ( $course_checkout_url ) : ?>
				<p class="es-sales-hero__footnote"><?php esc_html_e( 'Checkout opens in a secure external page.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php endif; ?>
		</div>

		<div class="es-sales-hero__visual course-single__hero-visual" aria-label="<?php esc_attr_e( 'Course preview', 'executive-signal-wordpress-theme' ); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large' ); ?>
			<?php else : ?>
				<div class="course-single__hero-placeholder">
					<span><?php esc_html_e( 'Course', 'executive-signal-wordpress-theme' ); ?></span>
					<strong><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></strong>
				</div>
			<?php endif; ?>
		</div>
	</header>

	<section class="es-event-info-strip" aria-labelledby="course-overview-title">
		<header class="es-event-info-strip__header">
			<p class="es-event-info-strip__eyebrow"><?php esc_html_e( 'Course overview', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-event-info-strip__title" id="course-overview-title"><?php esc_html_e( 'Practical context before you enroll.', 'executive-signal-wordpress-theme' ); ?></h2>
		</header>
		<div class="es-event-info-strip__items">
			<div class="es-event-info-strip__item">
				<p class="es-event-info-strip__label"><?php esc_html_e( 'Format', 'executive-signal-wordpress-theme' ); ?></p>
				<p class="es-event-info-strip__value"><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></p>
				<p class="es-event-info-strip__meta"><?php esc_html_e( 'Self-paced access', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
			<?php if ( $course_category ) : ?>
				<div class="es-event-info-strip__item">
					<p class="es-event-info-strip__label"><?php esc_html_e( 'Category', 'executive-signal-wordpress-theme' ); ?></p>
					<p class="es-event-info-strip__value"><?php echo esc_html( $course_category->name ); ?></p>
					<p class="es-event-info-strip__meta"><?php esc_html_e( 'Course track', 'executive-signal-wordpress-theme' ); ?></p>
				</div>
			<?php endif; ?>
			<div class="es-event-info-strip__item">
				<p class="es-event-info-strip__label"><?php esc_html_e( 'Updated', 'executive-signal-wordpress-theme' ); ?></p>
				<p class="es-event-info-strip__value">
					<time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C, $course_id ) ); ?>"><?php echo esc_html( get_the_modified_date( '', $course_id ) ); ?></time>
				</p>
				<p class="es-event-info-strip__meta"><?php esc_html_e( 'Latest course revision', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</div>
	</section>

	<section class="es-preview-modal-trigger" aria-labelledby="course-preview-title">
		<div class="es-preview-modal-trigger__copy">
			<p class="es-preview-modal-trigger__eyebrow"><?php esc_html_e( 'Preview', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-preview-modal-trigger__title" id="course-preview-title"><?php esc_html_e( 'See the course shape before enrolling.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p class="es-preview-modal-trigger__description"><?php esc_html_e( 'This preview area uses mocked copy until the course plugin exposes preview video or sample lesson data.', 'executive-signal-wordpress-theme' ); ?></p>
			<div class="es-preview-modal-trigger__actions">
				<a class="es-button" data-variant="ghost" href="#course-content"><?php esc_html_e( 'Read course details', 'executive-signal-wordpress-theme' ); ?></a>
			</div>
		</div>
		<div class="es-preview-modal-trigger__preview">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large' ); ?>
			<?php else : ?>
				<div class="course-single__hero-placeholder">
					<span><?php esc_html_e( 'Preview', 'executive-signal-wordpress-theme' ); ?></span>
					<strong><?php esc_html_e( 'Sample', 'executive-signal-wordpress-theme' ); ?></strong>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php if ( $course_checkout_url ) : ?>
		<aside class="es-course-enrollment" data-sticky="desktop" aria-labelledby="course-checkout-title" id="course-enrollment">
			<div class="es-course-enrollment__preview" aria-label="<?php esc_attr_e( 'Course thumbnail', 'executive-signal-wordpress-theme' ); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large' ); ?>
				<?php else : ?>
					<div class="course-single__hero-placeholder">
						<span><?php esc_html_e( 'Course', 'executive-signal-wordpress-theme' ); ?></span>
						<strong><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></strong>
					</div>
				<?php endif; ?>
			</div>

			<div class="es-course-enrollment__body">
				<p class="es-course-enrollment__eyebrow"><?php esc_html_e( 'Enrollment', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-course-enrollment__title" id="course-checkout-title"><?php esc_html_e( 'Start this course when you are ready.', 'executive-signal-wordpress-theme' ); ?></h2>
				<div class="es-course-enrollment__action">
					<a class="es-button" data-variant="primary" data-size="lg" data-full-width="true" href="<?php echo esc_url( $course_checkout_url ); ?>">
						<?php esc_html_e( 'Go to checkout', 'executive-signal-wordpress-theme' ); ?>
					</a>
				</div>
				<p class="es-course-enrollment__trust"><?php esc_html_e( 'Secure checkout handled by the course platform.', 'executive-signal-wordpress-theme' ); ?></p>
				<dl class="es-course-enrollment__meta">
					<div class="es-course-enrollment__meta-item">
						<dt class="es-course-enrollment__meta-label"><?php esc_html_e( 'Format', 'executive-signal-wordpress-theme' ); ?></dt>
						<dd class="es-course-enrollment__meta-value"><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></dd>
					</div>
					<?php if ( $course_category ) : ?>
						<div class="es-course-enrollment__meta-item">
							<dt class="es-course-enrollment__meta-label"><?php esc_html_e( 'Category', 'executive-signal-wordpress-theme' ); ?></dt>
							<dd class="es-course-enrollment__meta-value"><?php echo esc_html( $course_category->name ); ?></dd>
						</div>
					<?php endif; ?>
					<div class="es-course-enrollment__meta-item">
						<dt class="es-course-enrollment__meta-label"><?php esc_html_e( 'Access', 'executive-signal-wordpress-theme' ); ?></dt>
						<dd class="es-course-enrollment__meta-value"><?php esc_html_e( 'Self-paced', 'executive-signal-wordpress-theme' ); ?></dd>
					</div>
				</dl>
			</div>
		</aside>
	<?php endif; ?>

	<?php if ( is_array( $course_terms ) && ! empty( $course_terms ) ) : ?>
		<nav class="course-single__topics" aria-label="<?php esc_attr_e( 'Course topics', 'executive-signal-wordpress-theme' ); ?>">
			<div class="es-tabs">
				<?php foreach ( $course_terms as $course_term ) : ?>
					<a class="es-nav-link" href="<?php echo esc_url( get_term_link( $course_term ) ); ?>"><?php echo esc_html( $course_term->name ); ?></a>
				<?php endforeach; ?>
			</div>
		</nav>
	<?php endif; ?>

	<section class="es-value-stack" aria-labelledby="course-outcomes-title">
		<header class="es-value-stack__header">
			<p class="es-value-stack__eyebrow"><?php esc_html_e( 'Learning outcomes', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-value-stack__title" id="course-outcomes-title"><?php esc_html_e( 'What you will be able to apply.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p class="es-value-stack__description"><?php esc_html_e( 'Mocked outcomes show the intended section shape until structured course outcomes exist in the plugin.', 'executive-signal-wordpress-theme' ); ?></p>
		</header>
		<div class="es-value-stack__items">
			<?php foreach ( $mock_outcomes as $index => $mock_outcome ) : ?>
				<div class="es-value-stack__item">
					<div class="es-value-stack__item-copy">
						<p class="es-value-stack__item-title">
							<?php
							printf(
								/* translators: %d: Learning outcome number. */
								esc_html__( 'Outcome %d', 'executive-signal-wordpress-theme' ),
								(int) $index + 1
							);
							?>
						</p>
						<p class="es-value-stack__item-description"><?php echo esc_html( $mock_outcome ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="es-course-curriculum" data-es-course-curriculum aria-labelledby="course-curriculum-title">
		<header class="es-course-curriculum__header">
			<p class="es-course-curriculum__eyebrow"><?php esc_html_e( 'Course curriculum', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-course-curriculum__title" id="course-curriculum-title"><?php esc_html_e( 'Program structure', 'executive-signal-wordpress-theme' ); ?></h2>
			<p class="es-course-curriculum__description"><?php esc_html_e( 'Mock curriculum keeps the layout available while modules and lessons are not stored by the course plugin.', 'executive-signal-wordpress-theme' ); ?></p>
			<dl class="es-course-curriculum__summary">
				<div class="es-course-curriculum__summary-item">
					<dt><?php esc_html_e( 'Sections', 'executive-signal-wordpress-theme' ); ?></dt>
					<dd><?php esc_html_e( '3 sections', 'executive-signal-wordpress-theme' ); ?></dd>
				</div>
				<div class="es-course-curriculum__summary-item">
					<dt><?php esc_html_e( 'Lessons', 'executive-signal-wordpress-theme' ); ?></dt>
					<dd><?php esc_html_e( '9 lessons', 'executive-signal-wordpress-theme' ); ?></dd>
				</div>
				<div class="es-course-curriculum__summary-item">
					<dt><?php esc_html_e( 'Duration', 'executive-signal-wordpress-theme' ); ?></dt>
					<dd><?php esc_html_e( '42 min', 'executive-signal-wordpress-theme' ); ?></dd>
				</div>
			</dl>
			<div class="es-course-curriculum__controls">
				<button class="es-course-curriculum__control" type="button" data-es-course-curriculum-expand>
					<?php esc_html_e( 'Expand all sections', 'executive-signal-wordpress-theme' ); ?>
				</button>
				<button class="es-course-curriculum__control" type="button" data-es-course-curriculum-collapse>
					<?php esc_html_e( 'Collapse all sections', 'executive-signal-wordpress-theme' ); ?>
				</button>
			</div>
		</header>

		<div class="es-course-curriculum__sections">
			<?php foreach ( $mock_curriculum as $mock_curriculum_section ) : ?>
				<details
					id="course-curriculum-<?php echo esc_attr( $mock_curriculum_section['id'] ); ?>"
					class="es-course-curriculum__section"
					<?php if ( ! empty( $mock_curriculum_section['open'] ) ) : ?>
						open
					<?php endif; ?>
				>
					<summary class="es-course-curriculum__section-trigger">
						<span class="es-course-curriculum__section-title"><?php echo esc_html( $mock_curriculum_section['title'] ); ?></span>
						<span class="es-course-curriculum__section-meta"><?php echo esc_html( $mock_curriculum_section['meta'] ); ?></span>
					</summary>
					<ol class="es-course-curriculum__lectures">
						<?php foreach ( $mock_curriculum_section['lectures'] as $mock_curriculum_lecture ) : ?>
							<li id="course-lecture-<?php echo esc_attr( $mock_curriculum_lecture['id'] ); ?>" class="es-course-curriculum__lecture">
								<span class="es-course-curriculum__lecture-title"><?php echo esc_html( $mock_curriculum_lecture['title'] ); ?></span>
								<?php if ( ! empty( $mock_curriculum_lecture['preview'] ) ) : ?>
									<span class="es-course-curriculum__preview-badge"><?php esc_html_e( 'Preview lesson', 'executive-signal-wordpress-theme' ); ?></span>
								<?php endif; ?>
								<span class="es-course-curriculum__lecture-duration"><?php echo esc_html( $mock_curriculum_lecture['duration'] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
				</details>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="es-section-block course-single__content-section" aria-labelledby="course-requirements-title">
		<header class="es-section-header">
			<div class="es-section-header__copy">
				<p class="es-section-header__eyebrow"><?php esc_html_e( 'Requirements', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-section-header__title" id="course-requirements-title"><?php esc_html_e( 'Before you begin', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
		</header>
		<div class="es-article-prose">
			<ul>
				<?php foreach ( $mock_requirements as $mock_requirement ) : ?>
					<li><?php echo esc_html( $mock_requirement ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section id="course-content" class="es-section-block course-single__content-section" aria-labelledby="course-content-title">
		<header class="es-section-header">
			<div class="es-section-header__copy">
				<p class="es-section-header__eyebrow"><?php esc_html_e( 'Course details', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-section-header__title" id="course-content-title"><?php esc_html_e( 'About this course', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
		</header>
		<div class="entry__content es-article-prose" itemprop="description">
			<?php
			echo $course_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is filtered through the WordPress content pipeline above.
			wp_link_pages();
			?>
		</div>
	</section>

	<section class="es-audience-fit" aria-labelledby="course-audience-title">
		<header class="es-audience-fit__header">
			<p class="es-audience-fit__eyebrow"><?php esc_html_e( 'Audience fit', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-audience-fit__title" id="course-audience-title"><?php esc_html_e( 'Who this course is for.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p class="es-audience-fit__description"><?php esc_html_e( 'Mocked audience groups keep the page structure visible until the plugin provides structured audience data.', 'executive-signal-wordpress-theme' ); ?></p>
		</header>
		<div class="es-audience-fit__groups">
			<?php foreach ( $mock_audiences as $mock_audience ) : ?>
				<article class="es-audience-fit-card">
					<p class="es-audience-fit-card__label"><?php echo esc_html( $mock_audience['label'] ); ?></p>
					<h3 class="es-audience-fit-card__title"><?php echo esc_html( $mock_audience['title'] ); ?></h3>
					<p class="es-audience-fit-card__description"><?php echo esc_html( $mock_audience['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="es-instructor-bio" aria-labelledby="course-instructor-title">
		<div class="es-instructor-bio__media">
			<img src="<?php echo esc_url( $course_author_image ); ?>" alt="<?php echo esc_attr( $course_author_name ); ?>">
		</div>
		<div class="es-instructor-bio__content">
			<header class="es-instructor-bio__header">
				<p class="es-instructor-bio__eyebrow"><?php esc_html_e( 'Instructor', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-instructor-bio__name" id="course-instructor-title"><?php echo esc_html( $course_author_name ); ?></h2>
				<p class="es-instructor-bio__role"><?php esc_html_e( 'Executive Signal course author', 'executive-signal-wordpress-theme' ); ?></p>
			</header>
			<div class="es-instructor-bio__bio">
				<p>
					<?php
					echo esc_html(
						$course_author_bio
							? $course_author_bio
							: __( 'Mock instructor bio shown until the course plugin exposes dedicated instructor profile fields.', 'executive-signal-wordpress-theme' )
					);
					?>
				</p>
			</div>
			<ul class="es-instructor-bio__highlights">
				<li><?php esc_html_e( 'Connects course concepts to practical executive routines.', 'executive-signal-wordpress-theme' ); ?></li>
				<li><?php esc_html_e( 'Keeps examples focused on decisions, signals and operating rhythm.', 'executive-signal-wordpress-theme' ); ?></li>
			</ul>
		</div>
	</section>

	<section class="es-metric-strip" aria-label="<?php esc_attr_e( 'Course social proof', 'executive-signal-wordpress-theme' ); ?>">
		<div class="es-metric" data-tone="accent">
			<span><?php esc_html_e( 'Rating', 'executive-signal-wordpress-theme' ); ?></span>
			<strong><?php esc_html_e( '4.8/5', 'executive-signal-wordpress-theme' ); ?></strong>
			<p><?php esc_html_e( 'Mock average rating', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
		<div class="es-metric">
			<span><?php esc_html_e( 'Students', 'executive-signal-wordpress-theme' ); ?></span>
			<strong><?php echo esc_html( number_format_i18n( 1280 ) ); ?></strong>
			<p><?php esc_html_e( 'Mock enrollment count', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
		<div class="es-metric">
			<span><?php esc_html_e( 'Reviews', 'executive-signal-wordpress-theme' ); ?></span>
			<strong><?php echo esc_html( number_format_i18n( 96 ) ); ?></strong>
			<p><?php esc_html_e( 'Mock review volume', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
	</section>

	<section class="es-guarantee-callout" aria-labelledby="course-guarantee-title">
		<div class="es-guarantee-callout__seal" aria-hidden="true">
			<span><?php esc_html_e( '7 days', 'executive-signal-wordpress-theme' ); ?></span>
		</div>
		<div class="es-guarantee-callout__content">
			<header class="es-guarantee-callout__header">
				<p class="es-guarantee-callout__eyebrow"><?php esc_html_e( 'Guarantee', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-guarantee-callout__title" id="course-guarantee-title"><?php esc_html_e( 'Mock guarantee placeholder.', 'executive-signal-wordpress-theme' ); ?></h2>
			</header>
			<div class="es-guarantee-callout__description">
				<p><?php esc_html_e( 'This section uses temporary guarantee copy until the real checkout policy is defined.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</div>
	</section>

	<?php if ( $related_courses->have_posts() ) : ?>
		<section class="es-related-articles" data-columns="three" aria-labelledby="related-courses-title">
			<header class="es-related-articles__header">
				<div class="es-related-articles__copy">
					<p class="es-related-articles__eyebrow"><?php esc_html_e( 'Keep learning', 'executive-signal-wordpress-theme' ); ?></p>
					<h2 class="es-related-articles__title" id="related-courses-title"><?php esc_html_e( 'Related courses', 'executive-signal-wordpress-theme' ); ?></h2>
					<p class="es-related-articles__description"><?php esc_html_e( 'Continue with courses from the same track.', 'executive-signal-wordpress-theme' ); ?></p>
				</div>
			</header>

			<div class="es-related-articles__items">
				<?php
				while ( $related_courses->have_posts() ) :
					$related_courses->the_post();

					get_template_part( 'template-parts/content', 'course-card' );
				endwhile;
				?>
			</div>
		</section>
		<?php wp_reset_postdata(); ?>
	<?php endif; ?>
</article>
