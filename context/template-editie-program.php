<?php
	$custom_template = 'single/single-editie-program.twig';
	$evenimente = $context['site']->get_evenimente_for_editie($editie);
	function sort_by_name($a, $b) {
		return strcasecmp($a->title, $b->title);
	}
	uasort($evenimente, 'sort_by_name');
	$context['evenimente'] = $evenimente;
?>