<?php
/**
 * Plugin Name: Datenschutzfreundliche Meta-Suche
 * Description: Shortcode mit Suchfeld, Auswahl von anonymen Suchmaschinen, Ergebnisvorschau und direkter Weiterleitung.
 * Version: 1.0.0
 * Author: Dernerd
 */

/**
 * @@@@@@@@@@@@@@@@@ PS UPDATER 1.3 @@@@@@@@@@@
 **/
require 'psource/psource-plugin-update/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;
 
$myUpdateChecker = PucFactory::buildUpdateChecker(
	'https://github.com/cp-psource/ps-meta-suche',
	__FILE__,
	'ps-meta-suche'
);
 
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

/**
 * @@@@@@@@@@@@@@@@@ ENDE PS UPDATER 1.3 @@@@@@@@@@@
 **/

defined('ABSPATH') || exit;

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'inc/settings.php';
}
// REST-Endpunkt laden
require_once plugin_dir_path(__FILE__) . 'inc/rest-endpoint.php';
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('ps-meta-suche', plugin_dir_url(__FILE__) . 'assets/meta-suche.css');
});

function ps_meta_suche_shortcode() {
    $apikey = get_option('ps_meta_suche_apikey');
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
add_shortcode('ps_meta_suche', 'ps_meta_suche_shortcode');

add_action('wp_enqueue_scripts', 'ps_meta_suche_scripts');

function ps_meta_suche_scripts() {
    wp_enqueue_script('ps-meta-suche-js', plugin_dir_url(__FILE__) . 'assets/meta-suche.js', [], '1.0', true);
    wp_enqueue_style('ps-meta-suche-css', plugin_dir_url(__FILE__) . 'assets/meta-suche.css');
}
