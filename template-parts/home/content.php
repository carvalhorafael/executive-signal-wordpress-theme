<?php
/**
 * Content for the Rafael Carvalho home page.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portrait_url  = get_theme_file_uri( 'assets/images/rafael-carvalho-coo-as-a-service.jpeg' );
$coo_page      = get_page_by_path( 'coo-as-a-service' );
$coo_url       = $coo_page instanceof WP_Post && 'publish' === $coo_page->post_status ? get_permalink( $coo_page ) : home_url( '/coo-as-a-service/' );
$posts_page    = (int) get_option( 'page_for_posts' );
$articles_url  = $posts_page ? get_permalink( $posts_page ) : home_url( '/artigos/' );
$contact_page  = get_page_by_path( 'contato' );
$contact_url   = $contact_page instanceof WP_Post && 'publish' === $contact_page->post_status ? get_permalink( $contact_page ) : '';
$speaking_page = get_page_by_path( 'palestras' );
$speaking_url  = $speaking_page instanceof WP_Post && 'publish' === $speaking_page->post_status ? get_permalink( $speaking_page ) : '';
$articles      = new WP_Query(
	array(
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
		'post_type'           => 'post',
		'posts_per_page'      => 4,
	)
);
?>

<section class="rafael-home__hero" aria-labelledby="rafael-home-title">
	<div class="rafael-home__layout rafael-home__hero-layout">
		<div class="rafael-home__hero-copy">
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Rafael Carvalho · Empreendedor e executivo', 'executive-signal-wordpress-theme' ); ?></p>
			<h1 id="rafael-home-title"><?php esc_html_e( 'Ajudo fundadores a sair do papel de gargalo e construir uma operação que escala.', 'executive-signal-wordpress-theme' ); ?></h1>
			<p class="rafael-home__hero-summary"><?php esc_html_e( 'Trabalho ao lado de CEOs e lideranças para transformar estratégia em prioridades, decisões e execução.', 'executive-signal-wordpress-theme' ); ?></p>
			<div class="rafael-home__actions">
				<a class="es-button" data-variant="primary" data-size="lg" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Conheça o COO as a Service', 'executive-signal-wordpress-theme' ); ?></a>
				<a class="rafael-home__text-link" href="#ideias"><?php esc_html_e( 'Leia minhas ideias', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> ↓</span></a>
			</div>
		</div>

		<figure class="rafael-home__portrait">
			<img src="<?php echo esc_url( $portrait_url ); ?>" alt="<?php esc_attr_e( 'Rafael Carvalho sentado, em retrato com fundo neutro.', 'executive-signal-wordpress-theme' ); ?>" width="550" height="550" fetchpriority="high">
			<figcaption>
				<span><?php esc_html_e( 'Estratégia', 'executive-signal-wordpress-theme' ); ?></span>
				<span><?php esc_html_e( 'Operação', 'executive-signal-wordpress-theme' ); ?></span>
				<span><?php esc_html_e( 'Liderança', 'executive-signal-wordpress-theme' ); ?></span>
			</figcaption>
		</figure>
	</div>
</section>

<nav class="rafael-home__routes" aria-label="<?php esc_attr_e( 'Explorar o site', 'executive-signal-wordpress-theme' ); ?>">
	<div class="rafael-home__layout rafael-home__route-list">
		<a href="#atuacao"><span>01</span><?php esc_html_e( 'Resolver um problema de operação', 'executive-signal-wordpress-theme' ); ?></a>
		<a href="#ideias"><span>02</span><?php esc_html_e( 'Acompanhar ideias e análises', 'executive-signal-wordpress-theme' ); ?></a>
		<a href="#sobre"><span>03</span><?php esc_html_e( 'Conhecer minha trajetória', 'executive-signal-wordpress-theme' ); ?></a>
	</div>
</nav>

<section class="rafael-home__section rafael-home__thesis" aria-labelledby="rafael-home-thesis-title">
	<div class="rafael-home__layout">
		<div class="rafael-home__section-heading">
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Uma mudança de papel', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="rafael-home-thesis-title"><?php esc_html_e( 'Quando a empresa cresce, o papel do fundador também precisa mudar.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p><?php esc_html_e( 'No início, centralizar decisões ajuda a empresa a avançar. Conforme a operação cresce, a mesma centralização começa a limitar pessoas, prioridades e resultados.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>

		<ol class="rafael-home__growth-path">
			<li>
				<span>01</span>
				<h3><?php esc_html_e( 'Validar', 'executive-signal-wordpress-theme' ); ?></h3>
				<p><?php esc_html_e( 'O fundador concentra contexto, velocidade e decisões.', 'executive-signal-wordpress-theme' ); ?></p>
			</li>
			<li>
				<span>02</span>
				<h3><?php esc_html_e( 'Crescer', 'executive-signal-wordpress-theme' ); ?></h3>
				<p><?php esc_html_e( 'Mais clientes, pessoas e frentes aumentam a complexidade.', 'executive-signal-wordpress-theme' ); ?></p>
			</li>
			<li>
				<span>03</span>
				<h3><?php esc_html_e( 'Construir capacidade', 'executive-signal-wordpress-theme' ); ?></h3>
				<p><?php esc_html_e( 'A liderança assume decisões e a operação ganha clareza.', 'executive-signal-wordpress-theme' ); ?></p>
			</li>
		</ol>
	</div>
</section>

<section id="atuacao" class="rafael-home__offer" aria-labelledby="rafael-home-offer-title">
	<div class="rafael-home__layout rafael-home__offer-layout">
		<div class="rafael-home__offer-copy">
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Atuação principal', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="rafael-home-offer-title"><?php esc_html_e( 'Senioridade operacional ao lado do CEO.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p><?php esc_html_e( 'COO as a Service é uma atuação próxima para diagnosticar gargalos, organizar prioridades e fortalecer a capacidade de execução da liderança.', 'executive-signal-wordpress-theme' ); ?></p>
			<a class="es-button rafael-home__offer-action" data-variant="primary" data-size="lg" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Entenda como funciona', 'executive-signal-wordpress-theme' ); ?></a>
		</div>

		<ul class="rafael-home__outcomes">
			<li><span>01</span><?php esc_html_e( 'Poucas prioridades, claramente assumidas.', 'executive-signal-wordpress-theme' ); ?></li>
			<li><span>02</span><?php esc_html_e( 'Gestores decidindo dentro de seus papéis.', 'executive-signal-wordpress-theme' ); ?></li>
			<li><span>03</span><?php esc_html_e( 'Riscos e bloqueios visíveis mais cedo.', 'executive-signal-wordpress-theme' ); ?></li>
		</ul>
	</div>
</section>

<section id="ideias" class="rafael-home__section rafael-home__ideas" aria-labelledby="rafael-home-ideas-title">
	<div class="rafael-home__layout">
		<div class="rafael-home__section-heading rafael-home__section-heading--with-action">
			<div>
				<p class="rafael-home__eyebrow"><?php esc_html_e( 'Pensamento em público', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-home-ideas-title"><?php esc_html_e( 'Ideias para quem precisa decidir e fazer acontecer.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<a class="rafael-home__text-link" href="<?php echo esc_url( $articles_url ); ?>"><?php esc_html_e( 'Ver todos os artigos', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> →</span></a>
		</div>

		<?php if ( $articles->have_posts() ) : ?>
			<div class="rafael-home__article-layout">
				<?php
				$articles->the_post();
				?>
				<article <?php post_class( 'es-featured-article-card rafael-home__featured-article' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="es-featured-article-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
							<?php the_post_thumbnail( 'large' ); ?>
						</a>
					<?php endif; ?>
					<div class="es-featured-article-card__body">
						<?php executive_signal_render_primary_category( 'es-featured-article-card__category' ); ?>
						<h3 class="es-featured-article-card__title"><a class="es-featured-article-card__title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="es-featured-article-card__excerpt"><?php echo esc_html( executive_signal_get_listing_excerpt() ); ?></p>
						<div class="es-featured-article-card__footer">
							<?php executive_signal_render_article_meta_row(); ?>
							<a class="es-article-card__action" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'executive-signal-wordpress-theme' ); ?></a>
						</div>
					</div>
				</article>

				<?php if ( $articles->have_posts() ) : ?>
					<div class="rafael-home__article-list">
						<?php while ( $articles->have_posts() ) : ?>
							<?php $articles->the_post(); ?>
							<article class="rafael-home__article-list-item">
								<div>
									<?php executive_signal_render_primary_category( 'rafael-home__article-category' ); ?>
									<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								</div>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
							</article>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else : ?>
			<p class="rafael-home__empty"><?php esc_html_e( 'Novos artigos serão publicados em breve.', 'executive-signal-wordpress-theme' ); ?></p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>

<section id="sobre" class="rafael-home__section rafael-home__experience" aria-labelledby="rafael-home-experience-title">
	<div class="rafael-home__layout rafael-home__experience-layout">
		<div class="rafael-home__section-heading">
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Experiência aplicada', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="rafael-home-experience-title"><?php esc_html_e( 'Eu já estive do outro lado da mesa.', 'executive-signal-wordpress-theme' ); ?></h2>
			<p><?php esc_html_e( 'Sou empreendedor e executivo há mais de 20 anos. Já construí empresas, liderei equipes e atravessei diferentes fases de crescimento.', 'executive-signal-wordpress-theme' ); ?></p>
			<p><?php esc_html_e( 'Hoje, uso essa experiência para trabalhar diretamente com fundadores e lideranças, conectando diagnóstico, decisão e implementação.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>

		<ol class="rafael-home__experience-path">
			<li><span><?php esc_html_e( 'Construir', 'executive-signal-wordpress-theme' ); ?></span><?php esc_html_e( 'Empresas, produtos e novos mercados.', 'executive-signal-wordpress-theme' ); ?></li>
			<li><span><?php esc_html_e( 'Liderar', 'executive-signal-wordpress-theme' ); ?></span><?php esc_html_e( 'Pessoas e operações em diferentes fases de crescimento.', 'executive-signal-wordpress-theme' ); ?></li>
			<li><span><?php esc_html_e( 'Acompanhar', 'executive-signal-wordpress-theme' ); ?></span><?php esc_html_e( 'Fundadores diante da complexidade que vem depois da validação.', 'executive-signal-wordpress-theme' ); ?></li>
		</ol>
	</div>
</section>

<section class="rafael-home__section rafael-home__archive" aria-labelledby="rafael-home-archive-title">
	<div class="rafael-home__layout">
		<div class="rafael-home__section-heading">
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Acervo', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="rafael-home-archive-title"><?php esc_html_e( 'Outras formas de acompanhar meu trabalho.', 'executive-signal-wordpress-theme' ); ?></h2>
		</div>

		<div class="rafael-home__archive-list">
			<article>
				<p><?php esc_html_e( 'Livro', 'executive-signal-wordpress-theme' ); ?></p>
				<h3><?php esc_html_e( 'Paixão S.A.', 'executive-signal-wordpress-theme' ); ?></h3>
				<span><?php esc_html_e( 'Uma reflexão sobre transformar paixão em negócio.', 'executive-signal-wordpress-theme' ); ?></span>
			</article>
			<a href="<?php echo esc_url( executive_signal_get_free_materials_page_url() ); ?>">
				<p><?php esc_html_e( 'Recursos', 'executive-signal-wordpress-theme' ); ?></p>
				<h3><?php esc_html_e( 'Materiais gratuitos', 'executive-signal-wordpress-theme' ); ?></h3>
				<span><?php esc_html_e( 'Guias e ferramentas para decisões mais claras.', 'executive-signal-wordpress-theme' ); ?></span>
			</a>
			<a href="<?php echo esc_url( executive_signal_get_courses_page_url() ); ?>">
				<p><?php esc_html_e( 'Aprendizado', 'executive-signal-wordpress-theme' ); ?></p>
				<h3><?php esc_html_e( 'Cursos', 'executive-signal-wordpress-theme' ); ?></h3>
				<span><?php esc_html_e( 'Conteúdos estruturados para colocar ideias em prática.', 'executive-signal-wordpress-theme' ); ?></span>
			</a>
		</div>
	</div>
</section>

<?php if ( $speaking_url ) : ?>
	<section class="rafael-home__speaking" aria-labelledby="rafael-home-speaking-title">
		<div class="rafael-home__layout rafael-home__speaking-layout">
			<div>
				<p class="rafael-home__eyebrow"><?php esc_html_e( 'Palestras e conversas', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-home-speaking-title"><?php esc_html_e( 'Para eventos e equipes que querem conversar sobre empreendedorismo, liderança e execução.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<a class="rafael-home__text-link" href="<?php echo esc_url( $speaking_url ); ?>"><?php esc_html_e( 'Conhecer palestras', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> →</span></a>
		</div>
	</section>
<?php endif; ?>

<section class="rafael-home__final-cta" aria-labelledby="rafael-home-final-title">
	<div class="rafael-home__layout rafael-home__final-layout">
		<div>
			<p class="rafael-home__eyebrow"><?php esc_html_e( 'Próximo passo', 'executive-signal-wordpress-theme' ); ?></p>
			<h2 id="rafael-home-final-title"><?php esc_html_e( 'Sua empresa cresceu. A operação ainda depende demais de você?', 'executive-signal-wordpress-theme' ); ?></h2>
			<p><?php esc_html_e( 'Conheça uma atuação executiva construída para reduzir dependência, fortalecer a liderança e fazer a estratégia avançar.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
		<div class="rafael-home__actions">
			<a class="es-button" data-variant="primary" data-size="lg" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Conheça o COO as a Service', 'executive-signal-wordpress-theme' ); ?></a>
			<?php if ( $contact_url ) : ?>
				<a class="rafael-home__text-link" href="<?php echo esc_url( $contact_url ); ?>"><?php esc_html_e( 'Entrar em contato', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> →</span></a>
			<?php endif; ?>
		</div>
	</div>
</section>
