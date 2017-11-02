<?php
	$custom_template = 'single/single-editie-program.twig';
	$context['evenimente'] = $context['site']->get_evenimente_for_editie_program($editie);
?>