<?php

	$current_editie_id = $context['site']->get_editie_curenta_in_current_language();

	$context['evenimente_curente'] = Timber::get_posts(
		array(
			'post_type' => 'eveniment',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'loc',
					'value' => $post->ID
				),
				array(
					'key' => 'editie',
					'value' => $current_editie_id
				)
			),
			'orderby' => 'title',
			'order' => 'ASC'
		)
	);

	$context['evenimente_trecute'] = Timber::get_posts(
		array(
			'post_type' => 'eveniment',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'loc',
					'value' => $post->ID
				),
				array(
					'key' => 'editie',
					'value' => $current_editie_id,
					'compare' => '!='
				)
			),
			'orderby' => 'title',
			'order' => 'ASC'
		)
	);
?>