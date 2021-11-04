<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
$options = ['venio_config', 'venio_api_last_call'];
foreach ($options as $option_name) {
    delete_option($option_name);
    delete_site_option($option_name);
}