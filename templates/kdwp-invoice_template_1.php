<div class="container">
    <!-- table-hover -->
    <table class="table" data-post-id="<?php echo $post->ID; ?>">
        <tbody>
            <td>
                <table class="table">
                    <thead><th>ПОЛУЧАТЕЛ</th></thead>
                    <tbody>
                        <tr><td>Име: <?php echo $company_name; ?></td></tr>
                        <tr><td>Град: <?php echo $company_city; ?></td></tr>
                        <tr><td>Адрес: <?php echo $company_address; ?></td></tr>
                        <tr><td>ЕИК: <?php echo $company_id; ?></td></tr>
                        <tr><td>МОЛ: <?php echo $responsible_person; ?></td></tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table>
                    <thead><th text-align="center"><?php echo "№ " . str_pad($invoice_serial_number, 10, "0", STR_PAD_LEFT); ?></th></thead>
                    <tbody>
                        <tr><td>Дата: 21.11.2015г.</td></tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table class="table">
                    <thead><th>ДОСТАВЧИК</th></thead>
                    <tbody>
                        <tr><td>Име: <?php echo $kdwp_company_name; ?></td></tr>
                        <tr><td>Град: <?php echo $kdwp_company_city; ?></td></tr>
                        <tr><td>Адрес: <?php echo $kdwp_company_address; ?></td></tr>
                        <tr><td>ЕИК: <?php echo $kdwp_company_unique_number; ?></td></tr>
                        <tr><td>МОЛ: <?php echo $kdwp_company_person; ?></td></tr>
                    </tbody>
                </table>
            </td>
            <table class="table table-hover">

                <thead>
                    <th width="2%">№</th>
                    <th>СТОКИТЕ / УСЛУГИТЕ</th>
                    <th>КОЛ-ВО</th>
                    <th>МЯРКА</th>
                    <th>ЕД. ЦЕНА</th>
                    <th>СТОЙНОСТ</th>
                </thead>
                <tbody>
                <?php for($i = 0; $i <= $invoice_item_column_number; $i++) { ?>    

                        <tr>
                        <?php if ( get_post_meta( $post->ID, 'name'.$i, true ) == '' &&  get_post_meta( $post->ID, 'quantity'.$i , true ) == '' && 
                             get_post_meta( $post->ID, 'measure'.$i, true ) == '' &&  get_post_meta( $post->ID, 'price'.$i , true ) == '' &&
                             $i < $invoice_item_column_number ) {
                            $i++;
                        } else { ?>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'name'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'quantity'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'measure'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </tbody>
    </table>
</div>