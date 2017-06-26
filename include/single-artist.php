<?php
$context['evenimente'] = Timber::get_posts(
	array(
		'post_type' => 'eveniment',
		'meta_query' => array(
			array(
				'key' => 'artists',
				'value' => '"' . $post->ID . '"', 
				'compare' => 'LIKE'
			)
		)
	)
);
?>