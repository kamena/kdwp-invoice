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
    $invoice_item_column_number = get_post_meta( $invoiceID, 'invoice_item_column_number', true );
    $kdwp_filepath = dirname( __FILE__ ) .'/templates/kdwp-invoice_template_'.$chosen_template.'.php';
    require_once($kdwp_filepath); 
?>
    <a href="javascript:window.print()">Print This Page</a>
</div>
<?php wp_reset_query(); ?>
</html>