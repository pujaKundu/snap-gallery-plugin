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
        wp_nonce_field(basename(__FILE__), 'snaps_nonce');
    
        $stored_meta = get_post_meta($post->ID, 'snaps_images', true);
        $images_data = $stored_meta ? json_decode($stored_meta, true) : [];
    
        ?>
    
        <div id="gallery_images" class="gallery_wrapper">
            <a href="#" id="add-gallery-image" class="button"><?php _e('Add Image', 'snaps'); ?></a>
            <ul id="gallery-images-list">
                <?php
                if (!empty($images_data) && is_array($images_data)) {
                    foreach ($images_data as $image_data) {
                        if (is_array($image_data) && isset($image_data['url'])) {
                            echo '<li>';
                            echo '<img class="list-image" src="' . esc_url($image_data['url']) . '" />';
                            echo '<a href="#" class="remove-image">' . __('Remove', 'snaps') . '</a>';
                            echo '</li>';
                        }
                    }
                }
                ?>
            </ul>
            <input type="hidden" id="snaps_images" name="snaps_images" multiple value="<?php echo esc_attr(json_encode($images_data)); ?>" />
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
    
        if (isset($_POST['snaps_settings']) && is_array($_POST['snaps_settings'])) {
            $customizations = array_map('sanitize_text_field', $_POST['snaps_settings']);
            update_post_meta($post_id, 'snaps_settings', $customizations);
        } else {
            delete_post_meta($post_id, 'snaps_settings');
        }
    }
    
}

add_action('add_meta_boxes', array('Gallery_MetaBox', 'add_meta_box'));

add_action('save_post', array('Gallery_MetaBox', 'save_meta_box_data'));
?>
