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

	<?php if ( $course_checkout_url ) : ?>
		<section class="es-offer-band" aria-labelledby="course-checkout-title">
			<div class="es-offer-band__copy">
				<p class="es-offer-band__eyebrow"><?php esc_html_e( 'Enrollment', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 class="es-offer-band__title" id="course-checkout-title"><?php esc_html_e( 'Start this course when you are ready.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p class="es-offer-band__description"><?php esc_html_e( 'Use the checkout link to complete enrollment outside WordPress.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
			<div class="es-offer-band__conversion">
				<div class="es-offer-band__actions">
					<a class="es-button" data-variant="primary" data-size="lg" data-full-width="true" href="<?php echo esc_url( $course_checkout_url ); ?>">
						<?php esc_html_e( 'Go to checkout', 'executive-signal-wordpress-theme' ); ?>
					</a>
				</div>
				<p class="es-offer-band__trust"><?php esc_html_e( 'Secure checkout handled by the course platform.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</section>
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
