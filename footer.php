<?php
/**
 * Theme footer.
 *
 * @package ExecutiveSignal
 */

?>
	<footer class="site-footer">
		<div class="site-footer__inner">
			<p>
				<?php
				printf(
					/* translators: %s: Current year. */
					esc_html__( 'Executive Signal %s', 'executive-signal-wordpress-theme' ),
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
