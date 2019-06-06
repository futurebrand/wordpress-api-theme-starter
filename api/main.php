<?php

function api_main() {
	$data = array();

  $menuLocations = get_nav_menu_locations();

  function filter_item_data($obj) {
    return array(
      'ID' => $obj->ID,
      'title' => $obj->title,
      'url' => $obj->url,
      'target' => $obj->target,
      'items' => isset($obj->items) ? $obj->items : array()
    );
  }

  foreach($menuLocations as $menuKey => $menuSlug) {
    $items = wp_get_nav_menu_items($menuSlug);

    $tempItems = array();
    $data[$menuKey] = array();

    foreach($items as $item) {
      $data[$menuKey][] = filter_item_data($item);
    }
  }

  return $data;
}

add_action( 'rest_api_init', function () {
  register_rest_route('/api', '/main', array(
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_main'
  ));
});
