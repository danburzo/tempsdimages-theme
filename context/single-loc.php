<?php
	$context['evenimente'] = Timber::get_posts(
		array(
			'post_type' => 'eveniment',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'loc',
					'value' => $post->ID
				)
			),
			'orderby' => 'title',
			'order' => 'ASC'
		)
	);
?>