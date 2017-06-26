<?php
/*
	Template Name: Locuri Ediție
	Template Post Type: page, post, editie
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$editie = $context['site']->get_editie($post);
$context['editie'] = $editie;

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