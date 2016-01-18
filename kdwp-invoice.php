<?php
/*
Plugin Name: KDWP-Invoice
Description: Declares a plugin that will create invoices.
Version: 1.0
Author: kamena
License: GPLv2
*/

if( !defined( 'KDWP_URL' ) ) {
    define( 'KDWP_INV_URL', plugin_dir_url( __FILE__ ) ); // plugin dir
}
if( !defined( 'KDWP_PATH' ) ) {
    define( 'KDWP_PATH', dirname( __FILE__ ) );
}
define( 'KDWP_TEMP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'KDWP_TEMP_PATH_HELPERS', dirname( __FILE__ ) . '/helpers' );
define( 'KDWP_FOLDER', basename( KDWP_PATH ) );
define( 'KDWP_TEMP_URL', plugins_url() . '/' . KDWP_FOLDER );
define( 'KDWP_TEMP_URL_INCLUDES',  plugins_url() . '/' . KDWP_FOLDER . '/inc' );
define( 'KDWP_TEMP_URL_HELPERS',  plugins_url() . '/' . KDWP_FOLDER . '/helpers' );

// include( KDWP_INV_URL . 'inc/invoice.class.php');
require_once KDWP_TEMP_PATH_INCLUDES . '/invoice.class.php';
require_once KDWP_TEMP_PATH_INCLUDES . '/clients.class.php';
require_once KDWP_TEMP_PATH_HELPERS . '/custom_screen_options.php';
require_once KDWP_TEMP_PATH_HELPERS . '/clients_custom_screen_options.php';
require_once KDWP_TEMP_PATH_HELPERS . '/settings_menu.php';
require_once KDWP_TEMP_PATH_HELPERS . '/invoice-item-form.class.php';

class KDWPinvoice {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));

        add_filter( 'template_include', array( $this, 'include_template_function' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

        //add_action('admin_footer-post.php', array( $this, 'wptuts_add_my_meta_box') );

        //add action to call ajax
        add_action( 'wp_ajax_add_outlook_customer', array( $this, 'add_outlook_customer' ));
        add_action( 'wp_ajax_nopriv_add_outlook_customer',array( $this,  'add_outlook_customer' ));

        add_action( 'admin_init', array( $this, 'kdwp_invoice_admin_init' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('assets/css/master.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'print_css', plugins_url('assets/css/print.css', __FILE__ ), false, '1.0.0', 'print' );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-datepicker', plugins_url('assets/scripts/datepicker.js', __FILE__ ), array( 'jquery' ), '', true );
        // wp_enqueue_script( 'jquery-dynamic-table', plugins_url('/js/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
    }

    function wptuts_add_my_meta_box(){
      echo "<p>This paragraph will be shown in footer of the post edit page.</p>";
    }

    public function include_template_function( $template_path ) {
        if ( get_post_type() == 'invoice' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-invoces.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-invoces.php';
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

?>