<?php
/**
 * Theme footer.
 *
 * @package ExecutiveSignal
 */

?>
	<footer class="site-footer es-blog-site-footer">
		<div class="site-footer__inner es-blog-site-footer__inner">
			<div class="site-footer__widgets es-blog-site-footer__links">
				<?php for ( $footer_column = 1; $footer_column <= 4; $footer_column++ ) : ?>
					<aside class="site-footer__widget-area es-blog-site-footer__group" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: Footer column number. */ __( 'Footer column %d', 'executive-signal-wordpress-theme' ), $footer_column ) ); ?>">
						<?php dynamic_sidebar( 'footer-' . $footer_column ); ?>
					</aside>
				<?php endfor; ?>
			</div>

			<div class="site-footer__bottom es-blog-site-footer__bottom">
				<div class="site-footer__legal es-blog-site-footer__legal">
					<?php if ( is_active_sidebar( 'footer-bottom' ) ) : ?>
						<?php dynamic_sidebar( 'footer-bottom' ); ?>
					<?php else : ?>
						<p>
							<?php
							printf(
								/* translators: %s: Current year. */
								esc_html__( 'Copyright © %s - Developed by Rafael Carvalho®', 'executive-signal-wordpress-theme' ),
								esc_html( gmdate( 'Y' ) )
							);
							?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
