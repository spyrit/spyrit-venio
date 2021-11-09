<?php
namespace VENIO;

use VENIO\API as Api;
use VENIO\Route as Route;

class VenioPlugin
{
    public function init()
    {
        // Load translation
        load_plugin_textdomain('venio', false,  'venio/languages');

        // Init routes
        $route = new Route();
        $route->init();

        // Init Api
        $api = new Api();
        $api->init();

        // Init Setting page
        if (is_admin()) {
            new \SettingsPage();
        }

        // Call includes
        require_once VENIO_PLUGIN_DIR_PATH . 'inc/venio-events-list.php';

        // Call shortcodes
        require_once VENIO_PLUGIN_DIR_PATH . 'shortcodes/venio-events-shortcode.php';

        // Call stylesheets and script
        wp_enqueue_style('venio-style', plugin_dir_url(__FILE__) . '../assets/venio.css');
    }
}