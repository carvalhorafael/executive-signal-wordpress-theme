<?php
/**
 * Template part for a single course page.
 *
 * @package ExecutiveSignal
 */

$course_id           = get_the_ID();
$course_category     = executive_signal_get_primary_course_category( $course_id );
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
	<div class="course-single__page-layout">
		<div class="course-single__masthead-layout">
			<div class="course-single__main-column">
				<header class="es-sales-hero course-single__hero">
					<div class="es-sales-hero__content">
						<div class="es-sales-hero__copy">
							<p class="es-sales-hero__eyebrow"><?php esc_html_e( 'Online course', 'executive-signal-wordpress-theme' ); ?></p>
							<?php the_title( '<h1 class="es-sales-hero__title" itemprop="name">', '</h1>' ); ?>
							<?php if ( has_excerpt() ) : ?>
								<p class="es-sales-hero__description" itemprop="description"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</header>
			</div>
		</div>

		<?php if ( $course_checkout_url ) : ?>
			<aside class="course-single__sidebar" aria-label="<?php esc_attr_e( 'Course enrollment', 'executive-signal-wordpress-theme' ); ?>">
				<div class="es-course-enrollment" data-sticky="desktop" aria-labelledby="course-checkout-title" id="course-enrollment">
					<div class="es-course-enrollment__preview" aria-label="<?php esc_attr_e( 'Course preview', 'executive-signal-wordpress-theme' ); ?>">
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
						<p class="es-course-enrollment__eyebrow"><?php esc_html_e( 'Preview and enrollment', 'executive-signal-wordpress-theme' ); ?></p>
						<h2 class="es-course-enrollment__title" id="course-checkout-title"><?php esc_html_e( 'Start this course when you are ready.', 'executive-signal-wordpress-theme' ); ?></h2>
						<div class="es-course-enrollment__action">
							<a class="es-button" data-variant="primary" data-size="sm" data-full-width="true" href="<?php echo esc_url( $course_checkout_url ); ?>">
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
				</div>
			</aside>
		<?php endif; ?>

		<div class="course-single__body-layout">
			<div class="course-single__main-column">
				<div id="course-content" class="entry__content course-single__editor-content" itemprop="description">
					<?php
					echo $course_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Content is filtered through the WordPress content pipeline above.
					wp_link_pages();
					?>
				</div>
			</div>
		</div>
	</div>

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
