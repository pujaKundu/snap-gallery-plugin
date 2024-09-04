<?php

class Gallery_Display
{
    public static function display_gallery_in_content($content)
    {
        if (is_singular('snaps_gallery')) {
            global $post;

            $images_data = get_post_meta($post->ID, 'snaps_images', true);
            $images_data = $images_data ? json_decode($images_data, true) : [];

            $featured_image = get_the_post_thumbnail($post->ID, 'full', array('class' => 'custom-featured-image'));

            $settings = get_post_meta($post->ID, 'snaps_settings', true);
            $width = isset($settings['snaps_width']) ? esc_attr($settings['snaps_width']) : '100%';
            $height = isset($settings['snaps_height']) ? esc_attr($settings['snaps_height']) : '100%';
            $spacing = isset($settings['snaps_spacing']) ? esc_attr($settings['snaps_spacing']) : '10px';
            $hover_effect = isset($settings['snaps_hover_effect']) ? esc_attr($settings['snaps_hover_effect']) : '';
            $columns = isset($settings['snaps_columns']) ? esc_attr($settings['snaps_columns']) : '3';

            $images_per_page = get_post_meta($post->ID, '_snaps_images_per_page', true) ?: 3;
            $total_images = count($images_data);
            $total_pages = ceil($total_images / $images_per_page);
            $current_page = isset($_GET['gallery_page']) ? absint($_GET['gallery_page']) : 1;
            $offset = ($current_page - 1) * $images_per_page;

            $inline_styles = "
                <style>
                    .gallery-images {
                        display: grid;
                        grid-template-columns: repeat({$columns}, 1fr);
                        gap: {$spacing};
                        min-width:90vw;
                        justify-items:center;
                        align-content:center;
                    }
                    .gallery-images .gallery-item {
                        width: 100%;
                        height: {$height};
                        position: relative;
                        overflow: hidden;
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
            $gallery_output = '<div class="gallery-container">'; 

            $gallery_output .= '<div class="gallery-images">';

            $paged_images = array_slice($images_data, $offset, $images_per_page);
            foreach ($paged_images as $image_data) {
                if (is_array($image_data) && isset($image_data['url'])) {
                    $gallery_output .= '<div class="gallery-item ' . esc_attr($hover_effect) . '">';
                    $gallery_output .= '<img src="' . esc_url($image_data['url']) . '" alt="' . esc_attr($image_data['description'] ?? '') . '" />';
                    $gallery_output .= '</div>';
                }
            }
            $gallery_output .= '</div>';

            if ($total_pages > 1) {
                $gallery_output .= '<div class="gallery-pagination">';
                if ($current_page > 1) {
                    $gallery_output .= '<a class="paginate-btn" href="' . add_query_arg('gallery_page', $current_page - 1) . '">
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M20 .755l-14.374 11.245 14.374 11.219-.619.781-15.381-12 15.391-12 .609.755z" fill="#f7f7f7"/>
                    </svg>

                    Previous</a>';
                }
                if ($current_page < $total_pages) {
                    $gallery_output .= '<a class="paginate-btn" href="' . add_query_arg('gallery_page', $current_page + 1) . '">Next
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M4 .755l14.374 11.245-14.374 11.219.619.781 15.381-12-15.391-12-.609.755z" fill="#f7f7f7"/></svg>
                    </a>';
                }
                $gallery_output .= '</div>';
            }

            $content .= $inline_styles . $gallery_output;
        }

        return $content;
    }
}


add_filter('the_content', array('Gallery_Display', 'display_gallery_in_content'));