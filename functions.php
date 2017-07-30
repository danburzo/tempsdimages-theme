<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});
	
	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});
	
	return;
}

Timber::$dirname = array('templates', 'views');

class TDISite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_nav_menus' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'pre_get_posts', array( $this, 'configure_get_posts' ) );
		parent::__construct();
	}

	function register_post_types() {
		$this->register_cpt_loc();
		$this->register_cpt_artist();
		$this->register_cpt_eveniment();
		$this->register_cpt_editie();
		$this->register_cpt_galerie();
	}

	function register_taxonomies() {
	}

	function register_nav_menus() {
		register_nav_menu('main-menu',__( 'Main Menu' ));
		register_nav_menu('footer-menu',__( 'Footer Menu' ));
	}

	function register_shortcodes() {

	}

	function add_to_context( $context ) {
		$context['menu'] = new TimberMenu('main-menu');
		$context['footer_menu'] = new TimberMenu('footer-menu');
		$context['pagination'] = Timber::get_pagination();
		
		$context['site'] = $this;
		$context['is_home'] = is_home() || is_front_page();

		// add the WPML languages
		if (function_exists('icl_get_languages')) {
			$context['languages'] = icl_get_languages('skip_missing=0&orderby=code');
		}
		return $context;
	}

	function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		return $twig;
	}

	/* Custom post types */
	function register_cpt_loc() {
		register_post_type('loc', array(
			'labels' => array(
				'name' => 'Locuri',
				'singular_name' => 'Loc'
			),
			'description' => 'Locuri pentru evenimente',
			'rewrite' => array(
				'slug' => 'places'
			),
			'supports' => array(
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'custom-fields', 
				'page-attributes'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => false
		));
	}

	function register_cpt_artist() {
		register_post_type('artist', array(
			'labels' => array(
				'name' => 'Artiști',
				'singular_name' => 'Artist'
			),
			'description' => 'Artiști participanți',
			'rewrite' => array(
				'slug' => 'artists'
			),
			'supports' => array(
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'custom-fields', 
				'page-attributes'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => false
		));
	}

	function register_cpt_eveniment() {
		register_post_type('eveniment', array(
			'labels' => array(
				'name' => 'Evenimente',
				'singular_name' => 'Eveniment'
			),
			'description' => 'Evenimente din program',
			'rewrite' => array(
				'slug' => 'events'
			),
			'supports' => array(
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'custom-fields', 
				'page-attributes'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => true
		));
	}

	function register_cpt_editie() {
		register_post_type('editie', array(
			'labels' => array(
				'name' => 'Ediții',
				'singular_name' => 'Ediție'
			),
			'description' => 'Edițiile TDI',
			'rewrite' => array(
				'slug' => 'editions'
			),
			'supports' => array(
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'custom-fields', 
				'page-attributes'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => true
		));
	}

	function register_cpt_galerie() {
		register_post_type('galerie', array(
			'labels' => array(
				'name' => 'Galerii',
				'singular_name' => 'Galerie'
			),
			'description' => 'Galerii TDI',
			'rewrite' => array(
				'slug' => 'galleries'
			),
			'supports' => array(
				'title', 
				'editor', 
				'thumbnail', 
				'excerpt', 
				'custom-fields', 
				'page-attributes'
			),
			'public' => true,
			'has_archive' => true,
			'hierarchical' => true
		));
	}

	function object_id_in_current_language($id, $type = 'page') {
		if (function_exists('icl_object_id')) {
			return icl_object_id($id, $type, true);
		} else {
			return $id;
		}
	}

	function configure_get_posts($query) {

		// Don't alter queries in the admin interface
		// and don't alter any query that's not the main one
		if (is_admin() || !$query->is_main_query()) {
			return;
		} 

		// For custom post type based archives
		if ($query->is_post_type_archive()) {
			// Only show top-level posts
			if ($query->query_vars['post_parent'] == false) {
				$query->set('post_parent', 0);
			}

			if ($query->query_vars['post_type'] == 'loc') {
				$query->set('posts_per_archive_page', -1);
			}
		}
	}

	function get_editie($post) {
		$ancestors = array_reverse(get_post_ancestors($post->ID));
		if (count($ancestors)) {
			$top_ancestor = get_post($ancestors[0]);
			if ($top_ancestor->post_type == 'editie') {
				return $top_ancestor;
			}
		}
	}

	function get_editie_menu($editie) {
		if ($editie) {
			return new TimberMenu(get_field('meniu', $editie->ID));
		} 
	}

	function get_evenimente_for_editie($editie) {
		return Timber::get_posts(
			array(
				'posts_per_page' => -1,
				'post_type' => 'eveniment',
				'meta_query' => array(
					array(
						'key' => 'editie',
						'value' => $editie->ID
					)
				)
			)
		);
	}

	function get_loc_from_eveniment($eveniment) {
		return get_field('loc', $eveniment->ID, false);
	}

	function get_timber_post_from_id($id) {
		return new TimberPost($id);
	}

	function get_locuri_for_editie($editie) {
		$locuri_ids =  array_unique(
			array_map(
				array($this, 'get_loc_from_eveniment'), 
				$this->get_evenimente_for_editie($editie)
			)
		);

		$locuri = array_map(
			array($this, 'get_timber_post_from_id'),
			$locuri_ids
		);
		
		usort($locuri, function($itemA, $itemB) {
			return strcmp($itemA->post_title, $itemB->post_title);
		});

		return $locuri;
	}
}

// class TDIEvent extends TimberPost {

// 	var $_calendar;
// 	var $_occurrences;
		
// 	function get_first_occurrence() {
// 		if (!$this->_calendar) {
// 			$this->calendar = $this->get_field('calendar');
// 		}

// 		if (!$this->_occurrences) {
// 			$this->_occurrences = $this->calendar;
// 			uasort($this->_occurrences, array($this, '_sort_occurrences_by_date_and_time'));
// 		}

// 		return $this->_occurrences[0];
// 	}

// 	function _sort_occurrences_by_date_and_time($a, $b) {
// 		$date_a = new DateTime($a['data_inceput']);
// 		$date_b = new DateTime($b['data_inceput']);
// 		$time_a = new DateTime($a['ora_inceput']);
// 		$time_b = new DateTime($b['ora_inceput']);

// 		if ($date_a > $date_b) {
// 			return 1;
// 		}

// 		if ($date_a < $date_b) {
// 			return -1;
// 		}

// 		if ($time_a > $time_b) {
// 			return 1;
// 		}

// 		if ($time_a < $time_b) {
// 			return -1;
// 		}

// 		return 0;
// 	}
// }

new TDISite();
