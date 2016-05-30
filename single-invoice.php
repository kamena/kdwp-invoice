<?php
 /*Template Name: New Template */

    $post = get_post( );
    // Customer company info
    $chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
    $company_name = get_post_meta( $chosen_client, 'company_name', true );
    $company_city = get_post_meta( $chosen_client, 'company_city', true );
    $company_address = get_post_meta( $chosen_client, 'company_address', true );
    $company_id = get_post_meta( $chosen_client, 'company_id', true );
    $responsible_person = get_post_meta( $chosen_client, 'responsible_person', true );
    $chosen_template = get_post_meta( $post->ID, 'chosen_template', true );

    $chosen_date = get_post_meta( $post->ID, 'chosen_date', true );


    $invoiceID = get_the_ID();
    $invoice_item_column_number = get_post_meta( $post->ID, 'invoice_item_column_number', true );

    // settings_fields( 'invoice_plugin_options' );
    $kdwp_invoice_options       = get_option( 'kdwp_invoice_options' );
    /*  Company Detail      */
    $kdwp_company_person             = isset($kdwp_invoice_options['kdwp_company_person']) ? $kdwp_invoice_options['kdwp_company_person'] : "";
    $kdwp_company_name               = isset($kdwp_invoice_options['kdwp_company_name']) ? $kdwp_invoice_options['kdwp_company_name'] : "";
    $kdwp_company_email              = isset($kdwp_invoice_options['kdwp_company_email']) ? $kdwp_invoice_options['kdwp_company_email'] : "";
    $kdwp_company_website            = isset($kdwp_invoice_options['kdwp_company_website']) ? $kdwp_invoice_options['kdwp_company_website'] : "";
    $kdwp_company_city               = isset($kdwp_invoice_options['kdwp_company_city']) ? $kdwp_invoice_options['kdwp_company_city'] : "";
    $kdwp_company_address            = isset($kdwp_invoice_options['kdwp_company_address']) ? $kdwp_invoice_options['kdwp_company_address'] : "";
    $kdwp_company_unique_number      = isset($kdwp_invoice_options['kdwp_company_unique_number']) ? $kdwp_invoice_options['kdwp_company_unique_number'] : "";
    $kdwp_company_responsible_person = isset($kdwp_invoice_options['kdwp_company_responsible_person']) ? $kdwp_invoice_options['kdwp_company_responsible_person'] : "";
    $kdwp_invoice_note               = isset($kdwp_invoice_options['kdwp_invoice_note']) ? $kdwp_invoice_options['kdwp_invoice_note'] : "";
    // Payment Detail
    $kdwp_bank_name                  = isset($kdwp_invoice_options['kdwp_bank_name']) ? $kdwp_invoice_options['kdwp_bank_name'] : "";
    $kdwp_company_iban               = isset($kdwp_invoice_options['kdwp_company_iban']) ? $kdwp_invoice_options['kdwp_company_iban'] : "";
    $kdwp_company_bic                = isset($kdwp_invoice_options['kdwp_company_bic']) ? $kdwp_invoice_options['kdwp_company_bic'] : "";
    $kdwp_payment_method             = get_post_meta( $post->ID, 'payment_method', true );
    $invoice_serial_number           = get_post_meta( $post->ID, 'invoice_serial_number', true );

    $a_id = $post->post_author;
    $kdwp_invoice_author = get_the_author_meta( 'first_name', $a_id ) . " " . get_the_author_meta( 'last_name', $a_id );


        require_once KDWP_PATH . '/html2pdf/vendor/autoload.php';
        ob_start();
        
        require_once KDWP_PATH . '/templates/kdwp-invoice_template_' . $chosen_template . '.php';
        
        $content = ob_get_clean();

        $html2pdf = new Spipu\Html2Pdf\Html2Pdf( 'P', 'A4', 'fr' );

        $html2pdf->setDefaultFont( 'Freesans' );
        $html2pdf->writeHTML( $content );


        $pdf_name = 'inv' . str_pad($invoice_serial_number, 10, "0", STR_PAD_LEFT) . '.pdf';
        $html2pdf->Output( $pdf_name );

    // $kdwp_filepath = dirname( __FILE__ ) . '/templates/kdwp-invoice_template_' . $chosen_template . '.php';
    // require_once( $kdwp_filepath ); 
?>    