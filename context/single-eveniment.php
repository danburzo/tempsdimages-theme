<?php

	$editie = $context['site']->get_editie($post);
	$context['editie'] = $editie;
	$context['editie_menu'] = $context['site']->get_editie_menu($editie);
	$context['editie_curenta'] = $context['site']->is_editie_curenta($editie);

?>