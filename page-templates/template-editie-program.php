<?php
/*
	Template Name: Program Ediție
	Template Post Type: page, post, editie
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$editie = $context['site']->get_editie($post);
$context['editie'] = $editie;

$evenimente = $context['site']->get_evenimente_for_editie($editie);

function sort_by_name($a, $b) {
	return strcasecmp($a->title, $b->title);
}
uasort($evenimente, 'sort_by_name');

$context['evenimente'] = $evenimente;

Timber::render(
	array(
		'single/single-' . $post->ID . '.twig', 
		'single/single-editie-program.twig'
	), 
	$context
);
?>