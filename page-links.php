<?php
/**
 * Template Name: Links — Rafael Carvalho
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header( 'links' );

if ( have_posts() ) {
	the_post();
}

$portrait           = executive_signal_get_page_portrait();
$coo_page           = get_page_by_path( 'coo-as-a-service' );
$coo_url            = $coo_page instanceof WP_Post && 'publish' === $coo_page->post_status ? get_permalink( $coo_page ) : home_url( '/coo-as-a-service/' );
$posts_page         = (int) get_option( 'page_for_posts' );
$articles_url       = $posts_page ? get_permalink( $posts_page ) : home_url( '/artigos/' );
$contact_page       = get_page_by_path( 'contato' );
$contact_url        = $contact_page instanceof WP_Post && 'publish' === $contact_page->post_status ? get_permalink( $contact_page ) : '';
$free_materials_url = executive_signal_get_free_materials_page_url();
$youtube_url        = 'https://www.youtube.com/@RafaelCarvalhoMCC?utm_medium=site&utm_source=site&utm_campaign=links&sub_confirmation=1';
?>

<main id="primary" class="rafael-links">
	<div class="rafael-links__layout">
		<header class="rafael-links__profile">
			<img class="rafael-links__portrait" src="<?php echo esc_url( $portrait['url'] ); ?>" alt="<?php echo esc_attr( $portrait['alt'] ); ?>" width="<?php echo esc_attr( $portrait['width'] ); ?>" height="<?php echo esc_attr( $portrait['height'] ); ?>" fetchpriority="high">
			<div class="rafael-links__introduction">
				<h1><?php esc_html_e( 'Estratégia só gera resultado quando vira execução.', 'executive-signal-wordpress-theme' ); ?></h1>
				<p><?php esc_html_e( 'Empreendedor e executivo. Compartilho ideias e ferramentas para quem precisa decidir, liderar e fazer acontecer.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</header>

		<section class="rafael-links__featured" aria-labelledby="rafael-links-featured-title">
			<div class="rafael-links__featured-copy">
				<p class="rafael-links__eyebrow"><?php esc_html_e( 'Atuação principal', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-links-featured-title"><?php esc_html_e( 'Sua empresa cresceu. A operação ainda depende demais de você?', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Conheça uma atuação executiva próxima para organizar prioridades, fortalecer a liderança e transformar estratégia em execução consistente.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
			<a class="es-button rafael-links__featured-action" data-variant="primary" data-size="lg" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Conheça o COO as a Service', 'executive-signal-wordpress-theme' ); ?></a>
		</section>

		<nav class="rafael-links__destinations" aria-label="<?php esc_attr_e( 'Conteúdos e recursos', 'executive-signal-wordpress-theme' ); ?>">
			<a class="rafael-links__destination" href="<?php echo esc_url( $youtube_url ); ?>" target="_blank" rel="noopener noreferrer">
				<span>
					<strong><?php esc_html_e( 'Canal no YouTube', 'executive-signal-wordpress-theme' ); ?></strong>
					<small><?php esc_html_e( 'Vídeos sobre empreendedorismo, estratégia, operação e liderança.', 'executive-signal-wordpress-theme' ); ?></small>
				</span>
				<span class="rafael-links__destination-arrow" aria-hidden="true">↗</span>
			</a>
			<a class="rafael-links__destination" href="<?php echo esc_url( $free_materials_url ); ?>">
				<span>
					<strong><?php esc_html_e( 'Materiais gratuitos', 'executive-signal-wordpress-theme' ); ?></strong>
					<small><?php esc_html_e( 'Guias e ferramentas práticas para tomar decisões e executar melhor.', 'executive-signal-wordpress-theme' ); ?></small>
				</span>
				<span class="rafael-links__destination-arrow" aria-hidden="true">→</span>
			</a>
			<a class="rafael-links__destination" href="<?php echo esc_url( $articles_url ); ?>">
				<span>
					<strong><?php esc_html_e( 'Artigos', 'executive-signal-wordpress-theme' ); ?></strong>
					<small><?php esc_html_e( 'Reflexões para quem precisa decidir e fazer acontecer.', 'executive-signal-wordpress-theme' ); ?></small>
				</span>
				<span class="rafael-links__destination-arrow" aria-hidden="true">→</span>
			</a>
		</nav>

		<nav class="rafael-links__social" aria-label="<?php esc_attr_e( 'Redes sociais e contato', 'executive-signal-wordpress-theme' ); ?>">
			<a href="https://www.instagram.com/eu.rafaelcarvalho/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Instagram', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> ↗</span></a>
			<a href="https://www.linkedin.com/in/rafaelmcarvalho/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'LinkedIn', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> ↗</span></a>
			<?php if ( $contact_url ) : ?>
				<a href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Contato', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> →</span></a>
			<?php endif; ?>
		</nav>
	</div>
</main>

<?php
get_footer( 'links' );
