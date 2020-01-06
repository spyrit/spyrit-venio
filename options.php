<?php
include_once plugin_dir_path(__FILE__) . 'api/Synchro.php';
if (isset($_GET['page']) && $_GET['page'] == 'spyrit-venio/options.php' && isset($_POST['api'])) {
    $synchro = new Synchro;
    $synchro->synchronize();
}

add_action('admin_menu', 'venio_create_menu');


function venio_create_menu()
{
    add_menu_page(
        "Réglages de l'extension VENIO",
        'VENIO',
        'administrator',
        __FILE__,
        'venio_settings_page',
        plugins_url('/img/venio-icon.ico', __FILE__)
    );
    add_action('admin_init', 'venio_plugin_settings');
}


function venio_plugin_settings()
{
    register_setting('venio-settings-group', 'venio-institutions');
}

function venio_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Réglages de l'extension VENIO</h1>

        <form method="post" action="options.php">
            <?php settings_fields('venio-settings-group'); ?>
            <?php do_settings_sections('venio-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="venio-institutions">Institution(s)</label></th>
                    <td><input type="text" name="venio-institutions" id="venio-institutions" value="<?php echo esc_attr(get_option('venio-institutions')); ?>" /></td>
                </tr>
            </table>
            <p>Insérer dans le champ le ci-dessus le <strong>slug de vos institutions</strong>, séparées par une virgule, exemple :</p>
            <pre>institution1, institution2, institution3</pre>
            <?php submit_button('Enregistrer'); ?>

        </form>
        <hr>
        <h2>Récupération des événements</h2>
        <p>Les événements sont récupérés et mis à jour automatiquement tous les jours, <br>cependant, vous avez la possibilité de les récupérer immédiatement en cliquant sur le bouton ci-dessous&nbsp;:</p>
        <form method="POST" name="api">
            <button name="api" type="submit" class="button button-secondary">
                <span class="dashicons dashicons-update" style="padding: 4px 0 0 0;"></span>&nbsp;
                Récupérer les événements
            </button>
        </form>
        <?php if (get_option('venio-last-synchro') && get_option('venio-last-synchro') instanceof DateTime) { ?>
        <p>
            <small>Dernière récupération&nbsp;: <?php echo get_option('venio-last-synchro')->format('d/m/Y à H:i:s') ?></small>
        </p>
        <?php } ?>
        <hr>
        <h2>Shortcodes à intégrer sur votre site</h2>
        <p>Vous avez la possibilité d'intégrer les shorcodes suivants sur vos pages et articles&nbsp;:</p>
        <div style="background: #eaeaea;padding: 10px;margin-bottom: 10px;">
            <p><strong style="font-size: 16px;">[venio-events]</strong></p>
            <p>
                Affiche un module de recherche qui liste vos événements en fonction des critères recherchés.<br />
                Options disponibles&nbsp;:
            </p>
            <ul>
                <li>
                    <strong>institution</strong> (non requis)&nbsp;: slug de l'institution des événements.<br>
                    <em>Exemple d'intégration&nbsp;:</em> <strong>[venio-events institution=mon-institution]</strong>
                </li>
            </ul>
        </div>
        <div style="background: #eaeaea;padding: 10px;margin-bottom: 10px;">
            <p><strong style="font-size: 16px;">[venio-related-events]</strong></p>
            <p>
                Affiche les trois prochains événements sous forme de liste.<br />
                Options disponibles&nbsp;:
            </p>
            <ul>
                <li>
                    <strong>exclude</strong> (non requis)&nbsp;: Exclure un événement de la liste en renseignant son ID Wordpress.<br>
                    <em>Exemple d'intégration&nbsp;:</em> <strong>[venio-related-events exclude=42]</strong>
                </li>
            </ul>
        </div>
        <div style="background: #eaeaea;padding: 10px;">
            <p><strong style="font-size: 16px;">[venio-calendar]</strong></p>
            <p>
                Affiche un calendrier regroupant vos événements.
            </p>
        </div>
    </div>
<?php
} ?>