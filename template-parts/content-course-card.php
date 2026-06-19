<?php
/**
 * Template part for course cards.
 *
 * @package ExecutiveSignal
 */

$course_excerpt      = executive_signal_get_listing_excerpt();
$course_category     = executive_signal_get_primary_course_category();
$course_terms        = get_the_terms( get_the_ID(), EXECUTIVE_SIGNAL_COURSE_TAXONOMY );
$course_slugs        = array();

if ( is_array( $course_terms ) ) {
	$course_slugs = wp_list_pluck( $course_terms, 'slug' );
}
?>

<div class="es-resource-browser__item" data-es-resource-item data-es-resource-facets="<?php echo esc_attr( implode( ' ', $course_slugs ) ); ?>">
	<article
		id="post-<?php the_ID(); ?>"
		<?php post_class( 'es-article-card course-card' ); ?>
	>
		<?php if ( has_post_thumbnail() ) : ?>
			<a class="es-article-card__media course-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		<?php else : ?>
			<a class="es-article-card__media course-card__media course-card__media--placeholder" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<span class="course-card__placeholder-kicker"><?php esc_html_e( 'Course', 'executive-signal-wordpress-theme' ); ?></span>
				<span class="course-card__placeholder-title"><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></span>
			</a>
		<?php endif; ?>

		<div class="es-article-card__body">
			<?php if ( $course_category ) : ?>
				<p class="es-article-card__category"><?php echo esc_html( $course_category->name ); ?></p>
			<?php endif; ?>

			<h2 class="es-article-card__title">
				<a class="es-article-card__title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>

			<?php if ( $course_excerpt ) : ?>
				<p class="es-article-card__excerpt"><?php echo esc_html( $course_excerpt ); ?></p>
			<?php endif; ?>

			<div class="es-article-card__footer">
				<div class="es-article-card__action">
					<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'View course', 'executive-signal-wordpress-theme' ); ?></a>
				</div>
			</div>
		</div>
	</article>
</div>
