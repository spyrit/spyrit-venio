<?php
class SettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'venio_page']);
        add_action('admin_init', [$this, 'venio_page_init']);
        add_action('admin_print_styles', [$this, 'venio_load_plugin_css']);
    }

    public function venio_load_plugin_css()
    {
        wp_enqueue_style('venio-admin-style', plugin_dir_url(__FILE__) . '../assets/venio-admin.css');
    }

    /**
     * Add options page
     */
    public function venio_page()
    {
        add_menu_page(
            'Venio',
            'Venio',
            'manage_options',
            'venio-options',
            [$this, 'venio_admin_page'],
            plugins_url('../assets/img/venio-icon.ico', __FILE__)
        );
    }

    /**
     * Options page callback
     */
    public function venio_admin_page()
    {
        $api = new \VENIO\Api();
        $helper = new \VENIO\Helper();
        if (isset($_GET['page']) && $_GET['page'] === 'venio-options' && isset($_POST['sync'])) {
            $api->getEvents(null, null, true);
        }
        $this->options = get_option('venio_config'); ?>
        <div class="wrap">
            <h1>
                <img src="<?php echo esc_html(plugin_dir_url(__FILE__)) ?>../assets/img/logo-venio.svg" alt="<?php _e( "Venio's logo", 'venio' ); ?>" />
                Venio
            </h1>
            <div class="notices-wrap"><?php do_action('admin_notices') ?></div>
            <div class="container">
                <div class="col">
                    <h2><?php _e( 'Settings', 'venio' ); ?></h2>
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('venio-group');
                        do_settings_sections('venio-options');
                        submit_button();
                        ?>
                    </form>
                    <form method="POST">
                        <h2><?php _e( 'Events retrieval', 'venio' ); ?></h2>
                        <div class="api-call">
                            <?php submit_button(__( 'Force update', 'venio' ), 'large', 'sync', true, '') ?>
                            <?php if (get_option('venio_api_last_call')): ?>
                                <p style="margin-top: 10px;"><?php _e( 'Last update', 'venio' ); ?>&nbsp;: <strong><?php echo esc_html(get_option('venio_api_last_call')) ?></strong></p>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php $events = $api->getEvents(null, null, false); ?>
                    <?php if ($events): ?>
                        <div class="using">
                            <h2><?php _e( 'Your events', 'venio' ); ?></h2>
                            <h3><?php _e( 'Here is your event listing synced to your WordPress site:', 'venio' ); ?></h3>
                            <ul class="scrollable-list">
                                <?php foreach ($events as $event): ?>
                                    <li>
                                        <a href="<?php echo esc_url($helper->getEventUrl($event)) ?>" title="<?php _e( "Access the event page", 'venio' ); ?> <?php echo esc_html($event['name']) ?>" target="_blank">
                                            <?php echo esc_html($event['name']) ?> (<?php echo esc_html($helper->getFormattedDate($event)) ?>)
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <h2><?php _e( 'How to use?', 'venio' ); ?></h2>
                    <div class="using">
                        <h2><?php _e( 'How to use Venio plugin on your website?', 'venio' ); ?></h2>
                        <h3><?php _e( 'Insert the shortcode in your pages or posts', 'venio' ); ?></h3>
                        <p class="shortcode">[venio-events]</p>
                        <p>
                            <?php _e('Display a search module listing your events depending on search criteria.', 'venio'); ?><br />
                            <?php _e('Available options', 'venio'); ?>&nbsp;:
                        </p>
                        <ul>
                            <li>
                                <strong>institution</strong> (<?php _e( 'not required', 'venio' ); ?>)&nbsp;: <?php _e( 'institution slug', 'venio' ); ?>.<br>
                                <em><?php _e( 'Example', 'venio' ); ?>&nbsp;:</em> <strong>[venio-events institution=mon-institution]</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="using">
                        <h2><?php _e( 'How does it work?', 'venio' ); ?></h2>
                        <h3><?php _e( 'Single event', 'venio' ); ?></h3>
                        <p>
                            <?php _e( 'An URL is aviable for each of your events under the following pattern', 'venio' ); ?>&nbsp;:<br>
                            <?php _e( 'https://yourwebsite.com/', 'venio' ); ?><strong>evenement</strong>/<strong>slug</strong>
                        </p>
                        <p><?php _e( 'Consulting events is generating the url', 'venio' ); ?>.</p>
                        <h3><?php _e( 'Caching system', 'venio' ); ?></h3>
                        <p><?php _e( 'Caching system is regulating the connection with Venio API calls', 'venio' ); ?>.</p>
                        <p><?php _e( 'Caching lifetime is set to 1 hour (3600 seconds), every hours, your website make a new API call to Venio to get and display all your public events', 'venio' ); ?>.</p>
                        <p><?php _e( 'Anytime, you can force the events retrieval clicking on the "Force update" button', 'venio' ); ?>.</p>

                    </div>
                </div>
            </div>
            <div class="copy">
                Venio version <?php echo esc_html(VENIO_VERSION) ?><br>
                <?php _e( 'Designed by', 'venio' ); ?> <a href="https://www.spyrit.net" title="Accéder au site de SPYRIT" target="_blank">Spyrit systèmes d'information</a>
            </div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function venio_page_init()
    {
        register_setting(
            'venio-group',
            'venio_config'
        );

        add_settings_section(
            'venio-section-api',
            'API',
            null,
            'venio-options'
        );

        add_settings_section(
            'venio-section-website',
            __('Website', 'venio'),
            null,
            'venio-options'
        );

        add_settings_field(
            'venio-institutions',
            'Institutions',
            [$this, 'venio_institutions_callback'],
            'venio-options',
            'venio-section-api'
        );

        add_settings_field(
            'venio-back-button-url',
            __('Back button URL', 'venio'),
            [$this, 'venio_back_button_url_callback'],
            'venio-options',
            'venio-section-website'
        );

        add_settings_field(
            'venio-back-button-label',
            __('Back button label', 'venio'),
            [$this, 'venio_back_button_label_callback'],
            'venio-options',
            'venio-section-website'
        );
    }

    public function venio_institutions_callback()
    {
        printf(
            '<input type="text" id="institutions" name="venio_config[institutions]" value="%s" placeholder="'.__('Institutions, separated by comma', 'venio').'" />',
            isset($this->options['institutions']) ? esc_attr($this->options['institutions']) : ''
        );
    }

    public function venio_back_button_url_callback()
    {
        printf(
            '<input type="text" id="back-button-url" name="venio_config[back-button-url]" value="%s" placeholder="'.__("The button's URL on event page", 'venio').'" />',
            isset($this->options['back-button-url']) ? esc_attr($this->options['back-button-url']) : ''
        );
    }

    public function venio_back_button_label_callback()
    {
        printf(
            '<input type="text" id="back-button-label" name="venio_config[back-button-label]" value="%s" placeholder="'.__("The button's label on event page", 'venio').'" />',
            isset($this->options['back-button-label']) ? esc_attr($this->options['back-button-label']) : ''
        );
    }
}
