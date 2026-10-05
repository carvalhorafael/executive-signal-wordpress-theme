<?php
/**
 * Seed representative content for local performance audits.
 *
 * @package ExecutiveSignal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 1 );
}

require __DIR__ . '/setup-smoke-fixtures.php';

/**
 * Create or update a representative post for blog and performance checks.
 *
 * @param string $slug      Post slug.
 * @param string $title     Post title.
 * @param string $excerpt   Post excerpt.
 * @param string $content   Post content.
 * @param int    $author_id Post author ID.
 * @param string $post_date Local publication date.
 * @return int
 * @throws RuntimeException When WordPress cannot persist the post.
 */
function executive_signal_performance_upsert_post( $slug, $title, $excerpt, $content, $author_id, $post_date ) {
	$post    = get_page_by_path( $slug, OBJECT, 'post' );
	$post_id = wp_insert_post(
		array(
			'ID'           => $post instanceof WP_Post ? $post->ID : 0,
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $title,
			'post_excerpt' => $excerpt,
			'post_content' => $content,
			'post_author'  => $author_id,
			'post_date'    => $post_date,
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		throw new RuntimeException( esc_html( $post_id->get_error_message() ) );
	}

	return (int) $post_id;
}

$fixture_admin     = get_user_by( 'login', 'admin' );
$fixture_author_id = $fixture_admin instanceof WP_User ? (int) $fixture_admin->ID : 0;
$fixture_now       = current_datetime();

executive_signal_performance_upsert_post(
	'performance-audit-article',
	'Como transformar estratégia em ritmo de execução',
	'Uma fixture representativa para validar a listagem e a página individual do blog.',
	'<p>Estratégia só produz resultado quando se transforma em decisões claras, responsáveis definidos e um ritmo de acompanhamento que a organização consegue sustentar.</p>
<!-- wp:heading --><h2 class="wp-block-heading">Comece pelo gargalo real</h2><!-- /wp:heading -->
<p>Antes de adicionar novos processos, identifique onde as decisões ficam paradas, onde existe retrabalho e quais informações chegam tarde demais para orientar a operação.</p>
<!-- wp:quote --><blockquote class="wp-block-quote"><p>Uma boa rotina operacional reduz a distância entre perceber um problema e agir sobre ele.</p></blockquote><!-- /wp:quote -->
<!-- wp:heading --><h2 class="wp-block-heading">Transforme prioridade em cadência</h2><!-- /wp:heading -->
<!-- wp:list --><ul class="wp-block-list"><li>Defina o resultado esperado.</li><li>Escolha um responsável claro.</li><li>Registre os sinais que antecipam desvios.</li><li>Revise decisões e aprendizados com frequência.</li></ul><!-- /wp:list -->
<p>Esse ciclo cria visibilidade sem transformar gestão em uma sequência de reuniões e relatórios que não mudam nenhuma decisão.</p>',
	$fixture_author_id,
	$fixture_now->format( 'Y-m-d H:i:s' )
);

executive_signal_performance_upsert_post(
	'performance-audit-decisions',
	'Decisões que não dependem do fundador',
	'Como dar contexto e autonomia sem perder o controle da operação.',
	'<p>Uma operação madura transforma contexto em critérios claros para que as decisões avancem no nível certo.</p>',
	$fixture_author_id,
	$fixture_now->modify( '-1 minute' )->format( 'Y-m-d H:i:s' )
);

executive_signal_performance_upsert_post(
	'performance-audit-signals',
	'Sinais de que a operação perdeu o ritmo',
	'Os sintomas que aparecem antes de metas, entregas e decisões começarem a atrasar.',
	'<p>O atraso raramente começa na data final. Ele aparece antes na qualidade dos acordos, no fluxo de informação e na cadência de acompanhamento.</p>',
	$fixture_author_id,
	$fixture_now->modify( '-2 minutes' )->format( 'Y-m-d H:i:s' )
);

update_option( 'posts_per_page', 3 );

if ( $fixture_author_id > 0 ) {
	$fixture_posts = get_posts(
		array(
			'fields'         => 'ids',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'post_type'      => 'post',
		)
	);

	foreach ( $fixture_posts as $fixture_post_id ) {
		if ( (int) get_post_field( 'post_author', $fixture_post_id ) > 0 ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'          => $fixture_post_id,
				'post_author' => $fixture_author_id,
			)
		);
	}
}
