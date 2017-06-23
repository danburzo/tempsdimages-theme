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
	}

	function register_taxonomies() {
		$this->register_tax_categorie_eveniment();
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

	/* Taxonomies */

	function register_tax_categorie_eveniment() {
		register_taxonomy('categorie_eveniment', 'eveniment', array(
			'labels' => array(
				'name' => 'Categorii eveniment',
				'singular_name' => 'Categorie eveniment'
			),
			'hierarchical' => true,
			'public' => true,
			'rewrite' => array(
				'slug' => 'event-categories'
			),
			'show_in_quick_edit' => true
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
		}
	}

}

new TDISite();
