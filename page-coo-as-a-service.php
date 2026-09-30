<?php
/**
 * Template Name: COO as a Service
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

get_header( 'sales' );

$privacy_policy_url   = get_privacy_policy_url();
$capture_profile_slug = 'coo-as-a-service';
$capture_available    = function_exists( 'crm_leads_capture_form_fields' )
	&& function_exists( 'crm_leads_capture_render_message' )
	&& function_exists( 'crm_leads_capture' )
	&& null !== crm_leads_capture()->capture_profiles()->resolve( $capture_profile_slug );
$portrait             = executive_signal_get_page_portrait();
$utm_fields           = array( 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_name' );
?>

<main id="primary" class="coo-sales-page">
	<section class="coo-hero" aria-labelledby="coo-hero-title">
		<div class="coo-layout coo-hero__layout">
			<div class="coo-hero__content">
				<p class="coo-eyebrow"><?php esc_html_e( 'COO as a Service', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 id="coo-hero-title"><?php esc_html_e( 'Sua empresa cresceu. A operação ainda depende demais de você.', 'executive-signal-wordpress-theme' ); ?></h1>
				<p class="coo-hero__promise"><?php esc_html_e( 'Ajudo fundadores a sair do papel de gargalo e construir uma operação que escala.', 'executive-signal-wordpress-theme' ); ?></p>
				<p class="coo-hero__category"><?php esc_html_e( 'Liderança operacional sênior para empresas em crescimento que ainda não precisam de um COO em tempo integral.', 'executive-signal-wordpress-theme' ); ?></p>
				<div class="coo-hero__actions">
					<a class="es-button coo-primary-action" data-variant="primary" data-size="lg" href="#conversar"><?php esc_html_e( 'Quero conversar sobre minha operação', 'executive-signal-wordpress-theme' ); ?></a>
					<p><?php esc_html_e( 'Uma conversa inicial para entender o momento da empresa e avaliar se essa atuação faz sentido.', 'executive-signal-wordpress-theme' ); ?></p>
				</div>
			</div>

			<figure class="coo-dependency-map" aria-labelledby="coo-dependency-map-caption">
				<div class="coo-dependency-map__orbit" aria-hidden="true">
					<span class="coo-dependency-map__signal" data-position="top"><?php esc_html_e( 'Prioridades', 'executive-signal-wordpress-theme' ); ?></span>
					<span class="coo-dependency-map__signal" data-position="right"><?php esc_html_e( 'Decisões', 'executive-signal-wordpress-theme' ); ?></span>
					<span class="coo-dependency-map__signal" data-position="bottom"><?php esc_html_e( 'Pessoas', 'executive-signal-wordpress-theme' ); ?></span>
					<span class="coo-dependency-map__signal" data-position="left"><?php esc_html_e( 'Riscos', 'executive-signal-wordpress-theme' ); ?></span>
					<span class="coo-dependency-map__center"><?php esc_html_e( 'CEO', 'executive-signal-wordpress-theme' ); ?></span>
				</div>
				<figcaption id="coo-dependency-map-caption"><?php esc_html_e( 'Quando toda decisão importante converge para o fundador, crescimento também aumenta dependência.', 'executive-signal-wordpress-theme' ); ?></figcaption>
			</figure>
		</div>
	</section>

	<section class="coo-section coo-section--diagnosis" aria-labelledby="coo-diagnosis-title">
		<div class="coo-layout">
			<div class="coo-diagnosis">
				<div class="coo-section-heading">
					<p class="coo-eyebrow"><?php esc_html_e( 'O sintoma', 'executive-signal-wordpress-theme' ); ?></p>
					<h2 id="coo-diagnosis-title"><?php esc_html_e( 'O problema não é falta de esforço. A empresa cresceu além do modelo de gestão que trouxe você até aqui.', 'executive-signal-wordpress-theme' ); ?></h2>
				</div>

				<ul class="coo-symptom-list">
					<li><?php esc_html_e( 'Decisões importantes continuam voltando para você.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Gestores executam, mas ainda assumem pouca responsabilidade pelos resultados.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Prioridades mudam ou competem entre si.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Os números existem, mas não orientam suficientemente as decisões.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Problemas relevantes aparecem tarde.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'A empresa trabalha muito, mas executa com pouca previsibilidade.', 'executive-signal-wordpress-theme' ); ?></li>
				</ul>
			</div>

			<div class="es-article-prose coo-diagnosis__conclusion">
				<figure class="wp-block-pullquote">
					<blockquote>
						<p><?php esc_html_e( 'Contratar mais pessoas não resolve quando prioridades, responsabilidades e decisões continuam centralizadas.', 'executive-signal-wordpress-theme' ); ?></p>
					</blockquote>
				</figure>
			</div>
		</div>
	</section>

	<section class="coo-pivot" aria-labelledby="coo-pivot-title">
		<div class="coo-layout coo-pivot__layout">
			<div class="coo-pivot__copy">
				<p class="coo-eyebrow"><?php esc_html_e( 'A mudança necessária', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-pivot-title"><?php esc_html_e( 'Você não precisa estar em todas as decisões para continuar no controle.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'O CEO não precisa acompanhar cada movimento. Precisa de visibilidade suficiente para decidir onde sua atenção realmente muda o resultado.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<div class="coo-pivot__questions">
				<p class="coo-pivot__questions-label"><?php esc_html_e( 'Uma operação sob controle consegue responder:', 'executive-signal-wordpress-theme' ); ?></p>
				<ol>
					<li><span>01</span><p><?php esc_html_e( 'O que realmente precisa avançar agora?', 'executive-signal-wordpress-theme' ); ?></p></li>
					<li><span>02</span><p><?php esc_html_e( 'Quem tem autoridade para decidir e responder?', 'executive-signal-wordpress-theme' ); ?></p></li>
					<li><span>03</span><p><?php esc_html_e( 'Onde estão os riscos e bloqueios que exigem atenção?', 'executive-signal-wordpress-theme' ); ?></p></li>
				</ol>
			</div>
		</div>
	</section>

	<section class="coo-section" aria-labelledby="coo-solution-title">
		<div class="coo-layout">
			<div class="coo-section-heading coo-section-heading--wide">
				<p class="coo-eyebrow"><?php esc_html_e( 'A atuação', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-solution-title"><?php esc_html_e( 'Senioridade operacional ao lado do CEO.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'COO as a Service é uma atuação executiva dentro da empresa para diagnosticar gargalos, transformar estratégia em prioridades e fazer a mudança acontecer junto aos gestores.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<div class="coo-comparison" role="region" aria-label="<?php esc_attr_e( 'Diferença entre consultoria tradicional e a atuação proposta', 'executive-signal-wordpress-theme' ); ?>" tabindex="0">
				<table>
					<thead>
						<tr>
							<th scope="col"><?php esc_html_e( 'Não é consultoria tradicional', 'executive-signal-wordpress-theme' ); ?></th>
							<th scope="col"><?php esc_html_e( 'É atuação executiva', 'executive-signal-wordpress-theme' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php esc_html_e( 'Diagnóstico isolado', 'executive-signal-wordpress-theme' ); ?></td>
							<td><?php esc_html_e( 'Diagnóstico seguido de implementação', 'executive-signal-wordpress-theme' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Framework aplicado da mesma forma em qualquer empresa', 'executive-signal-wordpress-theme' ); ?></td>
							<td><?php esc_html_e( 'Ferramentas escolhidas conforme o contexto', 'executive-signal-wordpress-theme' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Aconselhamento distante', 'executive-signal-wordpress-theme' ); ?></td>
							<td><?php esc_html_e( 'Participação próxima do CEO e da liderança', 'executive-signal-wordpress-theme' ); ?></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'Equipe júnior executando recomendações', 'executive-signal-wordpress-theme' ); ?></td>
							<td><?php esc_html_e( 'Atuação direta e pessoal', 'executive-signal-wordpress-theme' ); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section id="como-funciona" class="coo-section coo-section--process" aria-labelledby="coo-process-title">
		<div class="coo-layout">
			<div class="coo-section-heading">
				<p class="coo-eyebrow"><?php esc_html_e( 'Como funciona', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-process-title"><?php esc_html_e( 'O processo é consistente. As ferramentas mudam conforme o contexto.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<ol class="coo-process-list">
				<li>
					<h3><?php esc_html_e( 'Diagnosticar', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Entender estratégia, números, pessoas, decisões, dependências e riscos.', 'executive-signal-wordpress-theme' ); ?></p>
				</li>
				<li>
					<h3><?php esc_html_e( 'Priorizar', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Identificar os poucos problemas que mais limitam a execução e definir o que precisa mudar primeiro.', 'executive-signal-wordpress-theme' ); ?></p>
				</li>
				<li>
					<h3><?php esc_html_e( 'Implementar', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Colocar prioridades, responsabilidades, indicadores e cadências de gestão em funcionamento.', 'executive-signal-wordpress-theme' ); ?></p>
				</li>
				<li>
					<h3><?php esc_html_e( 'Fortalecer a liderança', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Ajudar gestores a assumir decisões e resultados sem depender continuamente do fundador.', 'executive-signal-wordpress-theme' ); ?></p>
				</li>
				<li>
					<h3><?php esc_html_e( 'Evoluir', 'executive-signal-wordpress-theme' ); ?></h3>
					<p><?php esc_html_e( 'Acompanhar a operação, tornar problemas visíveis mais cedo e ajustar o sistema com o aprendizado real.', 'executive-signal-wordpress-theme' ); ?></p>
				</li>
			</ol>
		</div>
	</section>

	<section class="coo-section" aria-labelledby="coo-transformation-title">
		<div class="coo-layout">
			<div class="coo-section-heading coo-section-heading--wide">
				<p class="coo-eyebrow"><?php esc_html_e( 'A transformação', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-transformation-title"><?php esc_html_e( 'Uma operação que consegue executar sem pedir ao fundador cada resposta.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'A direção esperada é reduzir dependência e aumentar a capacidade executiva da empresa, sem criar processo pela organização em si.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<div class="coo-state-shift">
				<div class="coo-state-shift__labels" aria-hidden="true">
					<span><?php esc_html_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?></span>
					<span><?php esc_html_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?></span>
				</div>
				<dl>
					<div><dt data-current-label="<?php esc_attr_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Prioridades concorrentes', 'executive-signal-wordpress-theme' ); ?></dt><dd data-future-label="<?php esc_attr_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Poucas prioridades explícitas', 'executive-signal-wordpress-theme' ); ?></dd></div>
					<div><dt data-current-label="<?php esc_attr_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Decisões acumuladas no CEO', 'executive-signal-wordpress-theme' ); ?></dt><dd data-future-label="<?php esc_attr_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Gestores decidindo dentro de seus papéis', 'executive-signal-wordpress-theme' ); ?></dd></div>
					<div><dt data-current-label="<?php esc_attr_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Problemas descobertos tarde', 'executive-signal-wordpress-theme' ); ?></dt><dd data-future-label="<?php esc_attr_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Riscos e bloqueios visíveis', 'executive-signal-wordpress-theme' ); ?></dd></div>
					<div><dt data-current-label="<?php esc_attr_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Reuniões sem consequência', 'executive-signal-wordpress-theme' ); ?></dt><dd data-future-label="<?php esc_attr_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Cadência com decisão e acompanhamento', 'executive-signal-wordpress-theme' ); ?></dd></div>
					<div><dt data-current-label="<?php esc_attr_e( 'Hoje', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Execução reativa', 'executive-signal-wordpress-theme' ); ?></dt><dd data-future-label="<?php esc_attr_e( 'Evolução esperada', 'executive-signal-wordpress-theme' ); ?>"><?php esc_html_e( 'Mais clareza e previsibilidade', 'executive-signal-wordpress-theme' ); ?></dd></div>
				</dl>
			</div>
		</div>
	</section>

	<section id="para-quem" class="coo-section coo-section--fit" aria-labelledby="coo-fit-title">
		<div class="coo-layout">
			<div class="coo-section-heading coo-section-heading--wide">
				<p class="coo-eyebrow"><?php esc_html_e( 'Aderência', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-fit-title"><?php esc_html_e( 'Esse trabalho exige uma empresa pronta para mudar a forma como executa.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>

			<div class="coo-fit-grid">
				<section aria-labelledby="coo-fit-yes">
					<p class="coo-fit-grid__label"><?php esc_html_e( 'Pode fazer sentido', 'executive-signal-wordpress-theme' ); ?></p>
					<h3 id="coo-fit-yes"><?php esc_html_e( 'A empresa já possui uma operação real para transformar.', 'executive-signal-wordpress-theme' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'O produto foi validado e a empresa vende de forma consistente.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'Existe equipe e alguma estrutura de gestão.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'O crescimento aumentou a complexidade.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'O CEO reconhece que virou ponto de decisão e desbloqueio.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'Ainda não faz sentido contratar um COO sênior em tempo integral.', 'executive-signal-wordpress-theme' ); ?></li>
					</ul>
				</section>

				<section aria-labelledby="coo-fit-no">
					<p class="coo-fit-grid__label"><?php esc_html_e( 'Provavelmente não é o momento', 'executive-signal-wordpress-theme' ); ?></p>
					<h3 id="coo-fit-no"><?php esc_html_e( 'A prioridade ainda está antes da complexidade operacional.', 'executive-signal-wordpress-theme' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'A empresa ainda está validando produto ou mercado.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'Praticamente não existe equipe para liderar.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'A busca é apenas por aconselhamento pontual.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'A intenção é terceirizar a responsabilidade do CEO.', 'executive-signal-wordpress-theme' ); ?></li>
						<li><?php esc_html_e( 'Não há abertura para mudar prioridades e responsabilidades.', 'executive-signal-wordpress-theme' ); ?></li>
					</ul>
				</section>
			</div>
		</div>
	</section>

	<section class="coo-section coo-section--about" aria-labelledby="coo-about-title">
		<div class="coo-layout coo-about">
			<figure class="coo-about__portrait">
				<img src="<?php echo esc_url( $portrait['url'] ); ?>" alt="<?php echo esc_attr( $portrait['alt'] ); ?>" width="<?php echo esc_attr( $portrait['width'] ); ?>" height="<?php echo esc_attr( $portrait['height'] ); ?>" loading="lazy">
			</figure>
			<div class="coo-about__content">
				<p class="coo-eyebrow"><?php esc_html_e( 'Atuação direta', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-about-title"><?php esc_html_e( 'Eu já estive do outro lado da mesa.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Sou empreendedor e executivo há mais de 20 anos. Já construí empresas, liderei equipes e atravessei diferentes fases de crescimento.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'Conheço o problema de ser o fundador que precisa entender, decidir e desbloquear tudo. Também conheço o trabalho necessário para construir prioridades, gestores e sistemas capazes de sustentar uma operação mais madura.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'No COO as a Service, essa experiência não aparece apenas em recomendações. Eu trabalho diretamente com o CEO e com a liderança para diagnosticar, priorizar e implementar.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</div>
	</section>

	<section class="coo-section" aria-labelledby="coo-faq-title">
		<div class="coo-layout coo-faq">
			<div class="coo-section-heading">
				<p class="coo-eyebrow"><?php esc_html_e( 'Perguntas importantes', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-faq-title"><?php esc_html_e( 'Antes de avaliar se faz sentido conversar.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="coo-faq__items">
				<details>
					<summary><?php esc_html_e( 'Isso é uma consultoria?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'Não no sentido tradicional de entregar um diagnóstico e deixar a implementação com outra equipe. A atuação acontece diretamente com o CEO e os gestores, do diagnóstico à execução das mudanças prioritárias.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'COO as a Service substitui um COO em tempo integral?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'É uma forma de incorporar senioridade operacional em um estágio em que contratar um COO experiente em tempo integral ainda seria prematuro ou desproporcional.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'Minha empresa precisa já ter gestores?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'Precisa existir uma equipe e pessoas com quem seja possível dividir responsabilidades reais. Parte do trabalho pode envolver desenvolver essa liderança, mas não substitui a existência de uma operação para liderar.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'Como saber se este é o momento certo para contratar?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'Faz sentido quando a empresa já possui um produto validado, vende de forma consistente e ganhou complexidade, mas decisões, prioridades e desbloqueios ainda dependem demais do fundador. Não é o formato indicado para negócios ainda buscando validação ou apenas aconselhamento pontual.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'Quanto o CEO precisa se envolver no trabalho?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'O CEO precisa participar das decisões críticas, dar contexto e sustentar as mudanças junto à liderança. A proposta não é criar uma nova dependência, mas construir uma operação capaz de decidir e executar sem exigir a presença do CEO em cada movimento.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'Como o trabalho começa?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'O início passa por entender estratégia, números, pessoas, responsabilidades, decisões acumuladas e riscos. A partir disso, identificamos os poucos gargalos que mais limitam a execução e definimos o que precisa mudar primeiro.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
				<details>
					<summary><?php esc_html_e( 'Você aplica o mesmo modelo em todas as empresas?', 'executive-signal-wordpress-theme' ); ?></summary>
					<p><?php esc_html_e( 'Não. O processo de diagnosticar, priorizar e implementar é consistente, mas as ferramentas, cadências e estruturas são escolhidas conforme a maturidade, as pessoas e as restrições de cada empresa.', 'executive-signal-wordpress-theme' ); ?></p>
				</details>
			</div>
		</div>
	</section>

	<section id="conversar" class="coo-conversion" aria-labelledby="coo-conversion-title">
		<div class="coo-layout coo-conversion__layout">
			<div class="coo-conversion__copy">
				<p class="coo-eyebrow"><?php esc_html_e( 'Próximo passo', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="coo-conversion-title"><?php esc_html_e( 'Onde a operação ainda depende de você?', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Conte brevemente o momento da empresa. Vou analisar o contexto e entrar em contato se houver aderência para uma conversa.', 'executive-signal-wordpress-theme' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'Análise feita diretamente por Rafael.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Sem diagnóstico automático ou abordagem comercial genérica.', 'executive-signal-wordpress-theme' ); ?></li>
					<li><?php esc_html_e( 'Se não houver aderência, isso também será dito com clareza.', 'executive-signal-wordpress-theme' ); ?></li>
				</ul>
			</div>

			<div class="es-lead-form coo-lead-form">
				<?php if ( $capture_available ) : ?>
					<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" data-crm-leads-capture="<?php echo esc_attr( $capture_profile_slug ); ?>">
						<?php crm_leads_capture_form_fields( $capture_profile_slug ); ?>
						<?php foreach ( $utm_fields as $utm_field ) : ?>
							<?php $utm_value = isset( $_GET[ $utm_field ] ) ? sanitize_text_field( wp_unslash( $_GET[ $utm_field ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
							<input type="hidden" name="<?php echo esc_attr( $utm_field ); ?>" value="<?php echo esc_attr( $utm_value ); ?>">
						<?php endforeach; ?>

						<div class="coo-form-grid">
							<label class="coo-field">
								<span><?php esc_html_e( 'Nome', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="text" name="name" autocomplete="name" required>
							</label>
							<label class="coo-field">
								<span><?php esc_html_e( 'E-mail corporativo', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="email" name="email" autocomplete="email" inputmode="email" required>
							</label>
							<label class="coo-field">
								<span><?php esc_html_e( 'Empresa', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="text" name="company" autocomplete="organization" required>
							</label>
							<label class="coo-field">
								<span><?php esc_html_e( 'Seu papel', 'executive-signal-wordpress-theme' ); ?></span>
								<select name="role" required>
									<option value=""><?php esc_html_e( 'Selecione', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="founder"><?php esc_html_e( 'Fundador ou cofundador', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="ceo"><?php esc_html_e( 'CEO', 'executive-signal-wordpress-theme' ); ?></option>
									<option value="executive"><?php esc_html_e( 'Outro executivo', 'executive-signal-wordpress-theme' ); ?></option>
								</select>
							</label>
							<label class="coo-field">
								<span><?php esc_html_e( 'Site ou LinkedIn da empresa', 'executive-signal-wordpress-theme' ); ?></span>
								<input type="url" name="company_url" autocomplete="url" placeholder="https://">
							</label>
							<label class="coo-field">
								<span><?php esc_html_e( 'WhatsApp', 'executive-signal-wordpress-theme' ); ?> <small><?php esc_html_e( 'opcional', 'executive-signal-wordpress-theme' ); ?></small></span>
								<input type="tel" name="whatsapp" autocomplete="tel" inputmode="tel">
							</label>
							<label class="coo-field coo-field--full">
								<span><?php esc_html_e( 'Onde a operação mais depende de você hoje?', 'executive-signal-wordpress-theme' ); ?></span>
								<textarea name="challenge" rows="5" required></textarea>
							</label>
						</div>

						<label class="coo-consent">
							<input type="checkbox" name="consent" value="1" required>
							<span>
								<?php if ( $privacy_policy_url ) : ?>
									<?php esc_html_e( 'Concordo com o uso dos meus dados para retorno sobre este contato, conforme a', 'executive-signal-wordpress-theme' ); ?>
									<a href="<?php echo esc_url( $privacy_policy_url ); ?>"><?php esc_html_e( 'Política de Privacidade', 'executive-signal-wordpress-theme' ); ?></a>.
								<?php else : ?>
									<?php esc_html_e( 'Concordo com o uso dos meus dados para retorno sobre este contato.', 'executive-signal-wordpress-theme' ); ?>
								<?php endif; ?>
							</span>
						</label>

						<button class="es-button coo-primary-action" data-variant="primary" data-size="lg" type="submit"><?php esc_html_e( 'Quero conversar sobre minha operação', 'executive-signal-wordpress-theme' ); ?></button>
						<?php crm_leads_capture_render_message( $capture_profile_slug ); ?>
					</form>
				<?php else : ?>
					<p class="coo-form-unavailable" role="status"><?php esc_html_e( 'O formulário está temporariamente indisponível. Tente novamente mais tarde.', 'executive-signal-wordpress-theme' ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<nav aria-label="<?php esc_attr_e( 'Ação principal', 'executive-signal-wordpress-theme' ); ?>">
	<a class="coo-mobile-cta" href="#conversar"><?php esc_html_e( 'Quero conversar', 'executive-signal-wordpress-theme' ); ?></a>
</nav>

<?php
get_footer( 'sales' );
