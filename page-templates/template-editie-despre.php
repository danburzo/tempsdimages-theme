<?php
/*
	Template Name: Despre Ediție
	Template Post Type: page, post, editie
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$editie = $context['site']->get_editie($post);
$context['editie'] = $editie;

$context['editie_menu'] = $context['site']->get_editie_menu($editie);

Timber::render(
	array(
		'single/single-' . $post->ID . '.twig', 
		'single/single-editie-despre.twig'
	), 
	$context
);
?>