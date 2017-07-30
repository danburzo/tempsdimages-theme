<?php
/*
	Template Name: Despre Ediție
	Template Post Type: page, post, editie
*/

include(get_template_directory() . '/include/editie-subpage.php');

Timber::render(
	array(
		'single/single-' . $post->ID . '.twig', 
		'single/single-editie-despre.twig'
	), 
	$context
);
?>