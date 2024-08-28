<?php

// class Gallery_Display {

// public static function display_gallery_in_content($content) {
//     if (is_singular('snaps_gallery')) { 
//         global $post;

//         $images_data = get_post_meta($post->ID, 'snaps_images', true);
//         $images_data = $images_data ? json_decode($images_data, true) : [];

//         $gallery_output = '';

//         echo '<p>Hello from hell</p>';
//         echo "<input type='text' placeholder='Enter comment' />";

//         $gallery_output .= '<div class="gallery-images">';
//         foreach ($images_data as $image_data) {
//             if (is_array($image_data) && isset($image_data['url'])) {
//                 $gallery_output .= '<div class="gallery-item">';
//                 $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
//                 $gallery_output .= '</div>';
//             }
//         }
//         $gallery_output .= '</div>';

//         if (!empty($images_data) && is_array($images_data)) {
//             $gallery_output .= '<div class="gallery-images">';
//             foreach ($images_data as $image_data) {
//                 if (is_array($image_data) && isset($image_data['url'])) {
//                     $gallery_output .= '<div class="gallery-item">';
//                     $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
//                     $gallery_output .= '</div>';
//                 }
//             }
//             $gallery_output .= '</div>';
//         }

//         $content .= $gallery_output;
//     }

//     return $content;
// }
// }

// add_filter('the_content', array('Gallery_Display', 'display_gallery_in_content'));

class Gallery_Display {

    public static function display_gallery_in_content($content) {
        if (is_singular('snaps_gallery')) { 
            global $post;

            $images_data = get_post_meta($post->ID, 'snaps_images', true);
            $images_data = $images_data ? json_decode($images_data, true) : [];

            $gallery_output = '<div class="gallery-images">';
            foreach ($images_data as $image_data) {
                if (is_array($image_data) && isset($image_data['url'])) {
                    $gallery_output .= '<div class="gallery-item">';
                    $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
                    $gallery_output .= '</div>';
                }
            }
            $gallery_output .= '</div>';

            $content .= $gallery_output;
        }

        return $content;
    }
}

add_filter('the_content', array('Gallery_Display', 'display_gallery_in_content'));
