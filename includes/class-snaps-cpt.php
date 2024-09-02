<?php

class Gallery_CPT {
    public static function register() {
        register_post_type( 'snaps_gallery',
            array(
                'labels' => array(
                    'name' => __( 'Galleries', 'snaps' ),
                    'singular_name' => __( 'Gallery', 'snaps' )
                ),
                'public' => true,
                'supports' => array( 'title', 'thumbnail', 'comments' ), 
                'menu_icon' => 'dashicons-format-gallery',
                'has_archive' => true,
            )
        );
    }
}
