<?php
/*
Plugin Name: SPYRIT - Venio
Description: Une extension qui permet de connecter vos événements VENIO à votre site Wordpress
Author: SPYRIT
Author URI: http://www.spyrit.net
Version: 0.1
*/

/* Type de contenu */
include_once plugin_dir_path(__FILE__) . 'cpt/evenement-venio_type.php';

/* Shortcodes */
include_once plugin_dir_path(__FILE__) . 'shortcodes/venio-related-events_shortcode.php';

/* Réglages, page d'option */
include_once plugin_dir_path(__FILE__) . 'options.php';

/* Notifications */
include_once plugin_dir_path(__FILE__) . 'notices.php';

/* Autre */
include_once plugin_dir_path(__FILE__) . 'miscellaneous.php';

/* Template */
add_filter('template_include', 'venio_template');
function venio_template($template)
{
    $post_types = ['evenement-venio'];

    if (is_singular($post_types)) {
        $template = plugin_dir_path(__FILE__) . 'templates/single-evenement-venio.php';
    }

    return $template;
}
