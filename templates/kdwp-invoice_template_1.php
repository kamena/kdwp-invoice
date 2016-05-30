<style type="text/Css">
<!--
.paper {
    border: none !important;
    margin-top: 0px !important;
}
-->
</style>

<link rel="stylesheet" type="text/css" href="<?php  echo KDWP_TEMP_URL ?>/assets/css/master.css" />
<link rel="stylesheet" type="text/css" media="print" href="<?php  echo KDWP_TEMP_URL ?>/assets/css/print.css" />

<page>
	<div class="container paper">
	    <table class="table">
	    	<tr>
                <td class="three">
                	<table class="table table-condensed">
	                    <tr><th>ПОЛУЧАТЕЛ</th></tr>
                        <tr><td>Име: <?php echo $company_name; ?></td></tr>
                        <tr><td>Град: <?php echo $company_city; ?></td></tr>
                        <tr><td>Адрес: <?php echo $company_address; ?></td></tr>
                        <tr><td>ЕИК: <?php echo $company_id; ?></td></tr>
                        <tr><td>МОЛ: <?php echo $responsible_person; ?></td></tr>
	                </table>
	            </td>
                <td class="three centrate">
	                <h2 class="text-center text-danger number"><?php echo "№ " . str_pad($invoice_serial_number, 10, "0", STR_PAD_LEFT); ?></h2>
	                <p class="text-center">Дата: <?php echo $chosen_date; ?></p>
	                <h3 class="text-center">ОРИГИНАЛ</h3>
	            </td>
                <td class="three">
	                <table class="table table-condensed">
	                    <tr><th>ДОСТАВЧИК</th></tr>
                        <tr><td>Име: <?php echo $kdwp_company_name; ?></td></tr>
                        <tr><td>Град: <?php echo $kdwp_company_city; ?></td></tr>
                        <tr><td>Адрес: <?php echo $kdwp_company_address; ?></td></tr>
                        <tr><td>ЕИК: <?php echo $kdwp_company_unique_number; ?></td></tr>
                        <tr><td>МОЛ: <?php echo $kdwp_company_person; ?></td></tr>
	                </table>
                </td>
            </tr>	        
	    </table>
		<table class="table-margin">

            <tr>
                <th>№</th>
                <th class="two">СТОКИТЕ / УСЛУГИТЕ</th>
                <th>КОЛ-ВО</th>
                <th>МЯРКА</th>
                <th>ЕД. ЦЕНА</th>
                <th>СТОЙНОСТ</th>
            </tr>
            <?php 
                $number = 1;
                for($i = 0; $i <= $invoice_item_column_number; $i++) { ?>    

                    <tr class="row-xs-10">
                    <?php if ( get_post_meta( $post->ID, 'name'.$i, true ) == '' &&  get_post_meta( $post->ID, 'quantity'.$i , true ) == '' && 
                         get_post_meta( $post->ID, 'measure'.$i, true ) == '' &&  get_post_meta( $post->ID, 'price'.$i , true ) == '' &&
                         $i < $invoice_item_column_number ) {
                        $i++;
                    } else { ?>
                        <td><?php echo $number; $number++?></td>
                        <td class="two"><?php echo get_post_meta( $invoiceID, 'name'.$i, true ); ?></td>
                        <td><?php echo get_post_meta( $invoiceID, 'quantity'.$i, true ); ?></td>
                        <td><?php echo get_post_meta( $invoiceID, 'measure'.$i, true ); ?></td>
                        <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                        <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                    
                <?php } ?>
                </tr>
            <?php } ?>
            <tr>
                <th rowspan="4" colspan="4">Словом: <?php echo get_post_meta( $invoiceID, 'num_in_string', true ); ?></th>
            </tr>
            <tr>
                <th>Общо</th>
                <td><?php echo get_post_meta( $invoiceID, 'total_price', true ); ?></td>
            </tr>
            <tr>
                <th>ДДС ( % )</th>
                <td><?php echo get_post_meta( $invoiceID, 'vat', true ); ?></td>
            </tr>
            <tr>
                <th>Всичко</th>
                <td><?php echo get_post_meta( $invoiceID, 'all_price', true ); ?></td>
            </tr>
        </table>

        <table class="table">
        	<tr>
		        <td rowspan="4" class="two">
		            <h4>Начин на плащане: <?php echo $kdwp_payment_method; ?></h4>
		        </td>
		        <td class="two">
		            <p><b>Банка:</b> <?php echo $kdwp_bank_name; ?></p>
		            <p><b>IBAN:</b> <?php echo $kdwp_company_iban; ?></p>
		            <p><b>BIC:</b> <?php echo $kdwp_company_bic; ?></p>
		            <p><b>Съставител:</b> <?php echo $kdwp_invoice_author; ?></p>
		            <p><b>Подпис:</b> .................................</p>
		        </td>
		    </tr>
	    </table>
	    <p>&nbsp;</p>
	    <hr class="no_margins">
	    <small><?php echo $kdwp_invoice_note; ?></small>
</div>
</page>