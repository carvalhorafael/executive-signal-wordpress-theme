<?php
/**
 * Shared template helpers.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a compact content label.
 *
 * @param string $label Label text.
 * @param string $variant Visual variant.
 * @return void
 */
function executive_signal_render_badge( $label, $variant = 'signal' ) {
	$variant_class = sanitize_html_class( str_replace( ' ', '-', $variant ) );
	?>
	<span class="es-badge es-badge--<?php echo esc_attr( $variant_class ); ?>">
		<?php echo esc_html( $label ); ?>
	</span>
	<?php
}

/**
 * Render post metadata for editorial templates.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_post_meta( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}
	?>
	<div class="entry-meta" aria-label="<?php esc_attr_e( 'Post information', 'executive-signal-wordpress-theme' ); ?>">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>">
			<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
		</time>
		<span class="entry-meta__separator" aria-hidden="true">/</span>
		<span><?php echo esc_html( get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ) ); ?></span>
	</div>
	<?php
}

/**
 * Get the default blog eyebrow.
 *
 * @return string
 */
function executive_signal_get_blog_eyebrow_default() {
	return __( 'Executive Signal', 'executive-signal-wordpress-theme' );
}

/**
 * Get the default blog title.
 *
 * @return string
 */
function executive_signal_get_blog_title_default() {
	return __( 'Briefings for sharper decisions.', 'executive-signal-wordpress-theme' );
}

/**
 * Get the default blog description.
 *
 * @return string
 */
function executive_signal_get_blog_description_default() {
	return __( 'A running archive of strategic signals, operating notes and market context for leaders who need the useful part first.', 'executive-signal-wordpress-theme' );
}

/**
 * Get a Customizer-backed blog setting.
 *
 * @param string $setting Setting name suffix.
 * @param string $default_value Default value.
 * @return string
 */
function executive_signal_get_blog_setting( $setting, $default_value ) {
	return get_theme_mod( 'executive_signal_blog_' . $setting, $default_value );
}

/**
 * Render the WordPress primary menu inside the design-system blog header nav.
 *
 * @return void
 */
