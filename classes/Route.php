<?php

namespace VENIO;

class Route
{
    public function init()
    {
        add_action('wp_loaded', [$this, 'flushRules']);
        add_filter('rewrite_rules_array', [$this, 'rewriteRules']);
        add_filter('query_vars', [$this, 'insertQueryVars']);
        add_filter('template_include', [$this, 'addTemplate']);
    }

    public function flushRules()
    {
        $rules = get_option('rewrite_rules');
        if (!isset($rules['evenement/(.*)?'])) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }

    public function rewriteRules($rules)
    {
        $newrules = [];
        $newrules['evenement/(.*)?'] = 'index.php?evenement=$matches[1]';
        return $newrules + $rules;
    }

    public function insertQueryVars($vars)
    {
        array_push($vars, 'evenement');
        return $vars;
    }

    public function addTemplate($template)
    {
        $event_page = get_query_var('evenement');
        if ($event_page) {
            add_filter('document_title_parts', [$this, 'addPageTitle']);
            add_filter('body_class', [$this, 'addBodyClasses']);
            $template = VENIO_PLUGIN_DIR_PATH . 'templates/evenement-venio.php';
        }

        return $template;
    }

    public function addPageTitle($title_arr)
    {
        $api = new \VENIO\Api();
        $event = $api->getEvents(null, get_query_var('evenement'));
        $title_arr['title'] = $event['name'];
        return $title_arr;
    }

    public function addBodyClasses()
    {
        $classes[] = 'singular';
        return $classes;
    }
}