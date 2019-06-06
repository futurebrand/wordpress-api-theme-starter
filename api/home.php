<?php

function api_home() {
  $data = array();
  $pageObj = get_page_by_path('home');

  // fields
  $hero = get_field('hero', $pageObj->ID);
  $seo = get_field('seo', $pageObj->ID);

  // return
  $data['hero'] = $hero;
  $data['seo'] = $seo;

	return $data;
}

add_action( 'rest_api_init', function () {
  register_rest_route('/api/pages', '/home', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_home'
  ));
});
