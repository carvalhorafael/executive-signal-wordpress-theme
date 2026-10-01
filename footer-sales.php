<?php
/**
 * Focused footer for sales pages.
 *
 * @package ExecutiveSignal
 */

?>
	<footer class="sales-site-footer">
		<div class="sales-site-footer__inner">
			<p class="sales-site-footer__brand"><?php bloginfo( 'name' ); ?></p>
			<p>
				<?php
				printf(
					/* translators: %s: Current year. */
					esc_html__( 'Copyright © %s Rafael Carvalho.', 'executive-signal-wordpress-theme' ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</p>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
