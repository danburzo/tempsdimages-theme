<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

switch ($post->post_type) {
	case 'artist':
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
		break;
	case 'loc':
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
		break;
}

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single/single-password.twig', $context );
} else {
	Timber::render( array( 'single/single-' . $post->ID . '.twig', 'single/single-' . $post->post_type . '.twig', 'single/single.twig' ), $context );
}
