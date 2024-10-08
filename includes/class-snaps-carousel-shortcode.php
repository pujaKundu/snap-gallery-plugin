<?php

class Snaps_Carousel_Shortcode {

    public static function register_shortcode() {
        add_shortcode('snaps_carousel', array(__CLASS__, 'render_carousel'));
    }

    public static function render_carousel($atts) {
   
    $args = array(
        'post_type' => 'snaps_gallery',
        'posts_per_page' => -1, 
    );

    $query = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        $output .= '<div class="snaps-carousel-container">'; 

        $output .= '<div class="snaps-carousel">';
        
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = get_the_ID();
            $post_title = get_the_title();
            $post_link = get_permalink();
            $featured_image = get_the_post_thumbnail($post_id, 'medium'); 

            $output .= '<div class="carousel-item">';
            $output .= '<a href="' . esc_url($post_link) . '">';
            $output .= $featured_image;
            $output .= '<h3>' . esc_html($post_title) . '</h3>'; 
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '</div>';

        $output .= '<button class="carousel-control prev">
        <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                    <path d="M20 .755l-14.374 11.245 14.374 11.219-.619.781-15.381-12 15.391-12 .609.755z" fill="#f7f7f7"/>
                    </svg></button>';
        $output .= '<button class="carousel-control next">
         <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M4 .755l14.374 11.245-14.374 11.219.619.781 15.381-12-15.391-12-.609.755z" fill="#f7f7f7"/></svg>
        </button>';

        $output .= '</div>'; 

        wp_reset_postdata(); 
    } else {
        $output .= '<p>No gallery posts found.</p>';
    }

    return $output;
}
}

Snaps_Carousel_Shortcode::register_shortcode();

?>
