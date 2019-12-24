<?php

function evenement_venio_type()
{
    $labels = array(
        'name'                  => _x('Événements', 'Post Type General Name', 'spyrit_venio'),
        'singular_name'         => _x('Événement', 'Post Type Singular Name', 'spyrit_venio'),
        'menu_name'             => __('Événements', 'spyrit_venio'),
        'name_admin_bar'        => __('Événements', 'spyrit_venio'),
        'archives'              => __('Archives', 'spyrit_venio'),
        'attributes'            => __('Attributs', 'spyrit_venio'),
        'parent_item_colon'     => __('Événement parent', 'spyrit_venio'),
        'all_items'             => __('Tous les événements', 'spyrit_venio'),
        'add_new_item'          => __('Ajouter un nouvel événement', 'spyrit_venio'),
        'add_new'               => __('Ajouter', 'spyrit_venio'),
        'new_item'              => __('Nouvel événement', 'spyrit_venio'),
        'edit_item'             => __('Modifier l\'événement', 'spyrit_venio'),
        'update_item'           => __('Mettre à jour l\'événement', 'spyrit_venio'),
        'view_item'             => __('Voir l\'événement', 'spyrit_venio'),
        'view_items'            => __('Voir les événements', 'spyrit_venio'),
        'search_items'          => __('Rechercher dans les événements', 'spyrit_venio'),
        'not_found'             => __('Aucun résultat', 'spyrit_venio'),
        'not_found_in_trash'    => __('Aucun résultat dans la corbeille', 'spyrit_venio'),
        'featured_image'        => __('Image liée', 'spyrit_venio'),
        'set_featured_image'    => __('Définir l\'image liée', 'spyrit_venio'),
        'remove_featured_image' => __('Supprimer l\'image liée', 'spyrit_venio'),
        'use_featured_image'    => __('Utiliser comme image', 'spyrit_venio'),
        'insert_into_item'      => __('Insérer dans l\'événement', 'spyrit_venio'),
        'uploaded_to_this_item' => __('Téléverser pour cet événement', 'spyrit_venio'),
        'items_list'            => __('Liste des événements', 'spyrit_venio'),
        'items_list_navigation' => __('Liste des événements', 'spyrit_venio'),
        'filter_items_list'     => __('Filtres', 'spyrit_venio'),
    );
    $args = array(
        'label'                 => __('Événement', 'spyrit_venio'),
        'description'           => __('Vos événements VENIO', 'spyrit_venio'),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-calendar-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type('evenement_venio', $args);
}
add_action('init', 'evenement_venio_type', 0);
