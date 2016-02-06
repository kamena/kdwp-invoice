<?php 
class InvoiceSettings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_submenu_clients' ));
    }

    public function add_submenu_clients() {
        add_menu_page( __( 'Invoice Settings', 'kdwp-invoice' ), __( 'Фактури: Настройки', 'kdwp-invoice' ), 'manage_options','kdwp_invoice_settings', array($this, 'kdwp_invoice_settings') );
    }


    public function kdwp_invoice_settings(){
        require_once KDWP_TEMP_PATH_HELPERS . '/invoice-settings.php';
    }

 
}

new InvoiceSettings();

?>