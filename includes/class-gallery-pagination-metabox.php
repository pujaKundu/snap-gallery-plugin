<?php

class Gallery_Pagination_MetaBox {

    public static function add_pagination_meta_box() {
        add_meta_box(
            'snaps_pagination_options',
            __('Gallery Pagination Options', 'snaps'),
            array(__CLASS__, 'render_pagination_meta_box'),
            'snaps_gallery', 
            'side',
            'low'
        );
    }

    public static function render_pagination_meta_box($post) {
        wp_nonce_field(basename(__FILE__), 'snaps_pagination_nonce');

        $images_per_page = get_post_meta($post->ID, '_snaps_images_per_page', true) ?: 3;
        ?>
        <p>
            <label for="snaps_images_per_page"><strong><?php _e('Images per Page', 'snaps'); ?>:</strong></label>
            <input type="number" name="snaps_images_per_page" id="snaps_images_per_page"
                   value="<?php echo esc_attr($images_per_page); ?>" min="1" />
        </p>
        <?php
    }

    public static function save_pagination_meta_box_data($post_id) {
        if (!isset($_POST['snaps_pagination_nonce']) || !wp_verify_nonce($_POST['snaps_pagination_nonce'], basename(__FILE__))) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['snaps_images_per_page'])) {
            $images_per_page = sanitize_text_field($_POST['snaps_images_per_page']);
            update_post_meta($post_id, '_snaps_images_per_page', $images_per_page);
        } else {
            delete_post_meta($post_id, '_snaps_images_per_page');
        }
    }
}

add_action('add_meta_boxes', array('Gallery_Pagination_MetaBox', 'add_pagination_meta_box'));
add_action('save_post', array('Gallery_Pagination_MetaBox', 'save_pagination_meta_box_data'));

