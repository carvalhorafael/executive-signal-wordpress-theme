<?php
/**
 * Template Name: Palestras
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

get_header( 'speaking' );

if ( have_posts() ) {
	the_post();
}

$portrait             = executive_signal_get_page_portrait();
$privacy_policy_url   = get_privacy_policy_url();
$capture_profile_slug = 'speaker-invitation';
$capture_available    = function_exists( 'crm_leads_capture_form_fields' )
	&& function_exists( 'crm_leads_capture_render_message' )
	&& function_exists( 'crm_leads_capture' )
	&& null !== crm_leads_capture()->capture_profiles()->resolve( $capture_profile_slug );
$utm_fields           = array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_name' );
$speaking_videos      = array(
	array(
		'id'    => '1MIaHnWisrU',
		'url'   => 'https://www.youtube.com/watch?v=1MIaHnWisrU',
		'title' => esc_html__( 'Como construir negócios que não param de crescer no digital', 'executive-signal-wordpress-theme' ),
	),
	array(
		'id'    => 'uThiFciz-14',
		'url'   => 'https://www.youtube.com/watch?v=uThiFciz-14',
		'title' => esc_html__( 'Vendas de infoprodutos: esqueça tudo que já ouviu sobre ganhar dinheiro na internet', 'executive-signal-wordpress-theme' ),
	),
	array(
		'id'    => '72aXZzF9lyk',
		'url'   => 'https://www.youtube.com/watch?v=72aXZzF9lyk',
		'title' => esc_html__( 'O segredo por trás dos negócios que não param de crescer', 'executive-signal-wordpress-theme' ),
	),
	array(
		'id'    => 'PSkfPahhMx4',
		'url'   => 'https://www.youtube.com/watch?v=PSkfPahhMx4',
		'title' => esc_html__( 'Novidades do mercado para afiliados: tendências e estratégias avançadas', 'executive-signal-wordpress-theme' ),
	),
	array(
		'id'    => '6146xJHLR5k',
		'url'   => 'https://www.youtube.com/watch?v=6146xJHLR5k',
		'title' => esc_html__( 'Por que negócios quebram? Palestra no Sebrae sobre empreendedorismo digital', 'executive-signal-wordpress-theme' ),
	),
);
?>

<main id="primary" class="speaking-page">
	<section class="speaking-hero" aria-labelledby="speaking-title">
		<div class="speaking-layout speaking-hero__layout">
			<div class="speaking-hero__copy">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Palestras com Rafael Carvalho', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 id="speaking-title"><?php esc_html_e( 'As decisões reais por trás de empreender, liderar e crescer.', 'executive-signal-wordpress-theme' ); ?></h1>
				<p class="speaking-hero__summary"><?php esc_html_e( 'Experiência de quem construiu empresas, liderou operações e conhece as escolhas que acontecem longe das fórmulas prontas.', 'executive-signal-wordpress-theme' ); ?></p>
				<div class="speaking-actions">
					<a class="es-button speaking-primary-action" data-variant="primary" data-size="lg" href="#conversar"><?php esc_html_e( 'Quero convidar Rafael', 'executive-signal-wordpress-theme' ); ?></a>
					<a class="speaking-text-link" href="#temas"><?php esc_html_e( 'Conhecer os temas', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> ↓</span></a>
				</div>
			</div>

			<figure class="speaking-hero__portrait">
				<img src="<?php echo esc_url( $portrait['url'] ); ?>" alt="<?php echo esc_attr( $portrait['alt'] ); ?>" width="<?php echo esc_attr( $portrait['width'] ); ?>" height="<?php echo esc_attr( $portrait['height'] ); ?>" fetchpriority="high">
				<figcaption><?php esc_html_e( 'Empreendedor · Executivo · Autor', 'executive-signal-wordpress-theme' ); ?></figcaption>
			</figure>
		</div>
	</section>

	<section class="speaking-section speaking-intent" aria-labelledby="speaking-intent-title">
		<div class="speaking-layout speaking-intent__layout">
			<div class="speaking-heading">
				<p class="speaking-eyebrow"><?php esc_html_e( 'O que fica depois', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-intent-title"><?php esc_html_e( 'Uma boa palestra não termina no aplauso.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Ela abre novas perguntas, organiza ideias e cria conversas que continuam quando o evento termina.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<ol class="speaking-intent__list">
				<li><span>01</span><p><?php esc_html_e( 'Provocar uma leitura mais honesta sobre os desafios de empreender e liderar.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>02</span><p><?php esc_html_e( 'Dar linguagem para decisões que muitas lideranças vivem, mas raramente discutem.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>03</span><p><?php esc_html_e( 'Transformar reflexão em conversas práticas sobre o que precisa mudar.', 'executive-signal-wordpress-theme' ); ?></p></li>
			</ol>
		</div>
	</section>

	<section id="temas" class="speaking-section speaking-topics" aria-labelledby="speaking-topics-title">
		<div class="speaking-layout">
			<div class="speaking-heading speaking-heading--wide">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Temas', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-topics-title"><?php esc_html_e( 'Histórias, tensões e aprendizados que não cabem em fórmulas prontas.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<article class="speaking-featured-topic">
				<p class="speaking-featured-topic__index">01</p>
				<div>
					<p class="speaking-featured-topic__label"><?php esc_html_e( 'Palestra principal', 'executive-signal-wordpress-theme' ); ?></p>
					<h3><?php esc_html_e( 'As verdades sobre empreendedorismo que não te contaram', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Uma conversa sobre a distância entre a versão romantizada do empreendedorismo e as decisões reais que envolvem risco, pessoas, crescimento e responsabilidade.', 'executive-signal-wordpress-theme' ); ?></p>
				</div>
			</article>

			<div class="speaking-topic-list">
				<article>
					<p>02</p>
					<h3><?php esc_html_e( 'Crescer sem virar o gargalo da empresa', 'executive-signal-wordpress-theme' ); ?></h3>
					<span><?php esc_html_e( 'O que muda no papel do fundador quando a empresa cresce e a centralização deixa de ajudar.', 'executive-signal-wordpress-theme' ); ?></span>
				</article>
				<article>
					<p>03</p>
					<h3><?php esc_html_e( 'Da estratégia à execução', 'executive-signal-wordpress-theme' ); ?></h3>
					<span><?php esc_html_e( 'Por que boas ideias travam e como prioridades, liderança e cadência transformam intenção em movimento.', 'executive-signal-wordpress-theme' ); ?></span>
				</article>
			</div>

			<p class="speaking-topics__note"><?php esc_html_e( 'O recorte e os exemplos são ajustados ao contexto do evento, ao perfil do público e à conversa que o encontro precisa provocar.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
	</section>

	<section id="videos" class="speaking-section speaking-videos" aria-labelledby="speaking-videos-title">
		<div class="speaking-layout">
			<div class="speaking-heading speaking-heading--wide">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Palestras em vídeo', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-videos-title"><?php esc_html_e( 'Veja as ideias acontecendo no palco.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Uma seleção de palestras para conhecer o ritmo, a linguagem e a forma como Rafael conduz conversas com diferentes públicos.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<div class="speaking-videos__showcase">
				<a class="speaking-video speaking-video--featured" href="<?php echo esc_url( $speaking_videos[0]['url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<span class="speaking-video__media">
						<img src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $speaking_videos[0]['id'] . '/maxresdefault.jpg' ); ?>" alt="" width="1280" height="720" loading="lazy" decoding="async" referrerpolicy="no-referrer">
						<span class="speaking-video__play" aria-hidden="true"></span>
					</span>
					<span class="speaking-video__copy">
						<span class="speaking-video__label"><?php esc_html_e( 'Palestra completa em destaque', 'executive-signal-wordpress-theme' ); ?></span>
						<strong><?php echo esc_html( $speaking_videos[0]['title'] ); ?></strong>
						<span class="speaking-video__action"><?php esc_html_e( 'Assistir no YouTube', 'executive-signal-wordpress-theme' ); ?> <span aria-hidden="true">↗</span><span class="screen-reader-text"> <?php esc_html_e( 'Abre em nova aba.', 'executive-signal-wordpress-theme' ); ?></span></span>
					</span>
				</a>

				<div class="speaking-videos__rail">
					<a class="speaking-video speaking-video--secondary" href="<?php echo esc_url( $speaking_videos[1]['url'] ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="speaking-video__media">
							<img src="<?php echo esc_url( 'https://i.ytimg.com/vi/' . $speaking_videos[1]['id'] . '/maxresdefault.jpg' ); ?>" alt="" width="1280" height="720" loading="lazy" decoding="async" referrerpolicy="no-referrer">
							<span class="speaking-video__play" aria-hidden="true"></span>
						</span>
						<span class="speaking-video__copy">
							<span class="speaking-video__label"><?php esc_html_e( 'Também vale assistir', 'executive-signal-wordpress-theme' ); ?></span>
							<strong><?php echo esc_html( $speaking_videos[1]['title'] ); ?></strong>
						</span>
					</a>

					<div class="speaking-videos__more" aria-label="<?php esc_attr_e( 'Outras palestras em vídeo', 'executive-signal-wordpress-theme' ); ?>">
						<?php foreach ( array_slice( $speaking_videos, 2 ) as $index => $video ) : ?>
							<a href="<?php echo esc_url( $video['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span><?php echo esc_html( sprintf( '%02d', $index + 3 ) ); ?></span>
								<strong><?php echo esc_html( $video['title'] ); ?></strong>
								<span aria-hidden="true">↗</span>
								<span class="screen-reader-text"> <?php esc_html_e( 'Abre em nova aba.', 'executive-signal-wordpress-theme' ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="speaking-section speaking-contexts" aria-labelledby="speaking-contexts-title">
		<div class="speaking-layout speaking-contexts__layout">
			<div class="speaking-heading">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Onde essa conversa faz sentido', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-contexts-title"><?php esc_html_e( 'Para públicos que vivem decisões de verdade.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<ul class="speaking-contexts__list">
				<li><?php esc_html_e( 'Eventos de empreendedorismo e inovação', 'executive-signal-wordpress-theme' ); ?></li>
				<li><?php esc_html_e( 'Encontros de fundadores e lideranças', 'executive-signal-wordpress-theme' ); ?></li>
				<li><?php esc_html_e( 'Convenções e reuniões estratégicas', 'executive-signal-wordpress-theme' ); ?></li>
				<li><?php esc_html_e( 'Programas de formação executiva', 'executive-signal-wordpress-theme' ); ?></li>
				<li><?php esc_html_e( 'Comunidades e ecossistemas de negócios', 'executive-signal-wordpress-theme' ); ?></li>
			</ul>
		</div>
	</section>

	<section class="speaking-section speaking-experience" aria-labelledby="speaking-experience-title">
		<div class="speaking-layout speaking-experience__layout">
			<div>
				<p class="speaking-eyebrow"><?php esc_html_e( 'Experiência aplicada', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-experience-title"><?php esc_html_e( 'O conteúdo vem de experiência vivida, não apenas observada.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="speaking-experience__copy">
				<p><?php esc_html_e( 'Rafael Carvalho é empreendedor e executivo há mais de 20 anos. Construiu empresas, liderou equipes e operações e acompanhou de perto as diferentes fases de crescimento de um negócio.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'É autor de Paixão S.A. e hoje trabalha ao lado de fundadores e lideranças para conectar estratégia, decisão e execução.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</div>
	</section>

	<section id="formatos" class="speaking-section speaking-formats" aria-labelledby="speaking-formats-title">
		<div class="speaking-layout">
			<div class="speaking-heading">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Formatos', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-formats-title"><?php esc_html_e( 'A conversa certa para cada tipo de encontro.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<div class="speaking-format-list">
				<article><span>01</span><h3><?php esc_html_e( 'Palestra principal', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Uma narrativa estruturada para grandes públicos, eventos e convenções.', 'executive-signal-wordpress-theme' ); ?></p></article>
				<article><span>02</span><h3><?php esc_html_e( 'Conversa para lideranças', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Um encontro mais próximo para aprofundar tensões e decisões com grupos executivos.', 'executive-signal-wordpress-theme' ); ?></p></article>
				<article><span>03</span><h3><?php esc_html_e( 'Painéis e encontros', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Participação em conversas mediadas, comunidades e formatos especiais.', 'executive-signal-wordpress-theme' ); ?></p></article>
			</div>
			<p class="speaking-formats__note"><?php esc_html_e( 'Os formatos podem acontecer presencialmente ou online, conforme o desenho do evento.', 'executive-signal-wordpress-theme' ); ?></p>
		</div>
	</section>

	<section id="como-funciona" class="speaking-section speaking-process" aria-labelledby="speaking-process-title">
		<div class="speaking-layout">
			<div class="speaking-heading">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Como funciona', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-process-title"><?php esc_html_e( 'Do primeiro contato ao dia do evento.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<ol class="speaking-process__list">
				<li><h3><?php esc_html_e( 'Briefing', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Entendemos o evento, o público e o objetivo da conversa.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><h3><?php esc_html_e( 'Alinhamento', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Definimos tema, recorte e formato mais adequados.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><h3><?php esc_html_e( 'Preparação', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'A narrativa é ajustada ao contexto e às tensões do público.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><h3><?php esc_html_e( 'Palestra', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Rafael conduz uma conversa direta, relevante e conectada ao encontro.', 'executive-signal-wordpress-theme' ); ?></p></li>
			</ol>
		</div>
	</section>

	<section class="speaking-section speaking-faq" aria-labelledby="speaking-faq-title">
		<div class="speaking-layout speaking-faq__layout">
			<div class="speaking-heading">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Perguntas frequentes', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-faq-title"><?php esc_html_e( 'Antes de fazer o convite.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="speaking-faq__list">
				<details><summary><?php esc_html_e( 'O conteúdo pode ser adaptado ao evento?', 'executive-signal-wordpress-theme' ); ?></summary><p><?php esc_html_e( 'Sim. O tema central é preservado, mas o recorte, os exemplos e a ênfase são definidos a partir do público e do objetivo do encontro.', 'executive-signal-wordpress-theme' ); ?></p></details>
				<details><summary><?php esc_html_e( 'A palestra pode ser presencial ou online?', 'executive-signal-wordpress-theme' ); ?></summary><p><?php esc_html_e( 'Sim. O formato é alinhado durante o briefing de acordo com a dinâmica do evento.', 'executive-signal-wordpress-theme' ); ?></p></details>
				<details><summary><?php esc_html_e( 'É possível fazer um encontro apenas para lideranças?', 'executive-signal-wordpress-theme' ); ?></summary><p><?php esc_html_e( 'Sim. Conversas com grupos executivos permitem aprofundar dilemas específicos e abrir mais espaço para interação.', 'executive-signal-wordpress-theme' ); ?></p></details>
				<details><summary><?php esc_html_e( 'Como funciona a contratação?', 'executive-signal-wordpress-theme' ); ?></summary><p><?php esc_html_e( 'Envie o contexto inicial do evento. A partir dele, avaliamos a aderência, alinhamos o formato e seguimos com a proposta.', 'executive-signal-wordpress-theme' ); ?></p></details>
			</div>
		</div>
	</section>

	<section id="conversar" class="speaking-contact" aria-labelledby="speaking-contact-title">
		<div class="speaking-layout speaking-contact__layout">
			<div class="speaking-contact__copy">
				<p class="speaking-eyebrow"><?php esc_html_e( 'Convite', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="speaking-contact-title"><?php esc_html_e( 'Conte um pouco sobre o evento.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Compartilhe quem está organizando, o objetivo e o formato do encontro. Rafael avaliará pessoalmente a aderência do convite.', 'executive-signal-wordpress-theme' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Dados para retorno e organização responsável', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Contexto e objetivo do encontro', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Formato desejado', 'executive-signal-wordpress-theme' ); ?></li>
				</ul>
			</div>
			<div class="es-lead-form speaking-contact__form">
				<?php if ( $capture_available ) : ?>
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" data-crm-leads-capture="<?php echo esc_attr( $capture_profile_slug ); ?>">
						<?php crm_leads_capture_form_fields( $capture_profile_slug ); ?>
						<?php foreach ( $utm_fields as $utm_field ) : ?>
							<?php $utm_value = isset( $_GET[ $utm_field ] ) ? sanitize_text_field( wp_unslash( $_GET[ $utm_field ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
							<input type="hidden" name="<?php echo esc_attr( $utm_field ); ?>" value="<?php echo esc_attr( $utm_value ); ?>">
						<?php endforeach; ?>

						<div class="speaking-contact__form-grid">
							<label class="speaking-contact__field">
								<span><?php esc_html_e( 'Nome', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="text" name="name" autocomplete="name" required>
							</label>
							<label class="speaking-contact__field">
								<span><?php esc_html_e( 'E-mail', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="email" name="email" autocomplete="email" inputmode="email" required>
							</label>
							<label class="speaking-contact__field">
								<span><?php esc_html_e( 'WhatsApp', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="tel" name="whatsapp" autocomplete="tel" inputmode="tel" required>
							</label>
							<label class="speaking-contact__field">
								<span><?php esc_html_e( 'Empresa ou organização', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="text" name="organization" autocomplete="organization" required>
							</label>
							<label class="speaking-contact__field speaking-contact__field--full">
								<span><?php esc_html_e( 'Qual é o objetivo do encontro?', 'executive-signal-wordpress-theme' ); ?></span>
								<textarea name="objective_context" rows="4" required></textarea>
							</label>
							<label class="speaking-contact__field speaking-contact__field--full">
								<span><?php esc_html_e( 'Formato', 'executive-signal-wordpress-theme' ); ?></span>
								<select name="format" required>
									<option value=""><?php esc_html_e( 'Selecione', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="presencial"><?php esc_html_e( 'Presencial', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="online"><?php esc_html_e( 'Online', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="hibrido"><?php esc_html_e( 'Híbrido', 'executive-signal-wordpress-theme' ); ?></option>
								</select>
							</label>
						</div>

						<label class="speaking-contact__consent">
							<input type="checkbox" name="consent" value="1" required>
							<span>
								<?php if ( $privacy_policy_url ) : ?>
									<?php esc_html_e( 'Concordo com o uso dos meus dados para avaliação e retorno sobre este convite, conforme a', 'executive-signal-wordpress-theme' ); ?>
									<a href="<?php echo esc_url( $privacy_policy_url ); ?>"><?php esc_html_e( 'Política de Privacidade', 'executive-signal-wordpress-theme' ); ?></a>.
								<?php else : ?>
									<?php esc_html_e( 'Concordo com o uso dos meus dados para avaliação e retorno sobre este convite.', 'executive-signal-wordpress-theme' ); ?>
								<?php endif; ?>
							</span>
						</label>

						<button class="es-button speaking-contact__submit" data-variant="primary" data-size="lg" type="submit"><?php esc_html_e( 'Enviar convite para avaliação', 'executive-signal-wordpress-theme' ); ?></button>
						<?php crm_leads_capture_render_message( $capture_profile_slug ); ?>
					</form>
				<?php else : ?>
					<div class="speaking-contact__unavailable" role="status">
						<p><?php esc_html_e( 'O formulário de convite ainda não está configurado.', 'executive-signal-wordpress-theme' ); ?></p>
						<span><?php esc_html_e( 'Quando a captação for ativada, ela aparecerá aqui sem alterar este template.', 'executive-signal-wordpress-theme' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<nav class="speaking-mobile-action" aria-label="<?php esc_attr_e( 'Ação principal', 'executive-signal-wordpress-theme' ); ?>">
	<a class="es-button speaking-primary-action" data-variant="primary" data-size="lg" href="#conversar"><?php esc_html_e( 'Quero convidar Rafael', 'executive-signal-wordpress-theme' ); ?></a>
</nav>

<?php
get_footer( 'sales' );
