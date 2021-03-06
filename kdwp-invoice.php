<?php
/*
 * Plugin Name: KDWP-Invoice
 * Description: Declares a plugin that will create invoices.
 * Version: 1.0
 * Author: kamena
 * License: GPLv2
 * Text Domain: kdwpinvoice
*/
// Define static variable
// if( !defined( 'KDWP_INV_URL' ) ) {
//     define( 'KDWP_INV_URL', plugin_dir_url( __FILE__ ) ); // plugin dir
// }
if( !defined( 'KDWP_PATH' ) ) {
    define( 'KDWP_PATH', dirname( __FILE__ ) );
}
if( !defined( 'KDWP_TEMP_PATH_INCLUDES' ) ) {
    define( 'KDWP_TEMP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
}
if( !defined( 'KDWP_TEMP_PATH_HELPERS' ) ) {
    define( 'KDWP_TEMP_PATH_HELPERS', dirname( __FILE__ ) . '/helpers' );
}
if( !defined( 'KDWP_FOLDER' ) ) {
    define( 'KDWP_FOLDER', basename( KDWP_PATH ) );
}
if( !defined( 'KDWP_TEMP_URL' ) ) {
    define( 'KDWP_TEMP_URL', plugins_url() . '/' . KDWP_FOLDER );
}
//define( 'KDWP_TEMP_URL_INCLUDES',  plugins_url() . '/' . KDWP_FOLDER . '/inc' );
//define( 'KDWP_TEMP_URL_HELPERS',  plugins_url() . '/' . KDWP_FOLDER . '/helpers' );

// Includes
require_once KDWP_TEMP_PATH_INCLUDES . '/invoice.class.php';
require_once KDWP_TEMP_PATH_INCLUDES . '/clients.class.php';
require_once KDWP_TEMP_PATH_INCLUDES . '/settings.class.php';
// Helpers
require_once KDWP_TEMP_PATH_HELPERS . '/custom_screen_options.php';
require_once KDWP_TEMP_PATH_HELPERS . '/clients_custom_screen_options.php';
require_once KDWP_TEMP_PATH_HELPERS . '/invoice-item-form.class.php';
require_once KDWP_TEMP_PATH_HELPERS . '/number_to_text_convertor.php';

class KDWPinvoice {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_wp_plugin_scripts' ) );

        add_filter( 'template_include', array( $this, 'include_template_function' ), 1 );

        add_action( 'admin_init', array( $this, 'kdwp_invoice_admin_init' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', KDWP_TEMP_URL.'/assets/css/master.css', false, '1.0.0' ); 
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_script( 'jquery-datepicker', plugins_url('assets/scripts/datepicker.js', __FILE__ ), '', true );  
    }

    public function register_wp_plugin_scripts() {
        wp_enqueue_style( 'print_css', KDWP_TEMP_URL.'/assets/css/print.css', true, '1.0.0', 'print' );
        wp_enqueue_style( 'custom_wp_css', KDWP_TEMP_URL.'/assets/css/master.css', false, '1.0.0' ); 
    }

    public function theme_name_scripts() {
        wp_enqueue_style( 'table_css', plugins_url('assets/css/bootstrap.min.css', __FILE__ ) );
    }

    public function include_template_function( $template_path ) {
        if ( get_post_type() == 'invoice' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-invoice.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-invoice.php';
                }
            }
        }

        return $template_path;
    }

    public function kdwp_invoice_admin_init() {
        register_setting( 'invoice_plugin_options', 'kdwp_invoice_options' );
    }   
}

new KDWPinvoice();  
