<?php
require_once( wp_normalize_path(ABSPATH).'wp-load.php');
class Synchro {


    public function __construct() {
    }

    public function process() {
        $institutions = preg_replace('/\s+/', '', get_option('venio-institutions'));
        $institutions = explode(',', $institutions);

        foreach ($institutions as $institution) {
            // Appel de la route d'API pour récupérer les événements
            $results = json_decode($this->CallAPI("https://api.venio.fr/api/v1/fr_FR/events/" . $institution));
            if (isset($results->events)) {
                $events = [];
                $events_ids = [];
                foreach ($results->events as $event) {
                    $events[] = $event;
                    $events_ids[] = $event->id;
                }

                $events_to_create = [];
                $events_to_update = [];

                // Comparaison des événements à créer / mettre à jour
                foreach ($events as $event) {
                    $eventsWp = get_posts([
                        'numberposts' => 1,
                        'post_type' => 'evenement_venio',
                        'meta_key' => 'id_venio',
                        'meta_value' => $event->id,
                        'post_status' => ['publish', 'pending', 'draft'],
                    ]);

                    $event->id_venio = $event->id;

                    if($eventsWp) {
                        $events_to_update[] = $event;
                        $event->id_wordpress = $eventsWp[0]->ID;
                    } else {
                        $events_to_create[] = $event;
                    }
                }

                // Création des événements dans WP
                foreach ($events_to_create as $event) {
                    $args = [
                        'post_type' => 'evenement_venio',
                        'post_title' => $event->name ? $this->convertSpecialChars($event->name) : '',
                        'post_excerpt' => $event->short_description ? $this->convertSpecialChars($event->short_description) : '',
                        'post_content' => $event->long_description ? $this->convertSpecialChars(wp_kses($event->long_description, CUSTOM_TAGS)) : '',
                        'post_status' => 'publish',
                    ];
                    $event_id = wp_insert_post($args);

                    $metaFields = $this->getEventMetaFields();
                    foreach ($metaFields as $field) {
                        $this->__update_post_meta($event_id, $field, $event->$field);
                    }
                }

                // Mise à jour des événements dans WP
                foreach ($events_to_update as $event) {
                    $args = [
                        'ID' => $event->id_wordpress,
                        'post_title' => $event->name ? $this->convertSpecialChars($event->name) : '',
                        'post_excerpt' => $event->short_description ? $this->convertSpecialChars($event->short_description) : '',
                        'post_content' => $event->long_description ? $this->convertSpecialChars(wp_kses($event->long_description, CUSTOM_TAGS)) : '',
                    ];
                    $event_id = wp_update_post($args);

                    $metaFields = $this->getEventMetaFields();
                    foreach ($metaFields as $field) {
                        $this->__update_post_meta($event_id, $field, $event->$field);
                    }
                    $this->__update_post_meta($event_id, 'institution', $institution);
                }

                // Création du différentiel entre les événements WP & Venio
                $events_to_delete = get_posts([
                    'numberposts' => -1,
                    'post_type' => 'evenement_venio',
                    'meta_query' => [
                        'relation' => 'AND',
                        [
                            'key' => 'id_venio',
                            'value'   => [''],
                            'compare' => 'NOT IN'
                        ],
                        [
                            'key' => 'id_venio',
                            'value'   => $events_ids,
                            'compare' => 'NOT IN'
                        ],
                        [
                            'key' => 'institution',
                            'value'   => $institution,
                            'compare' => '='
                        ],
                    ]
                ]);

                // Suppression des évenements en trop
                foreach ($events_to_delete as $event) {
                    $metas = get_post_meta($event->ID);
                    foreach($metas as $key=>$val)  {
                        delete_post_meta($event->ID, $key);
                    }
                    wp_delete_post($event->ID, false);
                }

                add_action('admin_notices', 'success_synchro_notice');

            } else {
                add_action('admin_notices', 'no_event_notice');
            }
        }
    }

    public function synchronize() {
        update_option('venio-last-synchro', new DateTime());
        if (get_option('institution')) {
            add_action('init', [$this, 'process']);
        } else {
            add_action('admin_notices', 'missing_institution_notice');
        }
    }




    private function CallAPI($url)
    {
        $curl = curl_init();
        $url = sprintf($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    private function getEventMetaFields()
    {
        return [
            'id_venio',
            'begin_date',
            'end_date',
        ];
    }

    public function __update_post_meta( $post_id, $field_name, $value = '' )
    {
        if ( empty( $value ) OR ! $value ) {
            delete_post_meta( $post_id, $field_name );
        } elseif ( ! get_post_meta( $post_id, $field_name ) ) {
            add_post_meta( $post_id, $field_name, $value );
        } else {
            update_post_meta( $post_id, $field_name, $value );
        }
    }

    protected function convertSpecialChars($subject)
    {
        return str_replace([
            '', // <control> (U+0080) c280
            '', // STRING TERMINATOR (U+009C) c29c
            '', // PARTIAL LINE BACKWARD (U+008C) c28c
            '', // PRIVATE USE ONE (U+0091) c291
            '', // PRIVATE USE TWO (U+0092) c292
            '', // SET TRANSMIT STATE (U+0093) c293
            '', // CANCEL CHARACTER (U+0094) c294
        ],[
            '€',
            'œ',
            'Œ',
            "'",
            "'",
            '"',
            '"',
        ], $subject);
    }
}
