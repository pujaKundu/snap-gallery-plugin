<?php

$images_data = get_post_meta(get_the_ID(), 'snaps_images', true);

if (!empty($images_data)) {
    $images = json_decode($images_data, true);

    echo '<pre>';
    print_r($images);
    echo '</pre>';
    
    if (is_array($images)) {
        echo '<div class="gallery-images">';
        foreach ($images as $image) {
            if (!empty($image['url'])) {
                echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['description'] ?? '') . '">';
            }
        }
        echo '</div>';
    }
}
