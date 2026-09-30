<?php
/**
 * Template Name: Sobre — Rafael Carvalho
 * Template Post Type: page
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( have_posts() ) {
	the_post();
}

$portrait              = executive_signal_get_page_portrait();
$coo_page              = get_page_by_path( 'coo-as-a-service' );
$coo_url               = $coo_page instanceof WP_Post && 'publish' === $coo_page->post_status ? get_permalink( $coo_page ) : home_url( '/coo-as-a-service/' );
$speaking_page         = get_page_by_path( 'palestras' );
$speaking_url          = $speaking_page instanceof WP_Post && 'publish' === $speaking_page->post_status ? get_permalink( $speaking_page ) : home_url( '/palestras/' );
$timeline_allowed_html = array(
	'a' => array(
		'href'   => true,
		'rel'    => true,
		'target' => true,
	),
);
$timeline              = array(
	array(
		'period'     => '2008',
		'role'       => esc_html__( 'Primeira startup', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Da engenharia à construção de um negócio.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			esc_html__( 'Graduado em Engenharia de Telecomunicações pela Universidade Federal Fluminense e com pós-graduação em Gestão de Negócios pelo Ibmec-Rio, Rafael começou a carreira aprendendo a decompor problemas complexos e construir soluções a partir de restrições reais.', 'executive-signal-wordpress-theme' ),
			sprintf(
				/* translators: 1: opening link to the Peta5 article, 2: closing link, 3: opening link to the academic article about segmented TV advertising, 4: closing link. */
				__( 'Ainda durante a universidade, %1$scofundou a Peta5%2$s, startup de %3$stecnologia voltada à publicidade segmentada na televisão digital%4$s. O projeto reuniu uma equipe de 20 pessoas, conquistou investimento e participou de programas de aceleração em um mercado que ainda dava seus primeiros passos.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://oglobo.globo.com/economia/no-universo-das-start-ups-fracasso-quase-regra-10112227' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://periodicos.ufjf.br/index.php/lumina/article/view/20895' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			esc_html__( 'A experiência deixou uma lição que seguiria presente nos anos seguintes: tecnologia e inovação são importantes, mas não substituem clareza de mercado, modelo de negócio e capacidade de execução.', 'executive-signal-wordpress-theme' ),
		),
	),
	array(
		'period'     => '2010–2012',
		'role'       => esc_html__( 'Ecossistema e educação', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Ajudar empreendedores também exigia construir o ambiente ao redor.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			sprintf(
				/* translators: 1: opening link to the StartupBase article, 2: closing link, 3: opening link to the ABStartups article, 4: closing link. */
				__( 'Quando o termo startup ainda era pouco conhecido no Brasil, Rafael participou da %1$scriação do StartupBase%2$s, uma plataforma colaborativa para mapear startups, empreendedores, investidores e aceleradoras. Como cofundador e CTO, ajudou a construir a visão, a estratégia e a tecnologia do projeto, que %3$smais tarde se tornou a base oficial da Associação Brasileira de Startups%4$s.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://startupi.com.br/startupbase-o-crunchbase-com-jeitinho-brasileiro-2/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://revistapegn.globo.com/Startups/noticia/2014/11/abstartups-se-torna-responsavel-por-cadastro-de-empreendedores-digitais-brasileiros.html' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			sprintf(
				/* translators: 1: opening link to the Sebrae-SC article, 2: closing link, 3: opening link to the Sebrae-RJ article, 4: closing link. */
				__( 'Em 2011, cofundou a Bizstart para apoiar profissionais e empreendedores na transformação de ideias em negócios mais consistentes. A empresa capacitou mais de 50 mil empreendedores, gestores e consultores por meio de programas, workshops e iniciativas desenvolvidas com organizações como %1$sSebrae-SC%2$s, %3$sSebrae-RJ%4$s e Startup Rio.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://www.startupsc.com.br/startup-sc-abre-inscricoes-para-o-2o-programa-de-capacitacao/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://www.ti.rio/1o-sebrae-startup-rio/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			sprintf(
				/* translators: 1: opening link to the article about Rafael's students, 2: closing link. */
				__( 'No mesmo período, foi %1$sprofessor de Ciência da Computação%2$s e procurou aproximar algoritmos e arquitetura de computadores dos desafios reais de mercado.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://rafaelcarvalho.tv/alunos-de-ciencia-da-computacao-com-empreendedorismo-na-veio/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
		),
	),
	array(
		'period'     => '2013–2018',
		'role'       => esc_html__( 'Edools', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Transformar produto em empresa.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			esc_html__( 'Em 2013, Rafael cofundou a Edools, uma plataforma para criar, gerir e comercializar cursos online. A empresa começou com três sócios e cresceu de forma bootstrapped até reunir uma equipe de 35 pessoas e superar R$ 4 milhões em receita recorrente anual.', 'executive-signal-wordpress-theme' ),
			esc_html__( 'Construir a Edools significou participar da definição de estratégia, desenvolvimento de tecnologia, vendas, gestão de pessoas, captação de recursos e criação dos sistemas necessários para acompanhar o crescimento.', 'executive-signal-wordpress-theme' ),
			sprintf(
				/* translators: 1: opening link to the 500 Startups article, 2: closing link, 3: opening link to the Google Launchpad article, 4: closing link, 5: opening link to the Entrepreneur of the Year award article, 6: closing link, 7: opening link to the Startup of the Year award article, 8: closing link. */
				__( 'A empresa participou da %1$s500 Startups no Vale do Silício%2$s e do %3$sGoogle Launchpad Accelerator%4$s. Em 2016, %5$sRafael recebeu o prêmio Empreendedor de Sucesso na categoria Startups%6$s. Dois anos depois, a %7$sEdools foi reconhecida como Startup do Ano%8$s.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://revistapegn.globo.com/Startups/noticia/2015/05/edools-recebe-aporte-da-500-startups-e-mira-mercado-americano.html' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://vocesa.abril.com.br/geral/programa-do-google-quer-ajudar-startups-brasileiras-a-crescer/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://revistapegn.globo.com/Empreendedor-de-Sucesso/noticia/2016/12/hi-technologies-vence-o-premio-empreendedor-de-sucesso-2016.html' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://exame.com/pme/conheca-os-vencedores-do-oscar-das-startups-brasileiras/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
		),
	),
	array(
		'period'     => '2019–2023',
		'role'       => esc_html__( 'HeroSpark · COO', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Integrar empresas e liderar uma operação maior.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			sprintf(
				/* translators: 1: opening link to the Edools and Eadbox merger article, 2: closing link. */
				__( 'Em 2019, Rafael liderou as negociações para a %1$sfusão entre Edools e Eadbox, movimento que deu origem à HeroSpark%2$s e ampliou o portfólio, a equipe e a complexidade de gestão.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://tiinside.com.br/04/07/2019/startups-edools-e-eadbox-se-fundem-e-criam-a-herospark/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			esc_html__( 'Como cofundador e COO, liderou uma equipe de mais de 190 profissionais e teve sob sua responsabilidade áreas como Customer Success, Suporte, Vendas, Marketing e Gestão de Pessoas. Seu trabalho envolvia transformar estratégia em prioridades executáveis, estruturar indicadores e criar alinhamento entre líderes com responsabilidades distintas.', 'executive-signal-wordpress-theme' ),
			sprintf(
				/* translators: 1: opening link to the HeroSpark Series A article, 2: closing link. */
				__( 'Também participou diretamente da integração das empresas, da organização da cadência executiva e da %1$srodada Series A%2$s, com posição permanente na liderança e reporte ao CEO e ao conselho.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://pipelinevalor.globo.com/startups/noticia/com-capital-da-alexia-ventures-herospark-quer-transformar-profissionais-em-professores.ghtml' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
		),
	),
	array(
		'period'     => '2023–2025',
		'role'       => esc_html__( 'HeroSpark · CMO', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Levar estratégia e liderança para a comunicação.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			sprintf(
				/* translators: 1: opening link to a HeroSpark video featuring Rafael, 2: closing link. */
				__( 'Ao assumir como CMO, Rafael ampliou seu campo de atuação. Além de liderar estratégia e operação de marketing, passou a %1$srepresentar a empresa de maneira mais direta em conteúdos%2$s, eventos e conversas com o mercado.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://www.youtube.com/watch?v=oTzR3Lul5po' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			sprintf(
				/* translators: 1: opening link to the HeroSpark YouTube search results for Rafael Carvalho, 2: closing link. */
				__( 'Foram %1$smais de 250 vídeos produzidos para o canal da HeroSpark no YouTube%2$s, além de palestras, eventos e iniciativas de posicionamento. Essa fase reforçou outra dimensão da liderança: uma estratégia só mobiliza pessoas quando pode ser comunicada com clareza.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://www.youtube.com/@HeroSpark/search?query=carvalho' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			esc_html__( 'Em janeiro de 2025, deixou a operação diária da HeroSpark e permaneceu ligado à empresa como sócio, encerrando um ciclo de mais de uma década iniciado com a criação da Edools.', 'executive-signal-wordpress-theme' ),
		),
	),
	array(
		'period'     => '2025–hoje',
		'role'       => esc_html__( 'Quest Edu', 'executive-signal-wordpress-theme' ),
		'title'      => esc_html__( 'Aplicar repertório empreendedor em uma nova escala.', 'executive-signal-wordpress-theme' ),
		'paragraphs' => array(
			sprintf(
				/* translators: 1: opening link to Quest Edu, 2: closing link, 3: opening link to Yduqs, 4: closing link. */
				__( 'Rafael iniciou uma nova etapa como %1$sdiretor de Operações e Negócios na Quest Edu%2$s, ecossistema de educação especializada que faz parte da %3$sYduqs%4$s e reúne marcas como Qconcursos, Damásio, ProEnem, ProMedicina e outras operações educacionais.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://questedu.dev/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>',
				'<a href="' . esc_url( 'https://www.yduqs.com.br/' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
			esc_html__( 'Primeiro, assumiu a operação de cursos digitais da Estácio Online. Liderou o reposicionamento do portal, a organização de novas academias, a criação de canais de conteúdo e uma estratégia de lançamentos digitais. A operação alcançou o melhor mês do ano, o segundo melhor resultado de sua história até então e um aumento de 186% no ticket médio.', 'executive-signal-wordpress-theme' ),
			sprintf(
				/* translators: 1: opening link to the article about Yduqs acquiring ProEnem and ProMedicina, 2: closing link. */
				__( 'Na sequência, passou a %1$sliderar a unidade formada por ProEnem e ProMedicina%2$s, conectando experiências em tecnologia, educação, aquisição e integração de empresas, posicionamento e gestão de unidades de negócio a uma estrutura corporativa de maior escala.', 'executive-signal-wordpress-theme' ),
				'<a href="' . esc_url( 'https://pipelinevalor.globo.com/negocios/noticia/yduqs-compra-promedicina-proenem-e-eumilitar-fortalecendo-cursinhos.ghtml' ) . '" target="_blank" rel="noopener noreferrer">',
				'</a>'
			),
		),
	),
);
?>

<main id="primary" class="rafael-about">
	<section class="rafael-about__hero" aria-labelledby="rafael-about-title">
		<div class="rafael-about__layout rafael-about__hero-layout">
			<div class="rafael-about__hero-copy">
				<p class="rafael-about__eyebrow"><?php esc_html_e( 'Sobre Rafael Carvalho', 'executive-signal-wordpress-theme' ); ?></p>
				<h1 id="rafael-about-title"><?php esc_html_e( 'Construí empresas, liderei operações e aprendi a transformar ambição em execução.', 'executive-signal-wordpress-theme' ); ?></h1>
				<p><?php esc_html_e( 'Sou empreendedor e executivo há mais de 20 anos, com uma trajetória construída na interseção entre tecnologia, educação e gestão.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>

			<figure class="rafael-about__portrait">
				<img src="<?php echo esc_url( $portrait['url'] ); ?>" alt="<?php echo esc_attr( $portrait['alt'] ); ?>" width="<?php echo esc_attr( $portrait['width'] ); ?>" height="<?php echo esc_attr( $portrait['height'] ); ?>" fetchpriority="high">
				<figcaption><?php esc_html_e( 'Empreendedor · Executivo · Autor', 'executive-signal-wordpress-theme' ); ?></figcaption>
			</figure>
		</div>
	</section>

	<section class="rafael-about__opening" aria-labelledby="rafael-about-opening-title">
		<div class="rafael-about__layout rafael-about__opening-layout">
			<div>
				<p class="rafael-about__eyebrow"><?php esc_html_e( 'Experiência aplicada', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-about-opening-title"><?php esc_html_e( 'A trajetória não foi uma sequência de cargos. Foi uma sequência de problemas reais para resolver.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="rafael-about__opening-copy">
				<p><?php esc_html_e( 'Rafael viveu diferentes fases de uma empresa: a incerteza de transformar uma ideia em produto, a busca pelas primeiras vendas, a formação de equipes, a construção de cultura, a pressão por metas, a captação de investimento, a integração entre negócios e o desafio de fazer uma operação continuar avançando quando a complexidade aumenta.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'Essa vivência formou uma convicção que orienta seu trabalho: empresas não deixam de crescer apenas por falta de boas ideias. Muitas vezes, elas perdem velocidade porque decisões, prioridades e responsabilidades continuam concentradas no fundador, mesmo depois que o negócio já se tornou grande demais para funcionar dessa forma.', 'executive-signal-wordpress-theme' ); ?></p>
			</div>
		</div>
		<blockquote class="rafael-about__manifesto">
			<p><?php esc_html_e( 'Construir uma empresa exige coragem para começar. Fazer essa empresa crescer exige aprender a decidir, priorizar e construir uma operação que não dependa de uma única pessoa.', 'executive-signal-wordpress-theme' ); ?></p>
		</blockquote>
	</section>

	<section class="rafael-about__timeline" aria-labelledby="rafael-about-timeline-title">
		<div class="rafael-about__layout rafael-about__timeline-layout">
			<header class="rafael-about__timeline-heading">
				<p class="rafael-about__eyebrow"><?php esc_html_e( 'Trajetória', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-about-timeline-title"><?php esc_html_e( 'Uma carreira vivida em diferentes escalas.', 'executive-signal-wordpress-theme' ); ?></h2>
				<p><?php esc_html_e( 'Da primeira startup a operações com centenas de profissionais, cada etapa ampliou o repertório para decidir, liderar e executar.', 'executive-signal-wordpress-theme' ); ?></p>
			</header>

			<div class="rafael-about__timeline-list">
				<?php foreach ( $timeline as $chapter ) : ?>
					<article class="rafael-about__chapter">
						<div class="rafael-about__chapter-meta">
							<p><?php echo esc_html( $chapter['period'] ); ?></p>
							<span><?php echo esc_html( $chapter['role'] ); ?></span>
						</div>
						<div class="rafael-about__chapter-copy">
							<h3><?php echo esc_html( $chapter['title'] ); ?></h3>
							<?php foreach ( $chapter['paragraphs'] as $paragraph ) : ?>
								<p><?php echo wp_kses( $paragraph, $timeline_allowed_html ); ?></p>
							<?php endforeach; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="rafael-about__roles" aria-labelledby="rafael-about-roles-title">
		<div class="rafael-about__layout">
			<div class="rafael-about__roles-heading">
				<p class="rafael-about__eyebrow"><?php esc_html_e( 'Papéis vividos', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-about-roles-title"><?php esc_html_e( 'O mesmo problema visto de lugares diferentes.', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<ol class="rafael-about__role-list">
				<li><span>01</span><h3><?php esc_html_e( 'Fundador', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Transformar uma ideia em produto, negócio e equipe.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>02</span><h3><?php esc_html_e( 'Construtor', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Criar tecnologia, processos e capacidade para crescer.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>03</span><h3><?php esc_html_e( 'Operador', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Conectar estratégia, números, pessoas e execução.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>04</span><h3><?php esc_html_e( 'Integrador', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Alinhar negócios, áreas e lideranças depois de uma fusão.', 'executive-signal-wordpress-theme' ); ?></p></li>
				<li><span>05</span><h3><?php esc_html_e( 'Comunicador', 'executive-signal-wordpress-theme' ); ?></h3><p><?php esc_html_e( 'Traduzir visão e estratégia em decisões compreensíveis.', 'executive-signal-wordpress-theme' ); ?></p></li>
			</ol>
		</div>
	</section>

	<section class="rafael-about__present" aria-labelledby="rafael-about-present-title">
		<div class="rafael-about__layout rafael-about__present-layout">
			<div class="rafael-about__present-heading">
				<p class="rafael-about__eyebrow"><?php esc_html_e( 'O fio que conecta', 'executive-signal-wordpress-theme' ); ?></p>
				<h2 id="rafael-about-present-title"><?php esc_html_e( 'Como transformar ambição em uma organização capaz de executar?', 'executive-signal-wordpress-theme' ); ?></h2>
			</div>
			<div class="rafael-about__present-copy">
				<p><?php esc_html_e( 'Rafael já ocupou o lugar do fundador que precisa fazer de tudo, do executivo que organiza várias áreas, do líder que responde ao conselho, do responsável por integrar operações e do comunicador que precisa tornar uma estratégia clara para o mercado.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'Essa combinação oferece algo que não nasce apenas do estudo de modelos de gestão: julgamento executivo formado por decisões reais, com restrições, consequências e pessoas envolvidas.', 'executive-signal-wordpress-theme' ); ?></p>
				<p><?php esc_html_e( 'Hoje, ele também dedica essa experiência a ajudar fundadores e CEOs de empresas que já validaram seu produto e cresceram, mas perceberam que a execução passou a depender demais deles.', 'executive-signal-wordpress-theme' ); ?></p>
				<div class="rafael-about__actions">
					<a class="es-button" data-variant="primary" data-size="lg" href="<?php echo esc_url( $coo_url ); ?>"><?php esc_html_e( 'Conheça o COO as a Service', 'executive-signal-wordpress-theme' ); ?></a>
					<a class="rafael-about__text-link" href="<?php echo esc_url( $speaking_url ); ?>"><?php esc_html_e( 'Conheça minhas palestras', 'executive-signal-wordpress-theme' ); ?><span aria-hidden="true"> →</span></a>
				</div>
			</div>
		</div>
	</section>

	<section class="rafael-about__closing" aria-labelledby="rafael-about-closing-title">
		<div class="rafael-about__layout">
			<p class="rafael-about__eyebrow"><?php esc_html_e( 'Uma ideia para levar', 'executive-signal-wordpress-theme' ); ?></p>
			<blockquote class="rafael-about__closing-quote">
				<p id="rafael-about-closing-title"><?php esc_html_e( 'Construa coisas que importam. A vida é curta demais para ser desperdiçada.', 'executive-signal-wordpress-theme' ); ?></p>
			</blockquote>
			<div class="rafael-about__channels" aria-label="<?php esc_attr_e( 'Canais de Rafael Carvalho', 'executive-signal-wordpress-theme' ); ?>">
				<a href="https://www.linkedin.com/in/rafaelmcarvalho/" target="_blank" rel="noopener noreferrer">LinkedIn<span aria-hidden="true"> ↗</span></a>
				<a href="https://www.instagram.com/eu.rafaelcarvalho/" target="_blank" rel="noopener noreferrer">Instagram<span aria-hidden="true"> ↗</span></a>
				<a href="https://www.youtube.com/@RafaelCarvalhoMCC" target="_blank" rel="noopener noreferrer">YouTube<span aria-hidden="true"> ↗</span></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
