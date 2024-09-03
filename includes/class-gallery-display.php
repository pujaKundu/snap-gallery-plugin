<?php

class Gallery_Display {

    public static function display_gallery_in_content($content) {
        if (is_singular('snaps_gallery')) {
            global $post;
    
            $images_data = get_post_meta($post->ID, 'snaps_images', true);
            $images_data = $images_data ? json_decode($images_data, true) : [];
    
            $settings = get_post_meta($post->ID, 'snaps_settings', true);
            $width = isset($settings['snaps_width']) ? esc_attr($settings['snaps_width']) : '100%';
            $height = isset($settings['snaps_height']) ? esc_attr($settings['snaps_height']) : '100%';
            $spacing = isset($settings['snaps_spacing']) ? esc_attr($settings['snaps_spacing']) : '10px';
            $hover_effect = isset($settings['snaps_hover_effect']) ? esc_attr($settings['snaps_hover_effect']) : '';
            $columns = isset($settings['snaps_columns']) ? esc_attr($settings['snaps_columns']) : '3';
    
            $inline_styles = "
                <style>
                    .gallery-images {
                        display: grid;
                        grid-template-columns: repeat({$columns}, 1fr);
                        gap: {$spacing};
                    }

                    .gallery-images .gallery-item {
                        width: 100%;
                        height: {$height};
                        position: relative;
                        overflow: hidden;
                    }
                    .gallery-images .gallery-item img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    .hover-shadow:hover { 
                        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
                    }
                    .hover-scale:hover { 
                        transform: scale(1.1);
                        transition: transform 0.3s ease;
                    }
                    .hover-grayscale:hover {
                        filter: grayscale(100%);
                        transition: filter 0.3s ease;
                    }
                </style>
            ";
    
            $gallery_output = '<div class="gallery-images">';
            foreach ($images_data as $image_data) {
                if (is_array($image_data) && isset($image_data['url'])) {
                    $gallery_output .= '<div class="gallery-item ' . esc_attr($hover_effect) . '">';
                    // $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
                    $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" 
srcset="' . esc_attr(wp_get_attachment_image_srcset($image_id)) . '" 
sizes="(max-width: 600px) 100vw, (max-width: 1200px) 50vw, 33vw" />';

                    $gallery_output .= '</div>';
                }
            }
            $gallery_output .= '</div>';
    
            $content .= $inline_styles . $gallery_output;
        }
    
        return $content;
    }
    
}

add_filter('the_content', array('Gallery_Display', 'display_gallery_in_content'));

?>
