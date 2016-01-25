<?php
 /*Template Name: New Template
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Фактура</title>
    <link rel="stylesheet" href="<?php echo KDWP_TEMP_URL.'/assets/css/bootstrap.min.css' ;?>" >
</head>
<body>
<div class="container">
    <?php

    $post = get_post( );
    // Customer company info
    $chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
    $company_name = get_post_meta( $chosen_client, 'company_name', true );
    $company_city = get_post_meta( $chosen_client, 'company_city', true );
    $company_address = get_post_meta( $chosen_client, 'company_address', true );
    $company_id = get_post_meta( $chosen_client, 'company_id', true );
    $responsible_person = get_post_meta( $chosen_client, 'responsible_person', true );
    $chosen_template = get_post_meta( $post->ID, 'chosen_template', true );

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

    $kdwp_serial_number              = isset($kdwp_invoice_options['kdwp_serial_number']) ? $kdwp_invoice_options['kdwp_serial_number'] : "1";


    $kdwp_filepath = dirname( __FILE__ ) .'/templates/kdwp-invoice_template_'.$chosen_template.'.php';
    require_once($kdwp_filepath); 
?>
    <a href="javascript:window.print()">Print This Page</a>
</div>
<?php wp_reset_query(); ?>
</html>