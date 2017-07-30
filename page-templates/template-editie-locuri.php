<?php
/*
	Template Name: Locuri Ediție
	Template Post Type: page, post, editie
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

include(get_template_directory() . '/include/single-editie.php');

$locuri = $context['site']->get_locuri_for_editie($editie);
$context['locuri'] = $locuri;

Timber::render(
	array(
		'single/single-' . $post->ID . '.twig', 
		'single/single-editie-locuri.twig'
	), 
	$context
);
?>