function executive_signal_render_header_navigation() {
	$locations = get_nav_menu_locations();

	if ( empty( $locations['primary'] ) ) {
		return;
	}

	$menu_items = wp_get_nav_menu_items( $locations['primary'] );

	if ( empty( $menu_items ) || is_wp_error( $menu_items ) ) {
		return;
	}

	$menu_items = array_filter(
		$menu_items,
		static function ( $item ) {
			return '0' === (string) $item->menu_item_parent;
		}
	);

	if ( empty( $menu_items ) ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$current_url = home_url( $request_uri );
	?>
	<nav class="primary-navigation es-blog-site-header__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'executive-signal-wordpress-theme' ); ?>">
		<?php foreach ( $menu_items as $item ) : ?>
			<?php
			$is_current_by_class = ! empty(
				array_intersect(
					array(
						'current-menu-item',
						'current-menu-parent',
						'current-menu-ancestor',
					),
					(array) $item->classes
				)
			);
			$is_current          = $is_current_by_class || executive_signal_normalize_url_for_comparison( $current_url ) === executive_signal_normalize_url_for_comparison( $item->url );
			?>
			<a
				class="es-blog-site-header__nav-link"
				href="<?php echo esc_url( $item->url ); ?>"
				<?php if ( $is_current ) : ?>
					aria-current="page"
					data-current="true"
				<?php endif; ?>
			>
				<?php echo esc_html( $item->title ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php
}

/**
 * Normalize a URL before comparing menu item targets with the current request.
 *
 * @param string $url URL to normalize.
 * @return string
 */
function executive_signal_normalize_url_for_comparison( $url ) {
	$parts = wp_parse_url( $url );

	if ( empty( $parts ) || ! is_array( $parts ) ) {
		return untrailingslashit( $url );
	}

	$path  = isset( $parts['path'] ) ? untrailingslashit( $parts['path'] ) : '';
	$query = isset( $parts['query'] ) ? '?' . $parts['query'] : '';

	return ( '' === $path ? '/' : $path ) . $query;
}

/**
 * Render the primary category label for an article.
 *
 * @param string   $class_name Class name for the label element.
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_primary_category( $class_name = 'es-article-card__category', $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	$categories = get_the_category( $post_id );

	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return;
	}

	$category = $categories[0];
	?>
	<p class="<?php echo esc_attr( $class_name ); ?>"><?php echo esc_html( $category->name ); ?></p>
	<?php
}

/**
 * Render portable article metadata.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_article_meta_row( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	$author_name = get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
	?>
	<div class="es-article-meta-row" aria-label="<?php esc_attr_e( 'Article information', 'executive-signal-wordpress-theme' ); ?>">
		<span class="es-article-meta-row__item">
			<span class="es-article-meta-row__label"><?php esc_html_e( 'Date', 'executive-signal-wordpress-theme' ); ?></span>
			<time class="es-article-meta-row__value" datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>">
				<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
			</time>
		</span>
		<span class="es-article-meta-row__separator" aria-hidden="true">/</span>
		<span class="es-article-meta-row__item">
			<span class="es-article-meta-row__label"><?php esc_html_e( 'By', 'executive-signal-wordpress-theme' ); ?></span>
			<span class="es-article-meta-row__value"><?php echo esc_html( $author_name ); ?></span>
		</span>
	</div>
	<?php
}

/**
 * Render pagination markup using the design-system blog contract.
 *
 * @return void
 */
function executive_signal_render_posts_pagination() {
	$links = paginate_links(
		array(
			'type'      => 'array',
			'prev_text' => esc_html__( 'Previous', 'executive-signal-wordpress-theme' ),
			'next_text' => esc_html__( 'Next', 'executive-signal-wordpress-theme' ),
		)
	);

	if ( empty( $links ) || ! is_array( $links ) ) {
		return;
	}

	$previous_link = '';
	$next_link     = '';
	$page_links    = array();

	foreach ( $links as $link ) {
		if ( false !== strpos( $link, 'prev page-numbers' ) ) {
			$previous_link = str_replace( 'prev page-numbers', 'es-blog-pagination__direction prev page-numbers', $link );
			$previous_link = str_replace( '<a ', '<a data-direction="previous" ', $previous_link );
			continue;
		}

		if ( false !== strpos( $link, 'next page-numbers' ) ) {
			$next_link = str_replace( 'next page-numbers', 'es-blog-pagination__direction next page-numbers', $link );
			$next_link = str_replace( '<a ', '<a data-direction="next" ', $next_link );
			continue;
		}

		$link = str_replace( 'class="page-numbers current"', 'class="es-blog-pagination__page page-numbers current"', $link );
		$link = str_replace( 'class="page-numbers dots"', 'class="es-blog-pagination__page page-numbers dots"', $link );
		$link = str_replace( 'class="page-numbers"', 'class="es-blog-pagination__page page-numbers"', $link );
		$link = str_replace( 'current"', 'current" data-current="true"', $link );

		$page_links[] = $link;
	}
	?>
	<nav class="es-article-archive-grid__pagination" aria-label="<?php esc_attr_e( 'Posts pagination', 'executive-signal-wordpress-theme' ); ?>">
		<div class="es-blog-pagination">
			<?php if ( $previous_link ) : ?>
				<?php echo wp_kses_post( $previous_link ); ?>
			<?php else : ?>
				<span class="es-blog-pagination__direction" data-direction="previous" aria-disabled="true"><?php esc_html_e( 'Previous', 'executive-signal-wordpress-theme' ); ?></span>
			<?php endif; ?>

			<div class="es-blog-pagination__pages">
				<?php
				foreach ( $page_links as $link ) {
					echo wp_kses_post( $link );
				}
				?>
			</div>

			<?php if ( $next_link ) : ?>
				<?php echo wp_kses_post( $next_link ); ?>
			<?php else : ?>
				<span class="es-blog-pagination__direction" data-direction="next" aria-disabled="true"><?php esc_html_e( 'Next', 'executive-signal-wordpress-theme' ); ?></span>
			<?php endif; ?>
		</div>
	</nav>
	<?php
}

/**
 * Get a concise excerpt for listings.
 *
 * @param int|null $post_id Post ID.
 * @param int      $words Maximum words.
 * @return string
 */
function executive_signal_get_listing_excerpt( $post_id = null, $words = 24 ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	$excerpt = get_the_excerpt( $post_id );

	return wp_trim_words( $excerpt, $words, ' [...]' );
}
