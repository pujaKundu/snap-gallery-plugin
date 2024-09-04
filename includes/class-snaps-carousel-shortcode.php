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

        $output .= '<button class="carousel-control prev">Previous</button>';
        $output .= '<button class="carousel-control next">Next</button>';

        wp_reset_postdata(); 
    } else {
        $output .= '<p>No gallery posts found.</p>';
    }

    $output .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const items = document.querySelectorAll(".snaps-carousel .carousel-item");
            const nextButton = document.querySelector(".carousel-control.next");
            const prevButton = document.querySelector(".carousel-control.prev");
            let currentItem = 0;
            const totalItems = items.length;

            function showItem(index) {
                items.forEach((item, i) => {
                    item.style.display = (i === index) ? "block" : "none";
                });
            }

            function nextItem() {
                currentItem = (currentItem + 1) % totalItems;
                showItem(currentItem);
            }

            function prevItem() {
                currentItem = (currentItem - 1 + totalItems) % totalItems;
                showItem(currentItem);
            }

            showItem(currentItem);

            nextButton.addEventListener("click", nextItem);
            prevButton.addEventListener("click", prevItem);

            setInterval(nextItem, 3000);
        });
    </script>';

    return $output;
}
}

Snaps_Carousel_Shortcode::register_shortcode();




?>
