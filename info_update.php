<?php
add_filter('plugins_api', 'venio_plugin_info', 20, 3);

function venio_plugin_info($res, $action, $args)
{
    if ($action !== 'plugin_information') {
        return false;
    }

    if ('spyrit-venio' !== $args->slug) {
        return $res;
    }

    if (false == $remote = get_transient('spyrit_upgrade_spyrit-venio')) {
        $remote = wp_remote_get(
            'https://raw.githubusercontent.com/spyrit/spyrit-venio/master/info.json',
            [
                'timeout' => 10,
                'headers' => [
                    'Accept' => 'application/json'
                ]]
        );

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('spyrit_upgrade_spyrit-venio', $remote, 43200);
        }
    }

    if ($remote) {
        $remote = json_decode($remote['body']);
        $res = new stdClass();
        $res->name = $remote->name;
        $res->slug = 'spyrit-venio';
        $res->version = $remote->version;
        $res->tested = $remote->tested;
        $res->requires = $remote->requires;
        $res->author = '<a href="' . $remote->author_homepage . '">' . $remote->author . '</a>';
        $res->download_link = $remote->download_url;
        $res->trunk = $remote->download_url;
        $res->last_updated = $remote->last_updated;
        $res->sections = [
            'description' => $remote->sections->description,
            'installation' => $remote->sections->installation,
            'changelog' => $remote->sections->changelog,
        ];

        $res->banners = array(
            'low' => plugins_url('/img/venio-banner-772x250.jpg', __FILE__),
            'high' => plugins_url('/img/venio-banner-1544x500.jpg', __FILE__)
        );
        return $res;
    }

    return false;
}


add_filter('site_transient_update_plugins', 'venio_push_update');

function venio_push_update($transient)
{
    if (empty($transient->checked)) {
        return $transient;
    }

    if (false == $remote = get_transient('spyrit_upgrade_spyrit-venio')) {
        $remote = wp_remote_get(
            'https://raw.githubusercontent.com/spyrit/spyrit-venio/master/info.json',
            array(
                'timeout' => 10,
                'headers' => array(
                    'Accept' => 'application/json'
                ) )
        );

        if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
            set_transient('spyrit_upgrade_spyrit-venio', $remote, 43200);
        }
    }

    if ($remote) {
        $remote = json_decode($remote['body']);

        if ($remote && version_compare(PLUGIN_VERSION, $remote->version, '<') && version_compare($remote->requires, get_bloginfo('version'), '<')) {
            $res = new stdClass();
            $res->slug = 'spyrit-venio';
            $res->plugin = 'spyrit-venio/spyrit-venio.php';
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[$res->plugin] = $res;
        }
    }
    return $transient;
}

add_action('upgrader_process_complete', 'venio_after_update', 10, 2);

function venio_after_update($upgrader_object, $options)
{
    if ($options['action'] == 'update' && $options['type'] === 'plugin') {
        delete_transient('spyrit_upgrade_spyrit-venio');
    }
}
