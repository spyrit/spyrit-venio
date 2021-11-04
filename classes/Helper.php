<?php

namespace VENIO;

use DateTime;

class Helper
{
    public function getEventMedias($event)
    {
        $images = [];
        if (isset($event['images']) && $event['images']) {
            foreach ($event['images'] as $imagePath) {
                $images[] = VENIO_URL . $imagePath;
            }
        }
        return $images;
    }

    public function getEventThumbnail($event)
    {
        if (isset($event['images']) && $event['images']) {
            foreach ($event['images'] as $imagePath) {
                return VENIO_URL . $imagePath;
            }
        }
        return false;
    }

    public function hasThumbnail($event)
    {
        if (isset($event['images']) && count($event['images']) > 0) {
            return true;
        }
        return false;
    }

    public function getFormattedDate($event)
    {
        if (isset($event['begin_date']) && $event['begin_date']) {
            setlocale(LC_ALL, 'fr_FR.UTF-8');
            $startDateTime = DateTime::createFromFormat('Y-m-d', $event['begin_date']);
            $endDateTime = DateTime::createFromFormat('Y-m-d', $event['end_date']);

            return
                strftime("%d", $startDateTime->getTimestamp()) .
                ' ' .
                strftime("%b", $startDateTime->getTimestamp()) .
                '. ' .
                strftime("%Y", $startDateTime->getTimestamp()) .
                ' — ' .
                strftime("%d", $endDateTime->getTimestamp()) .
                ' ' .
                strftime("%B", $endDateTime->getTimestamp()) .
                '. ' .
                strftime("%Y", $endDateTime->getTimestamp())
            ;
        }
        return false;
    }
}