<?php
defined('ABSPATH') || exit;

add_action('rest_api_init', function () {
  register_rest_route('ds-meta-suche/v1', '/wayback', [
    'methods'  => 'GET',
    'callback' => 'ds_proxy_wayback_lookup',
    'permission_callback' => '__return_true',
  ]);
});

function ds_proxy_wayback_lookup($request) {
  $url = sanitize_text_field($request->get_param('url'));

  if (!$url) {
    return new WP_Error('no_url', 'URL fehlt.', ['status' => 400]);
  }

  $api = 'https://web.archive.org/cdx/search/cdx?url=' . rawurlencode($url) . '&output=json';

  $response = wp_remote_get($api);
  if (is_wp_error($response)) {
    return new WP_Error('fetch_error', 'Fehler beim Abrufen.', ['status' => 500]);
  }

  $body = wp_remote_retrieve_body($response);
  return json_decode($body, true);
}

function ds_meta_suche_rest_callback($request) {
    // Zähler erhöhen
    $count = (int) get_option( 'ds_meta_suche_counter', 0 );
    update_option( 'ds_meta_suche_counter', $count + 1 );
    $query = sanitize_text_field($request->get_param('q'));
    $page  = max(1, intval($request->get_param('page')));
    $limit = max(1, intval(get_option('ds_meta_suche_limit', 5)));

    $key = get_option('ds_meta_suche_apikey');
    if (!$key) {
        return new WP_Error('no_key', 'API-Key nicht gesetzt', ['status' => 403]);
    }

    $cache_key = 'ds_meta_cache_' . md5($query);
    $cached = get_transient($cache_key);
    if (!$cached) {
        $url = "https://metager.org/api/meta/meta.ger3?q=" . urlencode($query) . "&key=" . urlencode($key) . "&out=json";
        $response = wp_remote_get($url, ['timeout' => 10]);
        if (is_wp_error($response)) return new WP_Error('api_error', 'Fehler beim Abrufen', ['status' => 500]);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        $cached = array_map(function ($item) {
            return [
                'title' => $item['titel'] ?? '',
                'url'   => $item['link'] ?? '',
                'desc'  => $item['anzeige'] ?? ''
            ];
        }, $data['results'] ?? []);
        set_transient($cache_key, $cached, HOUR_IN_SECONDS);
    }

    $total = count($cached);
    $offset = ($page - 1) * $limit;
    $paged = array_slice($cached, $offset, $limit);

    return rest_ensure_response([
        'results' => $paged,
        'total' => $total,
        'page' => $page,
        'pages' => ceil($total / $limit),
    ]);
}
