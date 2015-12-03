<?php
 /*Template Name: New Template
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
    <?php

    $post = get_post( $post_id );
    // Customer company info
    $chosen_client = esc_html( get_post_meta( get_the_ID(), 'chosen_client', true ) );
    $company_name = get_post_meta( $chosen_client, 'company_name', true );
    $company_city = get_post_meta( $chosen_client, 'company_city', true );
    $company_address = get_post_meta( $chosen_client, 'company_address', true );
    $company_id = get_post_meta( $chosen_client, 'company_id', true );
    $responsible_person = get_post_meta( $chosen_client, 'responsible_person', true );

    $invoiceID = get_the_ID();
    $invoice_item_column_number = get_post_meta( $invoiceID, 'invoice_item_column_number', true );
    $kdwp_filepath = dirname( __FILE__ ) .'/templates/kdwp-invoice_template_1.php';
    require_once($kdwp_filepath); 
?>
    <a href="javascript:window.print()">Print This Page</a>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>