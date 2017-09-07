<?php
	$custom_template = 'single/single-editie-locuri.twig';
	$locuri = $context['site']->get_locuri_for_editie($editie);
	$context['locuri'] = $locuri;
?>