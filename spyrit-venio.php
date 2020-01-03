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

/* Assets */
function spyrit_venio_style()
{
    wp_enqueue_style('spyrit-venio-style-global', plugin_dir_url(__FILE__) . 'assets/global.css');
    wp_enqueue_style('spyrit-venio-style-shortcodes', plugin_dir_url(__FILE__) . 'assets/shortcodes.css');
}
add_action('wp_enqueue_scripts', 'spyrit_venio_style');
