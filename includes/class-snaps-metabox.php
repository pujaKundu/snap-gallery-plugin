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
    
        $settings = get_post_meta($post->ID, 'snaps_settings', true);
        $width = isset($settings['snaps_width']) ? esc_attr($settings['snaps_width']) : '200px';
        $height = isset($settings['snaps_height']) ? esc_attr($settings['snaps_height']) : '250px';
        $spacing = isset($settings['snaps_spacing']) ? esc_attr($settings['snaps_spacing']) : '10px';
        $hover_effect = isset($settings['snaps_hover_effect']) ? esc_attr($settings['snaps_hover_effect']) : 'none';
        $columns = isset($settings['snaps_columns']) ? esc_attr($settings['snaps_columns']) : '3'; 
    
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
            <input type="hidden" id="snaps_images" name="snaps_images" value="<?php echo esc_attr(json_encode($images_data)); ?>" />
        </div>
    
        <!-- Customization Options -->
        <div class="gallery-customizations">
            <h3><?php _e('Gallery Customizations', 'snaps'); ?></h3>
            <p>
                <label for="snaps_width"><?php _e('Image Width', 'snaps'); ?></label>
                <input type="text" id="snaps_width" name="snaps_settings[snaps_width]" value="<?php echo esc_attr($width); ?>" />
            </p>
            <p>
                <label for="snaps_height"><?php _e('Image Height', 'snaps'); ?></label>
                <input type="text" id="snaps_height" name="snaps_settings[snaps_height]" value="<?php echo esc_attr($height); ?>" />
            </p>
            <p>
                <label for="snaps_spacing"><?php _e('Image Spacing', 'snaps'); ?></label>
                <input type="text" id="snaps_spacing" name="snaps_settings[snaps_spacing]" value="<?php echo esc_attr($spacing); ?>" />
            </p>
    
            <div class="hover-options">
                <label><?php _e('Hover Effects', 'snaps'); ?></label><br>
                <label><input type="radio" name="snaps_settings[snaps_hover_effect]" value="none" <?php checked($hover_effect, 'none'); ?> /> <?php _e('None', 'snaps'); ?></label><br>
                <label><input type="radio" name="snaps_settings[snaps_hover_effect]" value="hover-shadow" <?php checked($hover_effect, 'hover-shadow'); ?> /> <?php _e('Shadow', 'snaps'); ?></label><br>
                <label><input type="radio" name="snaps_settings[snaps_hover_effect]" value="hover-scale" <?php checked($hover_effect, 'hover-scale'); ?> /> <?php _e('Scale', 'snaps'); ?></label><br>
                <label><input type="radio" name="snaps_settings[snaps_hover_effect]" value="hover-grayscale" <?php checked($hover_effect, 'hover-grayscale'); ?> /> <?php _e('Grayscale', 'snaps'); ?></label><br>
            </div>

            <div>
                <label for="snaps_columns"><?php _e('Number of Columns', 'snaps'); ?></label>
                <select id="snaps_columns" name="snaps_settings[snaps_columns]">
                    <option value="1" <?php selected($columns, '1'); ?>>1</option>
                    <option value="2" <?php selected($columns, '2'); ?>>2</option>
                    <option value="3" <?php selected($columns, '3'); ?>>3</option>
                    <option value="4" <?php selected($columns, '4'); ?>>4</option>
                    <option value="5" <?php selected($columns, '5'); ?>>5</option>
                </select>
            </div>
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
