<?php 
class InvoiceSettings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_submenu_clients' ));
        add_action( 'add_meta_boxes', array( $this, 'cd_meta_box_add' ));
        add_action( 'save_post', array( $this, 'add_client_fields'), 10, 2 );
    }

    public function add_submenu_clients() {
        add_menu_page( __( 'Invoice Settings', 'kdwp-invoice' ), __( 'Invoice Settings', 'kdwp-invoice' ), 'manage_options','kdwp_invoice_settings', array($this, 'kdwp_invoice_settings') );
    }


    public function kdwp_invoice_settings(){
        include_once plugin_dir_path( __FILE__ ) . '/helpers/invoice-settings.php';
    }

}

new InvoiceSettings();

?>