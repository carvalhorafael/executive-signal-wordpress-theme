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

	$menu_tree = array();

	foreach ( $menu_items as $item ) {
		$parent_id = (string) $item->menu_item_parent;

		if ( '0' === $parent_id ) {
			$menu_tree[ $item->ID ] = array(
				'item'     => $item,
				'children' => array(),
			);
			continue;
		}

		if ( isset( $menu_tree[ (int) $parent_id ] ) ) {
			$menu_tree[ (int) $parent_id ]['children'][] = $item;
		}
	}

	if ( empty( $menu_tree ) ) {
		return;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$current_url = home_url( $request_uri );
	?>
	<nav id="primary-navigation" class="primary-navigation es-blog-site-header__nav" aria-label="<?php esc_attr_e( 'Primary menu', 'executive-signal-wordpress-theme' ); ?>">
		<ul class="es-blog-site-header__nav-list">
			<?php foreach ( $menu_tree as $menu_node ) : ?>
				<?php
				$item       = $menu_node['item'];
				$children   = $menu_node['children'];
				$is_current = executive_signal_is_menu_item_current( $item, $current_url );
				?>
				<li
					class="es-blog-site-header__nav-item"
					<?php if ( ! empty( $children ) ) : ?>
						data-has-submenu="true"
					<?php endif; ?>
				>
					<?php $submenu_id = 'primary-submenu-' . (int) $item->ID; ?>
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

					<?php if ( ! empty( $children ) ) : ?>
						<button class="es-blog-site-header__submenu-toggle" type="button" aria-expanded="false" aria-controls="<?php echo esc_attr( $submenu_id ); ?>">
							<span class="es-blog-site-header__submenu-toggle-label"><?php esc_html_e( 'Open submenu', 'executive-signal-wordpress-theme' ); ?></span>
							<span aria-hidden="true">⌄</span>
						</button>
						<ul class="es-blog-site-header__submenu" id="<?php echo esc_attr( $submenu_id ); ?>">
							<?php foreach ( $children as $child ) : ?>
								<?php $is_child_current = executive_signal_is_menu_item_current( $child, $current_url ); ?>
								<li class="es-blog-site-header__submenu-item">
									<a
										class="es-blog-site-header__submenu-link"
										href="<?php echo esc_url( $child->url ); ?>"
										<?php if ( $is_child_current ) : ?>
											aria-current="page"
											data-current="true"
										<?php endif; ?>
									>
										<?php echo esc_html( $child->title ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Check whether a menu item points to the current request.
 *
 * @param WP_Post $item Menu item object.
 * @param string  $current_url Current URL.
 * @return bool
 */
function executive_signal_is_menu_item_current( $item, $current_url ) {
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

	return $is_current_by_class || executive_signal_normalize_url_for_comparison( $current_url ) === executive_signal_normalize_url_for_comparison( $item->url );
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
 * Render compact search in the public header.
 *
 * @return void
 */
function executive_signal_render_header_search() {
	get_search_form(
		array(
			'aria_label'     => __( 'Search', 'executive-signal-wordpress-theme' ),
			'id'             => 'header-search-field',
			'class'          => 'es-blog-site-header-search',
			'submit_label'   => __( 'Search', 'executive-signal-wordpress-theme' ),
			'shortcut_label' => __( 'Cmd K', 'executive-signal-wordpress-theme' ),
		)
	);
}

/**
 * Render the public header feed link using the design-system contract.
 *
 * @return void
 */
function executive_signal_render_header_feed_link() {
	?>
	<a class="es-blog-site-header-feed-link" href="<?php echo esc_url( get_feed_link() ); ?>" aria-label="<?php esc_attr_e( 'RSS feed', 'executive-signal-wordpress-theme' ); ?>">
		<span aria-hidden="true"><?php esc_html_e( 'RSS', 'executive-signal-wordpress-theme' ); ?></span>
		<span class="es-blog-site-header-feed-link__label"><?php esc_html_e( 'RSS feed', 'executive-signal-wordpress-theme' ); ?></span>
	</a>
	<?php
}

/**
 * Render the theme mode switcher for the public header.
 *
 * @return void
 */
function executive_signal_render_theme_switcher() {
	$options = array(
		'light'  => __( 'Light', 'executive-signal-wordpress-theme' ),
		'dark'   => __( 'Dark', 'executive-signal-wordpress-theme' ),
		'system' => __( 'System', 'executive-signal-wordpress-theme' ),
	);
	?>
	<div class="es-blog-theme-switcher" role="group" aria-label="<?php esc_attr_e( 'Theme', 'executive-signal-wordpress-theme' ); ?>" data-es-theme-switcher>
		<button class="es-blog-theme-switcher__trigger" type="button" aria-expanded="false">
			<span><?php esc_html_e( 'Theme', 'executive-signal-wordpress-theme' ); ?></span>
		</button>
		<div class="es-blog-theme-switcher__menu">
			<?php foreach ( $options as $value => $label ) : ?>
				<button class="es-blog-theme-switcher__option" type="button" data-es-theme-option="<?php echo esc_attr( $value ); ?>" aria-pressed="<?php echo 'light' === $value ? 'true' : 'false'; ?>">
					<?php echo esc_html( $label ); ?>
				</button>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
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
	$author_url  = get_author_posts_url( (int) get_post_field( 'post_author', $post_id ) );
	?>
	<div class="es-article-meta-row" aria-label="<?php esc_attr_e( 'Article information', 'executive-signal-wordpress-theme' ); ?>">
		<span class="es-article-meta-row__item">
			<span class="es-article-meta-row__label"><?php esc_html_e( 'Date', 'executive-signal-wordpress-theme' ); ?></span>
			<time class="es-article-meta-row__value" datetime="<?php echo esc_attr( get_the_date( DATE_W3C, $post_id ) ); ?>" itemprop="datePublished">
				<?php echo esc_html( get_the_date( '', $post_id ) ); ?>
			</time>
		</span>
		<span class="es-article-meta-row__separator" aria-hidden="true">/</span>
		<span class="es-article-meta-row__item" itemprop="author" itemscope itemtype="https://schema.org/Person">
			<span class="es-article-meta-row__label"><?php esc_html_e( 'By', 'executive-signal-wordpress-theme' ); ?></span>
			<a class="es-article-meta-row__value" href="<?php echo esc_url( $author_url ); ?>" rel="author" itemprop="url">
				<span itemprop="name"><?php echo esc_html( $author_name ); ?></span>
			</a>
		</span>
	</div>
	<?php
}

/**
 * Render post tags for single article navigation.
 *
 * @param int|null $post_id Post ID.
 * @return void
 */
function executive_signal_render_article_tags( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_the_ID();

	if ( ! $post_id ) {
		return;
	}

	$tags = get_the_tags( $post_id );

	if ( empty( $tags ) || is_wp_error( $tags ) ) {
		return;
	}
	?>
	<nav class="es-article-tags" aria-label="<?php esc_attr_e( 'Article tags', 'executive-signal-wordpress-theme' ); ?>">
		<p class="es-article-tags__title"><?php esc_html_e( 'Tagged in', 'executive-signal-wordpress-theme' ); ?></p>
		<ul class="es-article-tags__list">
			<?php foreach ( $tags as $tag ) : ?>
				<li>
					<a class="es-article-tags__link" href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>
	<?php
}

/**
 * Render a single comment with design-system comment thread classes.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args Comment list arguments.
 * @param int        $depth Comment depth.
 * @return void
 */
function executive_signal_render_comment( $comment, $args, $depth ) {
	?>
	<article id="comment-<?php comment_ID(); ?>" <?php comment_class( 'es-comment-thread__comment', $comment ); ?>>
		<div class="es-comment-thread__avatar">
			<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>
		<div class="es-comment-thread__body">
			<div class="es-comment-thread__meta-row">
				<p class="es-comment-thread__author"><?php echo wp_kses_post( get_comment_author_link( $comment ) ); ?></p>
				<a class="es-comment-thread__meta" href="<?php echo esc_url( get_comment_link( $comment ) ); ?>">
					<time datetime="<?php echo esc_attr( get_comment_time( DATE_W3C, false, $comment ) ); ?>">
						<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
					</time>
				</a>
			</div>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="es-comment-thread__meta"><?php esc_html_e( 'Your comment is awaiting moderation.', 'executive-signal-wordpress-theme' ); ?></p>
			<?php endif; ?>

			<div class="es-comment-thread__content">
				<?php comment_text( $comment ); ?>
			</div>

			<div class="es-comment-thread__actions">
				<?php
				echo wp_kses_post(
					get_comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below'  => 'comment',
								'depth'      => $depth,
								'max_depth'  => $args['max_depth'],
								'reply_text' => esc_html__( 'Reply', 'executive-signal-wordpress-theme' ),
							)
						),
						$comment
					)
				);
				?>
			</div>
		</div>
	</article>
	<?php
}

/**
 * Render pagination markup using the design-system blog contract.
 *
 * @return void
 */
function executive_signal_render_posts_pagination() {
	global $wp_query;

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

	$current_page = max( 1, (int) get_query_var( 'paged' ) );
	$total_pages  = isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : 1;
	?>
	<nav class="es-article-archive-grid__pagination" aria-label="<?php esc_attr_e( 'Posts pagination', 'executive-signal-wordpress-theme' ); ?>">
		<div class="es-blog-pagination">
			<p class="es-blog-pagination__status">
				<?php
				printf(
					/* translators: 1: Current page number. 2: Total number of pages. */
					esc_html__( 'Page %1$s of %2$s', 'executive-signal-wordpress-theme' ),
					esc_html( number_format_i18n( $current_page ) ),
					esc_html( number_format_i18n( $total_pages ) )
				);
				?>
			</p>

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
