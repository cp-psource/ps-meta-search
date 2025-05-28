<?php
defined('ABSPATH') || exit;

// Menüpunkt einfügen
add_action('admin_menu', function () {
    add_options_page(
        'Meta-Suche Einstellungen',
        'Meta-Suche',
        'manage_options',
        'ps-meta-suche-settings',
        'ps_meta_suche_settings_page'
    );
});

// Einstellungen registrieren
add_action('admin_init', function () {
    register_setting('ps_meta_suche', 'ps_meta_suche_apikey');
    register_setting('ps_meta_suche', 'ps_meta_suche_limit');

    add_settings_section('ps_meta_suche_section', '', null, 'ps_meta_suche');

    add_settings_field(
        'ps_meta_suche_apikey',
        'MetaGer API Key (optional)',
        function () {
            $val = esc_attr(get_option('ps_meta_suche_apikey'));
            echo "<input type='text' name='ps_meta_suche_apikey' value='{$val}' class='regular-text' />";
            echo "<p class='description'>API-Key von <a href='https://metager.org/meta/meta.ger3?api=1' target='_blank'>MetaGer</a> einfügen, um echte Suchergebnisse zu aktivieren.</p>";
        },
        'ps_meta_suche',
        'ps_meta_suche_section'
    );

    add_settings_field(
        'ps_meta_suche_limit',
        'Treffer pro Seite',
        function () {
            $val = intval(get_option('ps_meta_suche_limit', 5));
            echo "<input type='number' name='ps_meta_suche_limit' value='{$val}' min='1' max='50' />";
            echo "<p class='description'>Anzahl der Suchergebnisse pro Seite (nur für MetaGer mit API-Key).</p>";
        },
        'ps_meta_suche',
        'ps_meta_suche_section'
    );
});

// Admin-Seite rendern
function ps_meta_suche_settings_page() {
    // Gesamtzahl der Suchanfragen aus Optionen auslesen
    $gesamt = 0;
    foreach (wp_load_alloptions() as $key => $val) {
        if (strpos($key, 'ds_suche_count_') === 0) {
            $gesamt += (int) $val;
        }
    }
    $counter = $gesamt;

    ?>
    <div class="wrap">
        <h1>Meta-Suche Einstellungen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ps_meta_suche');
            do_settings_sections('ps_meta_suche');
            submit_button();
            ?>
        </form>

        <hr>
        <h2>📊 Statistik</h2>
        <p>🔍 Die Suche wurde bisher <strong><?php echo $counter; ?></strong> Mal verwendet.</p>
    </div>
    <?php
}
