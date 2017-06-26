<?php
$context['evenimente'] = Timber::get_posts(
	array(
		'post_type' => 'eveniment',
		'meta_query' => array(
			array(
				'key' => 'loc',
				'value' => $post->ID
			)
		)
	)
);
?>