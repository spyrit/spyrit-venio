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
    $startDateTime = DateTime::createFromFormat('Y-m-d', get_post_meta($event->ID, 'begin_date')[0]);
    $endDateTime = DateTime::createFromFormat('Y-m-d', get_post_meta($event->ID, 'end_date')[0]);

    if ($startDateTime) {
        if ($startDateTime->format('Y') == $endDateTime->format('Y')) {
            // Si l'année est la même
            if ($startDateTime->format('M') == $endDateTime->format('M')) {
                // Si le mois est le même
                $dateFormatted =
                    'du ' .
                    strftime("%d", $startDateTime->getTimestamp()) .
                    ' au ' .
                    strftime("%d", $endDateTime->getTimestamp()) .
                    ' ' .
                    strftime("%B", $startDateTime->getTimestamp()) .
                    ' ' .
                    strftime("%Y", $startDateTime->getTimestamp())
                ;
            } else {
                // Si le mois est différent
                $dateFormatted =
                    'du ' .
                    strftime("%d", $startDateTime->getTimestamp()) .
                    ' ' .
                    strftime("%B", $startDateTime->getTimestamp()) .
                    '. au ' .
                    strftime("%d", $endDateTime->getTimestamp()) .
                    ' ' .
                    strftime("%B", $startDateTime->getTimestamp()) .
                    '. ' .
                    strftime("%Y", $startDateTime->getTimestamp())
                ;
            }
        } else {
            // Si l'année est différente
            $dateFormatted =
                'du ' .
                strftime("%d", $startDateTime->getTimestamp()) .
                ' ' .
                strftime("%B", $startDateTime->getTimestamp()) .
                '. ' .
                strftime("%%Y", $startDateTime->getTimestamp()) .
                ' au ' .
                strftime("%d", $endDateTime->getTimestamp()) .
                ' ' .
                strftime("%B", $endDateTime->getTimestamp()) .
                '. ' .
                strftime("%Y", $endDateTime->getTimestamp())
            ;
        }
    }
    return $dateFormatted;
}
