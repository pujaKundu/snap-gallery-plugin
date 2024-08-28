<?php

class Gallery_Shortcode {
    public static function render() {

        global $post;
        // require_once SNAPS_PATH;
        $images_data = get_post_meta($post->ID, 'snaps_images', true);
        // $images_data = get_post_meta($post->ID, 'snaps_images', true);
        $output = '';

        if ($images_data) {
            $images_data = json_decode($images_data, true);

            if (is_array($images_data)) {
                $output .= '<div class="gallery-images">';
                foreach ($images_data as $image_data) {
                    if (is_array($image_data) && isset($image_data['url'])) {
                        $output .= '<div class="gallery-item">';
                        $output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
                        $output .= '</div>';
                    }
                }
                $output .= '</div>';
            }
        }

        return $output;
    }
}

add_shortcode('gallery_shortcode', ['Gallery_Shortcode', 'render']);






