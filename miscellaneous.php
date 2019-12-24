<?php

add_action('admin_init', 'venio_flush');
function venio_flush()
{
    if (!get_option('venio-init')) {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
        $wp_rewrite->init();
        update_option('venio-init', true);
    }
}

function venio_date($event)
{
    setlocale(LC_ALL, 'fr_FR');
    $dateFormatted = null;
    $date_start = DateTime::createFromFormat('Y-m-d', get_post_meta($event->ID, 'begin_date')[0]);
    $date_end = DateTime::createFromFormat('Y-m-d', get_post_meta($event->ID, 'end_date')[0]);
    if ($date_start) {
        $year = $date_start->format('Y');
        if (!$date_end) {
            $year = $date_start->format('Y');
            $dateFormatted =  ucfirst(date_i18n('D', $date_start->getTimestamp())) . '. ' . $date_start->format('j') . ' ' . strtolower(strftime('%b', $date_start->getTimestamp())) . '. ' . $year;
        } else {
            if ($date_start->format('Y') == $date_end->format('Y')) {
                if ($date_start->format('M') == $date_end->format('M')) {
                    $dateFormatted = $date_start->format('j') . ' au ' . $date_end->format('j') . ' ' . strtolower(strftime('%b', $date_start->getTimestamp())) . '. ' . $year;
                } else {
                    $dateFormatted = $date_start->format('j') . ' ' . strtolower(strftime('%b', $date_start->getTimestamp())) . '. au ' . $date_end->format('j') . ' ' . strtolower(date_i18n('M', $date_end->getTimestamp())) . '. ' . $year;
                }
            } else {
                $dateFormatted = $date_start->format('j') . ' ' . strtolower(strftime('%b', $date_start->getTimestamp())) . '. ' . $date_start->format('Y') . ' au ' . $date_end->format('j') . ' ' . strtolower(strftime('%b', $date_end->getTimestamp())) . '. ' . $date_end->format('Y');
            }
        }
    }
    return $dateFormatted;
}
