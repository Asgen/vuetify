<?php

/**
 * Set up theme options on activation
 */

function vue_wordpress_setup()
{

    add_theme_support( 'title-tag' );

    add_theme_support( 'post-thumbnails' );

    add_theme_support( 'custom-logo', array(
        'height' => 160,
        'width' => 160,
    ) );

    register_nav_menus( array(
        'main' => 'Main Menu',
    ) );

}

add_action( 'after_setup_theme', 'vue_wordpress_setup' );

/**
 * Enable features from Soil when plugin is activated
 * @link https://roots.io/plugins/soil/
 */
add_theme_support('soil-clean-up');
#add_theme_support('soil-disable-rest-api');
add_theme_support('soil-disable-asset-versioning');
add_theme_support('soil-disable-trackbacks');
#add_theme_support('soil-google-analytics', 'UA-XXXXX-Y');
add_theme_support('soil-js-to-footer');
add_theme_support('soil-nav-walker');
add_theme_support('soil-nice-search');
add_theme_support('soil-relative-urls');

/**
 * Load scripts and styles
 */

function vue_wordpress_scripts()
{
    // Styles
    wp_enqueue_style( 'style.css', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('vue_wordpress.css', get_template_directory_uri() . '/dist/vue-wordpress.css');

    // Scripts

    // Enable For Production - Disable for Development
    //wp_enqueue_script('vue_wordpress.js', get_template_directory_uri() . '/dist/vue-wordpress.js', array(), null, true);

    // Enable For Development - Remove for Production
     wp_enqueue_script( 'vue_wordpress.js', 'http://localhost:8080/vue-wordpress.js', array(), false, true );
}

add_action( 'wp_enqueue_scripts', 'vue_wordpress_scripts' );

/**
 * Declare REST API Data Localizer dependency
 */

if ( !class_exists( 'RADL' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="error"><p>REST API Data Localizer not activated. To use this theme go to <a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">plugins</a> to download and/or activate REST API Data Localizer.</p></div>';
    } );
    return;
}

/**
 * Initialize REST API Data Localizer
 */

new RADL( '__VUE_WORDPRESS__', 'vue_wordpress.js', array(
    'routing' => RADL::callback( 'vue_wordpress_routing' ),
    'state' => array(
        'categories' => RADL::endpoint( 'categories'),
        'media' => RADL::endpoint( 'media' ),
        'menus' => RADL::callback( 'vue_wordpress_menus' ),
        'pages' => RADL::endpoint( 'pages' ),
        'posts' => RADL::endpoint( 'posts' ),
        'tags' => RADL::endpoint( 'tags' ),
        'users' => RADL::endpoint( 'users' ),
        'site' => RADL::callback( 'vue_wordpress_site' ),
    ),
) );

/**
 * REST API Data Localizer callbacks
 */

function vue_wordpress_routing()
{
    $routing = array(
        'category_base' => get_option( 'category_base' ),
        'page_on_front' => null,
        'page_for_posts' => null,
        'permalink_structure' => get_option( 'permalink_structure' ),
        'show_on_front' => get_option( 'show_on_front' ),
        'tag_base' => get_option( 'tag_base' ),
        'url' => get_bloginfo( 'url' )
    );

    if ( $routing['show_on_front'] === 'page' ) {
        $front_page_id = get_option( 'page_on_front' );
        $posts_page_id = get_option( 'page_for_posts' );

        if ( $front_page_id ) {
            $front_page = get_post( $front_page_id );
            $routing['page_on_front'] = $front_page->post_name;
        }

        if ( $posts_page_id ) {
            $posts_page = get_post( $posts_page_id );
            $routing['page_for_posts'] = $posts_page->post_name;
        }

    }

    return $routing;
}

function vue_wordpress_menus()
{
    $menus = array();
    // $locations is an array where ([NAME] = MENU_ID);
    $locations = get_nav_menu_locations();

    foreach ( array_keys( $locations ) as $name ) {
        $id = $locations[$name];
        $menu = array();
        $menu_items = wp_get_nav_menu_items( $id );

        foreach ( $menu_items as $i ) {

            array_push( $menu, array(
                'id'      => $i->ID,
                'parent'  => $i->menu_item_parent,
                'target'  => $i->target,
                'content' => $i->title,
                'title'   => $i->attr_title,
                'url'     => $i->url,
            ) );

        }

        $menus[$name] = $menu;
    }

    return $menus;
}

function vue_wordpress_site()
{
    return array(
        'description' => get_bloginfo( 'description' ),
        'docTitle' => '',
        'loading' => false,
        'logo' => get_theme_mod( 'custom_logo' ),
        'name' => get_bloginfo( 'name' ),
        'posts_per_page' => get_option( 'posts_per_page' ),
        'url' => get_bloginfo( 'url' )
    );

}

/**
 * In template functions
 */

function vue_wordpress_min_read( $content )
{
    $length = count( explode( ' ', $content ) ) + 1;
    $time = $length / 200;

    if ( is_float( $time ) ) {
        $time = ceil( $time );
    }
   
    return $time . 'min read';
}

/* ACF workaround */

// Adds options page in dashboard
if( function_exists('acf_add_options_page') ) {	
	acf_add_options_page();	
}

// Register ACF fields
function create_ACF_meta_in_REST() {
	$postypes_to_exclude = ['acf-field-group','acf-field'];
	$extra_postypes_to_include = ["page"];
	$post_types = array_diff(get_post_types(["_builtin" => false], 'names'),$postypes_to_exclude);
	array_push($post_types, $extra_postypes_to_include);
	foreach ($post_types as $post_type) {
		register_rest_field( $post_type, 'ACF', [
		'get_callback' => 'expose_ACF_fields',
		'schema' => null,
		]
	  );
    }
    register_options_fields();

}
function expose_ACF_fields( $object ) {
	$ID = $object['id'];
	return get_fields($ID);
}

// Register options page fields
function register_options_fields(){

	register_rest_field( 'page', 'ACF_options', array(
		'get_callback'    => function( $post, $field_name, $request ){
			return get_fields('options');;
		},
		'update_callback' => null,
		'schema'          => null
	) );

}

add_action( 'rest_api_init', 'create_ACF_meta_in_REST' );
