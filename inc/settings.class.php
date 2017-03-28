<?php 
class InvoiceSettings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'kdwp_invoice_settings_add' ) );
    }

    public function kdwp_invoice_settings_add() {
        add_menu_page( __( 'Invoice Settings', 'kdwpinvoice' ), __( 'Фактури: Настройки', 'kdwpinvoice' ), 'manage_options','kdwp_invoice_settings', array( $this, 'kdwp_invoice_settings' ) );
    }

    public function kdwp_invoice_settings(){
        require_once KDWP_TEMP_PATH_HELPERS . '/invoice-settings.php';
    }
}

new InvoiceSettings();
