<?php

	$custom_template = 'single/single-editie-calendar.twig';
	
	$evenimente = $context['site']->get_evenimente_for_editie_calendar($editie);

	$zile = [];
	foreach ($evenimente as $eveniment) {
		$calendar = $eveniment->get_field('calendar');
		foreach ($calendar as $instance) {
			if ($instance['data_inceput']) {
				$data_inceput = new DateTime($instance['data_inceput']);
				if ($instance['data_sfarsit']) {
					$data_sfarsit = new DateTime($instance['data_sfarsit']);
				} else {
					$data_sfarsit = $data_inceput;
				}
				$data_sfarsit = $data_sfarsit->setTime(0,0,1);
				$period = new DatePeriod(
					$data_inceput, 
					new DateInterval('P1D'), 
					$data_sfarsit
				);

				$ora_inceput = new DateTime($instance['ora_inceput']);
				if ($instance['ora_sfarsit']) {
					$ora_sfarsit = new DateTime($instance['ora_sfarsit']);
				} else {
					$ora_sfarsit = null;
				}
				$biletmaster = $instance['biletmaster'];
				$loc = $instance['loc'];

				foreach ($period as $zi) {
					$zi_key = $zi->format('Y-m-d');
					$zi_value = array(
						'eveniment' => $eveniment
					);
					if ($ora_inceput) {
						$zi_value['ora_inceput'] = $ora_inceput;
					}

					if ($ora_sfarsit) {
						$zi_value['ora_sfarsit'] = $ora_sfarsit;
					}

					if ($biletmaster) {
						$zi_value['biletmaster'] = $biletmaster;
					}

					if ($loc) {
						$zi_value['loc'] = $loc;
					}

					$zile[$zi_key][] = $zi_value;
				}
			}
		}
	}

	ksort($zile);

	function sort_evenimente_by_ora_inceput($a, $b) {
		return $a['ora_inceput'] > $b['ora_inceput'] ? 1 : 
			($a['ora_inceput'] < $b['ora_inceput'] ? -1 : 0);
	}

	foreach ($zile as $key => $zi) {
		uasort($zi, 'sort_evenimente_by_ora_inceput');
		$zile[$key] = $zi;
	}

	$context['zile'] = $zile;

?>