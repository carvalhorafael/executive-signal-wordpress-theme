<?php
/**
 * Related posts template part.
 *
 * @package ExecutiveSignal
 */

$current_post_id = get_the_ID();

if ( ! $current_post_id ) {
	return;
}

$category_ids = wp_get_post_categories( $current_post_id );

if ( empty( $category_ids ) ) {
	return;
}

$related_posts = new WP_Query(
	array(
		'category__in'        => $category_ids,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post__not_in'        => array( $current_post_id ),
		'posts_per_page'      => 3,
	)
);

if ( ! $related_posts->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<section class="es-related-articles" data-columns="three" aria-labelledby="related-articles-title">
	<header class="es-related-articles__header">
		<div class="es-related-articles__copy">
			<p class="es-related-articles__eyebrow"><?php esc_html_e( 'Read next', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 class="es-related-articles__title" id="related-articles-title"><?php esc_html_e( 'Related articles', 'executive-signal-wordpress-theme' ); ?></h2>
			<p class="es-related-articles__description"><?php esc_html_e( 'Continue with articles from the same editorial context.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
	</header>

	<div class="es-related-articles__items">
		<?php
		while ( $related_posts->have_posts() ) :
			$related_posts->the_post();

			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
		?>
	</div>
</section>

<?php
wp_reset_postdata();
