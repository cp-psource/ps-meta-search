<?php
defined('ABSPATH') || exit;

// Menüpunkt einfügen
add_action('admin_menu', function () {
    add_options_page(
        'Meta-Suche Einstellungen',
        'Meta-Suche',
        'manage_options',
        'ds-meta-suche-settings',
        'ds_meta_suche_settings_page'
    );
});

// Einstellungen registrieren
add_action('admin_init', function () {
    register_setting('ds_meta_suche', 'ds_meta_suche_apikey');
    register_setting('ds_meta_suche', 'ds_meta_suche_limit');

    add_settings_section('ds_meta_suche_section', '', null, 'ds_meta_suche');

    add_settings_field(
        'ds_meta_suche_apikey',
        'MetaGer API Key (optional)',
        function () {
            $val = esc_attr(get_option('ds_meta_suche_apikey'));
            echo "<input type='text' name='ds_meta_suche_apikey' value='{$val}' class='regular-text' />";
            echo "<p class='description'>API-Key von <a href='https://metager.org/meta/meta.ger3?api=1' target='_blank'>MetaGer</a> einfügen, um echte Suchergebnisse zu aktivieren.</p>";
        },
        'ds_meta_suche',
        'ds_meta_suche_section'
    );

    add_settings_field(
        'ds_meta_suche_limit',
        'Treffer pro Seite',
        function () {
            $val = intval(get_option('ds_meta_suche_limit', 5));
            echo "<input type='number' name='ds_meta_suche_limit' value='{$val}' min='1' max='50' />";
            echo "<p class='description'>Anzahl der Suchergebnisse pro Seite (nur für MetaGer mit API-Key).</p>";
        },
        'ds_meta_suche',
        'ds_meta_suche_section'
    );
});

// Admin-Seite rendern
function ds_meta_suche_settings_page() {
    ?>
    <div class="wrap">
        <h1>Meta-Suche Einstellungen</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ds_meta_suche');
            do_settings_sections('ds_meta_suche');
            submit_button();
            ?>
        </form>

        <hr>
        <h2>📊 Statistik</h2>
        <p>🔍 Die Suche wurde bisher <strong><?php echo $counter; ?></strong> Mal verwendet.</p>
    </div>
    <?php
}
