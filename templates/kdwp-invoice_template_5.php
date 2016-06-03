
<style type="text/Css">
<!--
.paper {
	background: #F5F5F5;
}
table {
	width: 100% !important;
}
.table-border, .table-border th, .table-border td {
    border: 1px solid black;
}
.table-no-border, .table-no-border th, .table-no-border td {
    border: 0px !important;
}
-->
</style>

<link rel="stylesheet" type="text/css" href="<?php  echo KDWP_TEMP_URL ?>/assets/css/master.css" />
<link rel="stylesheet" type="text/css" media="print" href="<?php  echo KDWP_TEMP_URL ?>/assets/css/print.css" />

<page>
	<div class="container paper">
	    <table class="table table-border">
	    	<tr>
                <td class="two">
                    <h4>ПОЛУЧАТЕЛ</h4>
                    Име: <?php echo $company_name; ?><br/>
                    Град: <?php echo $company_city; ?><br/>
                    Адрес: <?php echo $company_address; ?><br/>
                    ЕИК: <?php echo $company_id; ?><br/>
                    МОЛ: <?php echo $responsible_person; ?><br/>
	            </td>
                <td class="two">
                    <h4>ДОСТАВЧИК</h4>
                    Име: <?php echo $kdwp_company_name; ?><br/>
                    Град: <?php echo $kdwp_company_city; ?><br/>
                    Адрес: <?php echo $kdwp_company_address; ?><br/>
                    ЕИК: <?php echo $kdwp_company_unique_number; ?><br/>
                    МОЛ: <?php echo $kdwp_company_person; ?><br/>
                </td>
            </tr>	        
	    </table>
        <table class="table table-border">           
            <tr>
                <td class="two"><h2 class="text-left">ОРИГИНАЛ</h2></td>
                <td class="two">
                    <p class="text-right">Номер: <?php echo "№ " . str_pad($invoice_serial_number, 10, "0", STR_PAD_LEFT); ?></p>
                    <p class="text-right">Дата: <?php echo $chosen_date; ?></p>
                </td>
            </tr>                           
        </table>
		<table class="table-margin table-border">

            <tr>
                <th>№</th>
                <th class="big">СТОКИТЕ / УСЛУГИТЕ</th>
                <th>КОЛ-ВО</th>
                <th>МЯРКА</th>
                <th>ЕД. ЦЕНА</th>
                <th>СТОЙНОСТ</th>
            </tr>
            <?php 
                $number = 1;
                for($i = 0; $i <= $invoice_item_column_number; $i++) { ?>    
                    <tr>
                    <?php if ($i < $invoice_item_column_number) {
                        if ( get_post_meta( $post->ID, 'name'.$i, true ) == '' ||  get_post_meta( $post->ID, 'quantity'.$i , true ) == '' || 
                         get_post_meta( $post->ID, 'measure'.$i, true ) == '' ||  get_post_meta( $post->ID, 'price'.$i , true ) == '' ) {
                        $i++;
                        } else { ?>
                            <td><?php echo $number; $number++?></td>
                            <td class="two"><?php echo get_post_meta( $invoiceID, 'name'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'quantity'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'measure'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ) * get_post_meta( $invoiceID, 'quantity'.$i, true ); ?></td>
                        
                    <?php } 
                    }?>
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