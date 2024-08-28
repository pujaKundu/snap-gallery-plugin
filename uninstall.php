<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$galleries = get_posts( array( 'post_type' => 'gallery', 'numberposts' => -1 ) );

foreach ( $galleries as $gallery ) {
    wp_delete_post( $gallery->ID, true );
}
