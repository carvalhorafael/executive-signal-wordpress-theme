<?php
/**
 * Shared courses listing surface.
 *
 * @package ExecutiveSignal
 */

$courses_page             = executive_signal_get_courses_page();
$landing_page_id          = is_page() ? get_queried_object_id() : ( $courses_page ? $courses_page->ID : 0 );
$landing_page_title       = $landing_page_id ? get_the_title( $landing_page_id ) : __( 'Courses', 'executive-signal-wordpress-theme' );
$landing_page_description = '';

if ( $landing_page_id && has_excerpt( $landing_page_id ) ) {
	$landing_page_description = wpautop( get_the_excerpt( $landing_page_id ) );
} elseif ( $landing_page_id && trim( get_post_field( 'post_content', $landing_page_id ) ) ) {
	$landing_page_description = apply_filters( 'the_content', get_post_field( 'post_content', $landing_page_id ) );
}

$course_categories     = get_terms(
	array(
		'hide_empty' => true,
		'taxonomy'   => EXECUTIVE_SIGNAL_COURSE_TAXONOMY,
	)
);
$has_course_categories = ! is_wp_error( $course_categories ) && ! empty( $course_categories );
$courses_query         = new WP_Query(
	array(
		'no_found_rows'          => false,
		'post_status'            => 'publish',
		'post_type'              => EXECUTIVE_SIGNAL_COURSE_POST_TYPE,
		'posts_per_page'         => -1,
		'update_post_meta_cache' => true,
		'update_post_term_cache' => true,
	)
);
$found_posts           = (int) $courses_query->found_posts;
?>

<main id="primary" class="site-main site-main--blog site-main--courses">
	<header class="es-blog-archive-header">
		<div class="es-blog-archive-header__main">
			<div class="es-blog-archive-header__copy">
				<p class="es-blog-archive-header__eyebrow"><?php esc_html_e( 'Courses', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 class="es-blog-archive-header__title"><?php echo esc_html( $landing_page_title ); ?></h1>
				<?php if ( $landing_page_description ) : ?>
					<div class="es-blog-archive-header__description">
						<?php echo wp_kses_post( $landing_page_description ); ?>
					</div>
				<?php else : ?>
					<p class="es-blog-archive-header__description">
						<?php esc_html_e( 'Practical courses for operators who want sharper decisions, clearer routines and better execution signals.', 'executive-signal-wordpress-theme' ); ?>
					</p>
				<?php endif; ?>
			</div>
			<div class="es-blog-archive-header__meta">
				<?php
				printf(
					/* translators: %s: Number of courses found in an archive. */
					esc_html( _n( '%s course found', '%s courses found', $found_posts, 'executive-signal-wordpress-theme' ) ),
					esc_html( number_format_i18n( $found_posts ) )
				);
				?>
			</div>
		</div>
	</header>

	<section class="es-resource-browser" data-columns="two" data-es-resource-browser="true">
		<aside class="es-resource-browser__filters" aria-label="<?php esc_attr_e( 'Course categories', 'executive-signal-wordpress-theme' ); ?>">
			<div class="es-resource-browser__filters-header">
				<p id="course-filters-title" class="es-resource-browser__filters-title"><?php esc_html_e( 'Course categories', 'executive-signal-wordpress-theme' ); ?></p>
				<button class="es-resource-browser__clear" type="button" data-es-resource-clear="true">
					<?php esc_html_e( 'Clear filters', 'executive-signal-wordpress-theme' ); ?>
				</button>
			</div>

			<?php if ( $has_course_categories ) : ?>
				<form>
					<fieldset class="es-resource-browser__facet-group">
						<legend class="es-resource-browser__facet-legend"><?php esc_html_e( 'Filter by topic', 'executive-signal-wordpress-theme' ); ?></legend>
						<div class="es-resource-browser__facet-options">
						<?php foreach ( $course_categories as $course_category ) : ?>
							<label class="es-resource-browser__facet-option">
								<input
									class="es-resource-browser__facet-input"
									name="<?php echo esc_attr( EXECUTIVE_SIGNAL_COURSE_TAXONOMY ); ?>"
									type="checkbox"
									value="<?php echo esc_attr( $course_category->slug ); ?>"
									data-es-resource-filter="true"
								>
								<span class="es-resource-browser__facet-label"><?php echo esc_html( $course_category->name ); ?></span>
								<span class="es-resource-browser__facet-count"><?php echo esc_html( number_format_i18n( (int) $course_category->count ) ); ?></span>
							</label>
						<?php endforeach; ?>
						</div>
					</fieldset>
				</form>
			<?php else : ?>
				<p><?php esc_html_e( 'No course categories found.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php endif; ?>
		</aside>

		<section class="es-resource-browser__results" aria-label="<?php esc_attr_e( 'Course listing', 'executive-signal-wordpress-theme' ); ?>" aria-live="polite" data-es-resource-results="true">
			<?php if ( $courses_query->have_posts() ) : ?>
				<div class="es-resource-browser__items">
					<?php
					while ( $courses_query->have_posts() ) :
						$courses_query->the_post();

						get_template_part( 'template-parts/content', 'course-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>

				<p class="es-resource-browser__empty" hidden data-es-resource-empty="true">
					<?php esc_html_e( 'No courses match the selected categories.', 'executive-signal-wordpress-theme' ); ?>
				</p>
			<?php else : ?>
				<div class="es-article-archive-grid__empty es-article-archive-grid__empty--plain">
					<?php get_template_part( 'template-parts/content', 'none' ); ?>
				</div>
			<?php endif; ?>
		</section>
	</section>
</main>
