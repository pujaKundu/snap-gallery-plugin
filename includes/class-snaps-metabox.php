<?php

class Gallery_MetaBox {
    public static function add_meta_box() {
        add_meta_box(
            'snaps-images',
            __( 'Gallery Images', 'snaps' ),
            array( __CLASS__, 'render_meta_box' ),
            'snaps_gallery',
            'normal',
            'high'
        );
    }

    public static function render_meta_box($post) {
        wp_nonce_field( basename(__FILE__), 'snaps_nonce' );
        $stored_meta = get_post_meta($post->ID, 'snaps_images', true);
        $images_data = $stored_meta ? json_decode($stored_meta, true) : [];
        ?>

        <div id="gallery_images">
            <a href="#" id="add-gallery-image" class="button">Add Image</a>
            <ul id="gallery-images-list">
                <?php
                if (!empty($images_data) && is_array($images_data)) {
                    foreach ($images_data as $image_data) {
                        if (is_array($image_data) && isset($image_data['url'])) {
                            echo '<li>';
                            echo '<img width="300px" height="400px" src="' . esc_url($image_data['url']) . '" />';
                            echo '<a href="#" class="remove-image">Remove</a>';
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ul>
            <input type="hidden" id="snaps_images" name="snaps_images" value="<?php echo esc_attr(json_encode($images_data)); ?>" />
        </div>

        <?php
    }

    public static function save_meta_box_data($post_id) {
        if (!isset($_POST['snaps_nonce']) || !wp_verify_nonce($_POST['snaps_nonce'], basename(__FILE__))) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save image data
        if (isset($_POST['snaps_images'])) {
            $images_data = json_decode(stripslashes($_POST['snaps_images']), true);
            if (is_array($images_data)) {
                update_post_meta($post_id, 'snaps_images', json_encode($images_data));
            } else {
                delete_post_meta($post_id, 'snaps_images');
            }
        } else {
            delete_post_meta($post_id, 'snaps_images');
        }
    }
}
