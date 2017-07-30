<?php
/**
 * The Template for displaying all single posts
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;


// Include PHP code based on the post type 
// (for additional queries, etc.)

$post_type_include = get_template_directory() . '/include/single-' . $post->post_type . '.php';
if (file_exists($post_type_include)) {
	include($post_type_include);
}

$templates = array('single/single-' . $post->ID . '.twig');

if ($post->post_parent) {
	array_push(
		$templates,
		'single/single-' . $post->post_type . '-subpage.twig'
	);
}

array_push(
	$templates, 
	'single/single-' . $post->post_type . '.twig',
	'single/single.twig'
);

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single/single-password.twig', $context );
} else {
	Timber::render( $templates, $context );
}
