<?php
/*
	Template Name: Echipă Ediție
	Template Post Type: page, post, editie
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

Timber::render(
	array(
		'single/single-' . $post->ID . '.twig', 
		'single/single-editie-echipa.twig'
	), 
	$context
);

?>