<?php

namespace VENIO;

class Api
{
    public function init()
    {
        add_action('wp_ajax_get_events_by_string', [$this, 'getEventsByString']);
        add_action('wp_ajax_nopriv_get_events_by_string', [$this, 'getEventsByString']);
    }

    public function getEventsByString() {
        $institution = isset($_POST['institution']) && $_POST['institution'] ? sanitize_text_field($_POST['institution']) : null;
        $searchStr = isset($_POST['search']) && $_POST['search'] ? sanitize_text_field($_POST['search']) : null;
        $result = json_encode(venio_events_list($this->getEvents($institution, null, false, $searchStr)));
        wp_send_json_success($result);
    }

    private function getEvent($slug, $events)
    {
        foreach ($events as $event) {
            if ($slug === $event['subdomain']) {
                return $event;
            }
        }
        return false;
    }

    public function getEvents($institution = null, $slug = null, $ignoreCache = false, $searchStr = null)
    {
        $eventsFromCache = $this->getEventsFromCache();
        if ($ignoreCache || !$eventsFromCache) {
            if (!$this->getInstitutions()) {
                delete_transient(VENIO_TRANSIENT_NAME);
                return false;
            }
            $this->cacheEvents();
            return $this->getEvents($institution, $slug);
        }

        $allEvents = [];
        if (!$institution) {
            foreach ($eventsFromCache as $institutions) {
                if (isset($institutions['events']) && $institutions['events']) {
                    foreach ($institutions['events'] as $event) {
                        $allEvents[] = $event;
                    }
                }
            }
        } elseif (isset($eventsFromCache[$institution])) {
            foreach ($eventsFromCache[$institution]['events'] as $event) {
                $allEvents[] = $event;
            }
        }

        if ($slug) {
            return $this->getEvent($slug, $allEvents);
        }

        if ($searchStr) {
            $allEventsMatch = [];
            foreach ($allEvents as $event) {
                if(strpos(strtolower($event['name']), strtolower($searchStr)) !== false){
                    $allEventsMatch[] = $event;
                }
            }
            $allEvents = $allEventsMatch;
        }
        usort($allEvents, [$this, 'cmp']);

        return $allEvents;
    }

    private function getEventsFromCache()
    {
        if (get_transient(VENIO_TRANSIENT_NAME)) {
            return json_decode(get_transient(VENIO_TRANSIENT_NAME), true);
        }
        return false;
    }

    private function cmp($a, $b) {
        return strtotime($a['begin_date']) - strtotime($b['begin_date']);
    }

    private function cacheEvents()
    {
        $results = [];
        if ($this->getInstitutions()) {
            foreach($this->getInstitutions() as $institution) {
                $results[$institution] = json_decode($this->callApi($institution), true);
            }
            if (count($results) > 0) {
                set_transient(VENIO_TRANSIENT_NAME, json_encode($results), '3600');
            }
        }
    }

    private function getInstitutions()
    {
        $institutions = get_option('venio_config') && isset(get_option('venio_config')['institutions']) ? get_option('venio_config')['institutions'] : null;
        if ($institutions) {
            $institutions = trim($institutions);
            return explode(',', $institutions);
        }
        return false;
    }

    private function callApi($institution = null)
    {
        $args = [
            'user-agent' => 'Plugin VENIO',
        ];
        $response = wp_remote_get(VENIO_API_URL . $institution, $args);
        $body = wp_remote_retrieve_body($response);

        $date = new \DateTime();
        update_option('venio_api_last_call', $date->format('d/m/Y H:i'));

        return $body;
    }
}