<?php
/**
 * Plugin Name: Datenschutzfreundliche Meta-Suche
 * Description: Shortcode mit Suchfeld, Auswahl von anonymen Suchmaschinen, Ergebnisvorschau und direkter Weiterleitung.
 * Version: 1.0.0
 * Author: Dernerd
 */

defined('ABSPATH') || exit;

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'inc/settings.php';
}
// REST-Endpunkt laden
require_once plugin_dir_path(__FILE__) . 'inc/rest-endpoint.php';
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('ds-meta-suche', plugin_dir_url(__FILE__) . 'assets/meta-suche.css');
});

function ds_meta_suche_shortcode() {
    $apikey = get_option('ds_meta_suche_apikey');
    $suchdienste = [
        'duckduckgo' => 'DuckDuckGo',
        'wayback'    => 'Wayback Machine',
    ];

    if ($apikey) {
        $suchdienste['metager'] = 'MetaGer (mit API)';
    }

    ob_start();
    ?>
    <form id="ds-suche-form">
        <select id="ds-suchmaschine" name="ds_suchmaschine">
            <?php foreach ($suchdienste as $key => $label): ?>
                <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <input id="ds-suchbegriff" name="ds_suchbegriff" type="search" placeholder="Suchbegriff..." required>
        <button type="submit">Suchen</button>
    </form>
    <div id="ds-trefferanzahl" class="ds-hint"></div>
    <div id="ds-suchergebnisse"></div>
    <div id="ds-externer-link"></div>
    <?php
    return ob_get_clean();
}
add_shortcode('ds_meta_suche', 'ds_meta_suche_shortcode');

add_action('wp_enqueue_scripts', 'ds_meta_suche_scripts');

function ds_meta_suche_scripts() {
    wp_enqueue_script('ds-meta-suche-js', plugin_dir_url(__FILE__) . 'assets/meta-suche.js', [], '1.0', true);
    wp_enqueue_style('ds-meta-suche-css', plugin_dir_url(__FILE__) . 'assets/meta-suche.css');
}
