<?php

$siteUrl = get_bloginfo('url');

$locale = get_locale();
$lang = explode('_', $locale)[0];


define('LANG', $lang);
define('BASE_URL', $siteUrl);


// Enable thumbnails
add_theme_support( 'post-thumbnails' );


// Remove jQuery default
wp_deregister_script('jquery');
wp_register_script('jquery', (''));


// Add custom crops
$cropSizes = array(
	array('_crop_small', 600, 300),
	array('_crop_medium', 600, 300),
	array('_crop_large', 600, 300)
);


// Add custom crop
if (function_exists('add_image_size') && isset($cropSizes) && count($cropSizes)) {
	foreach ($crop as $cropSizes) {
		add_image_size($crop[0], $crop[1], $crop[2], true);
	}
}


// Remove tag <p> from images
function filter_ptags_on_images($content) {
	$content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	$content = preg_replace('/<p>\s*(<object.*>*.<\/object>)\s*<\/p>/iU', '\1', $content);
	return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
}
add_filter('the_content', 'filter_ptags_on_images');


// thumbnail post
function get_image($postId = null, $thumbId = false, $specific = array()) {
	$results = null;
	$specificTotal = count($specific);
	$thumbId = $thumbId ? $thumbId : get_post_thumbnail_id($postId);

	if(isset($thumbId) && !empty($thumbId)) {
		$results = array(
			'ID' => $thumbId
		);

		$defaultSize = wp_get_attachment_image_src($thumbId, 'full');
		if(isset($defaultSize) && !empty($defaultSize)) {
			$results['src'] = $defaultSize[0];
			$results['width'] = $defaultSize[1];
			$results['height'] = $defaultSize[2];
		}

		if($specificTotal) {
			for ($i = 0; $i < $specificTotal; $i++) {
				$size = $specific[$i];
				$result = wp_get_attachment_image_src($thumbId, $size);
				$data = array(
					'id' => $thumbId,
					'src' => $result[0],
					'width' => $result[1],
					'height' => $result[2]
				);
				$results[$size] = $data;
			}
		}
	}

	return $results;
}


// Register menu
function register_my_menu() {
	register_nav_menu('header-nav', __('Header Nav'));
}
add_action('init', 'register_my_menu');


// Remove link from sidebar menu
function remove_menus(){
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'remove_menus' );


// ACF Page Options
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'General Content Settings',
		'menu_title' 	=> 'General Content Settings',
		'post_id' => 'general-content',
		'redirect' 		=> false
	));
}

// Register new post type/taxonomy
function register_new_post_type($type, $name, $singularName, $dashicon, $supports = array('title', 'editor', 'thumbnail')) {
	$labels = array('name' => __($name), 'singular_name' => __($singularName));
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'menu_icon' => $dashicon,
		'supports' => $supports
	);
	register_post_type($type, $args);
}


function register_new_taxonomy($taxonomy, $type, $name) {
	register_taxonomy(
		$taxonomy,
		$type,
		array(
			'label' => __($name),
			'rewrite' => array( 'slug' => $taxonomy),
			'hierarchical' => true,
		)
	);
}

register_new_post_type('projeto', 'Projetos', 'Projeto', 'dashicons-grid-view');
register_new_taxonomy('categoria-projeto', 'projeto', 'Categorias');




// Custom logo
function my_login_logo() {
	$logo = get_stylesheet_directory_uri() . '/company-logo.svg';

  echo '<style type="text/css">
    #login h1 a, .login h1 a {
      background-image: url('. $logo .');
      height:100px;
      width:240px;
      background-size: 100%;
      background-repeat: no-repeat;
      margin-bottom: 15px;
    }
    #loginform h3 {
      display: none;
    }
    #loginform .button-primary,
    #lostpasswordform .button-primary {
      background: #555 !important;
      border-color: #555 !important;
      text-shadow: none !important;
      box-shadow: none !important;
    }
    .login #login_error,
    .login .message,
    .login .success {
      border-color: #555 !important;
    }
  </style>';
}

add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
  return 'COMPANY_NAME';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );


// Rest API
include 'api/main.php';
include 'api/home.php';