<?php

class Living_Flea_Markets {
    function __construct() {
        add_action( 'init', array( $this, 'register_taxonomy' ) );
    }

    public function register_taxonomy() {
        $labels = array(
            'name' => _x( 'Flea Markets', 'flea-markets' ),
            'singular_name' => _x( 'Flea Market', 'flea-markets' ),
            'search_items' => _x( 'Search Flea Markets', 'flea-markets' ),
            'popular_items' => _x( 'Popular Flea Markets', 'flea-markets' ),
            'all_items' => _x( 'All Flea Markets', 'flea-markets' ),
            'parent_item' => _x( 'Parent Flea Market', 'flea-markets' ),
            'parent_item_colon' => _x( 'Parent Flea Market:', 'flea-markets' ),
            'edit_item' => _x( 'Edit Flea Market', 'flea-markets' ),
            'update_item' => _x( 'Update Flea Market', 'flea-markets' ),
            'add_new_item' => _x( 'Add New Flea Market', 'flea-markets' ),
            'new_item_name' => _x( 'New Flea Market', 'flea-markets' ),
            'separate_items_with_commas' => _x( 'Separate flea markets with commas', 'flea-markets' ),
            'add_or_remove_items' => _x( 'Add or remove Flea Markets', 'flea-markets' ),
            'choose_from_most_used' => _x( 'Choose from most used Flea Markets', 'flea-markets' ),
            'menu_name' => _x( 'Flea Markets', 'flea-markets' ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'show_admin_column' => true,
            'hierarchical' => true,
            'rewrite' => true,
            'query_var' => true
        );

        register_taxonomy( 'flea-markets', array('post'), $args );
    }
}

$living_flea_markets = new Living_Flea_Markets();
