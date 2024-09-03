<?php
/*
 * Plugin Name:       Snaps
 * Plugin URI:        https://wordpress.org/plugins/snaps
 * Description:       A plugin to create an image gallery.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Puja
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       snaps
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

class Snaps {

    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();

    }

    private function define_constants() {
        define( 'SNAPS_PATH', plugin_dir_path( __FILE__ ) );
        define( 'SNAPS_URL', plugin_dir_url( __FILE__ ) );
    }

    private function includes() {
        require_once SNAPS_PATH . 'includes/class-snaps-cpt.php';
        require_once SNAPS_PATH . 'includes/class-snaps-metabox.php';
        require_once SNAPS_PATH . 'includes/class-snaps-shortcode.php';
        require_once SNAPS_PATH . 'includes/class-gallery-display.php';
        require_once SNAPS_PATH . 'includes/class-gallery-customization-metabox.php'; 
    }

    private function init_hooks() {
        add_action( 'init', array( 'Gallery_CPT', 'register' ) );
        add_action( 'add_meta_boxes', array( 'Gallery_MetaBox', 'add_meta_box' ) );
        add_action( 'save_post', array( 'Gallery_MetaBox', 'save_meta_box_data' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_styles' ) );
        add_shortcode( 'snaps_image_gallery', array( 'Gallery_Shortcode', 'render' ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'snaps-gallery-style', SNAPS_URL . 'assets/css/style.css' );
        wp_enqueue_script( 'snaps-gallery-script', SNAPS_URL . 'assets/js/script.js', array( 'jquery' ), null, true );

        if ( is_admin() ) {
            wp_enqueue_media();
        }
    }

    public function enqueue_frontend_styles() {
        wp_enqueue_style( 'snaps-gallery-style', SNAPS_URL . 'assets/css/style.css' );
        wp_enqueue_script( 'snaps-gallery-script', SNAPS_URL . 'assets/js/script.js', array( 'jquery' ), null, true );
    }
}

new Snaps();
