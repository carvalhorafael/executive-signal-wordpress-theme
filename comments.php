<?php
/**
 * Comments template.
 *
 * @package ExecutiveSignal
 */

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area es-comment-thread">
	<header class="es-comment-thread__header">
		<h2 class="es-comment-thread__title">
			<?php
			$comments_number = get_comments_number();

			if ( '1' === $comments_number ) {
				esc_html_e( '1 comment', 'executive-signal-wordpress-theme' );
			} else {
				printf(
					/* translators: %s: Number of comments. */
					esc_html__( '%s comments', 'executive-signal-wordpress-theme' ),
					esc_html( number_format_i18n( $comments_number ) )
				);
			}
			?>
		</h2>
		<p class="es-comment-thread__description"><?php esc_html_e( 'Join the conversation with context, questions or practical notes.', 'executive-signal-wordpress-theme' ); ?></p>
	</header>

	<?php if ( have_comments() ) : ?>
		<div class="es-comment-thread__list">
			<?php
			wp_list_comments(
				array(
					'avatar_size' => 48,
					'callback'    => 'executive_signal_render_comment',
					'style'       => 'div',
				)
			);
			?>
		</div>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav class="es-blog-pagination comments-pagination" aria-label="<?php esc_attr_e( 'Comments pagination', 'executive-signal-wordpress-theme' ); ?>">
				<?php
				paginate_comments_links(
					array(
						'prev_text' => esc_html__( 'Previous comments', 'executive-signal-wordpress-theme' ),
						'next_text' => esc_html__( 'Next comments', 'executive-signal-wordpress-theme' ),
					)
				);
				?>
			</nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="es-comment-thread__empty"><?php esc_html_e( 'No comments yet. Be the first to add a useful signal.', 'executive-signal-wordpress-theme' ); ?></p>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<?php
		comment_form(
			array(
				'class_container'    => 'comment-respond es-comment-form',
				'class_form'         => 'comment-form es-comment-form__fields',
				'title_reply_before' => '<div class="es-comment-form__header"><h2 id="reply-title" class="comment-reply-title es-comment-form__title">',
				'title_reply_after'  => '</h2><p class="es-comment-form__description">' . esc_html__( 'Add a clear comment that helps the next reader decide what matters.', 'executive-signal-wordpress-theme' ) . '</p></div>',
				'label_submit'       => esc_html__( 'Publish comment', 'executive-signal-wordpress-theme' ),
				'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s es-button es-button--primary" value="%4$s">%4$s</button>',
			)
		);
		?>
	<?php endif; ?>
</section>
