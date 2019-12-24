<?php

// Success

function success_synchro_notice() {
    global $pagenow;
    if ( $pagenow == 'admin.php' ) {
        echo "<div class='notice notice-success is-dismissible'><p>Les événements ont bien été récupérés et/ou mis à jour.</p></div>";
    }
}

// Warning

function missing_institution_notice() {
    global $pagenow;
    if ( $pagenow == 'admin.php' ) {
        echo "<div class='notice notice-warning is-dismissible'><p>Les événements ne peuvent pas être récupérés car le champ <strong>Institutions(s)</strong> n'est pas renseigné.</p></div>";
    }
}

function no_event_notice() {
    global $pagenow;
    if ( $pagenow == 'admin.php' ) {
        echo "<div class='notice notice-warning is-dismissible'><p>Aucun événement n'a pu être récupéré, merci de vérifier que le <strong>nom de l'institution</strong> renseigné est correct.</p></div>";
    }
}