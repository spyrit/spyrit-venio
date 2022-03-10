<?php
/**
 * Plugin Name: Venio
 * Plugin URI: https://venio.fr/extension-wordpress-venio/
 * Description: Display Venio events directly on your WordPress website.
 * Version: 1.1.0
 * Requires at least: 5.7
 * Requires PHP: 5.6
 * Author: SPYRIT
 * Author URI: https://www.spyrit.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: venio
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define("VENIO_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
define("VENIO_TRANSIENT_NAME", 'venio_events');
define("VENIO_API_URL", 'https://api.venio.fr/api/v1/fr_FR/events/');
define("VENIO_URL", 'https://www.venio.fr');
define("VENIO_VERSION", '1.1.0');

// Include classes
require_once VENIO_PLUGIN_DIR_PATH . 'classes/VenioPlugin.php';
require_once VENIO_PLUGIN_DIR_PATH . 'classes/Route.php';
require_once VENIO_PLUGIN_DIR_PATH . 'classes/Helper.php';
require_once VENIO_PLUGIN_DIR_PATH . 'classes/Api.php';
require_once VENIO_PLUGIN_DIR_PATH . 'classes/SettingsPage.php';

add_action('init', function (){
    $plugin = new VENIO\VenioPlugin();
    $plugin->init();
});

// Settings link
function venio_settings_link($links)
{
    $settings_link = '<a href="'.admin_url().'admin.php?page=venio-options">'.esc_html__('Settings','venio').'</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'venio_settings_link');