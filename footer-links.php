<?php
/**
 * Focused footer for the links page.
 *
 * @package ExecutiveSignal
 */

?>
	<footer class="links-site-footer">
		<div class="links-site-footer__inner">
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
