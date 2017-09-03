<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context = Timber::get_context();

$context['title'] = __('Rezultate pentru: ', "TDI") . "<em>" . get_search_query() . "</em>";
$context['posts'] = Timber::get_posts();

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

Timber::render( $templates, $context );
