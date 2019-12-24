<?php
/*
Plugin Name: SPYRIT - Venio
Description: Une extension qui permet de connecter vos événements VENIO à votre site Wordpress
Author: SPYRIT
Author URI: http://www.spyrit.net
Version: 0.1
*/

/* Inclusion du type de contenu "evenement_venio" */
include_once plugin_dir_path(__FILE__) . 'cpt/evenement_venio_type.php';

/* Réglages, page d'option */
include_once plugin_dir_path(__FILE__) . 'options.php';

/* Notifications */
include_once plugin_dir_path(__FILE__) . 'notices.php';

include_once plugin_dir_path(__FILE__) . 'api/Synchro.php';
