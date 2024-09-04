<?php

class Gallery_CPT {
    public static function register() {
        $args = array(
            'labels' => array(
                'name' => __( 'Galleries', 'snaps' ),
                'singular_name' => __( 'Gallery', 'snaps' )
            ),
            'public' => true,
            'supports' => array( 'title', 'thumbnail', 'comments' ), 
            'menu_icon' => 'dashicons-format-gallery',
            'has_archive' => true,
            'rewrite' => array(
                'slug' => 'snaps-gallery', 
                'with_front' => false,    
                'pages' => true,          
                'feeds' => true
            ),
        );

        register_post_type( 'snaps_gallery', $args );
    }
}

add_action( 'init', array( 'Gallery_CPT', 'register' ) );

