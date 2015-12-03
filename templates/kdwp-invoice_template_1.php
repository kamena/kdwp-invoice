    <table >
        <tr>
            <td width="30%">
                <table>
                    <thead><th>ПОЛУЧАТЕЛ</th></thead>
                    <tbody>
                        <tr><td>Име: <?php echo $company_name; ?></td></tr>
                        <tr><td>Град: София</td></tr>
                        <tr><td>Адрес: ул. Минзухар</td></tr>
                        <tr><td>ЕИК: 201418410</td></tr>
                        <tr><td>МОЛ: Иван Иванов</td></tr>
                    </tbody>
                </table>
            </td>
            <td width="30%">
                <table >
                    <thead><th text-align="center">№0000000001</th></thead>
                    <tbody>
                        <tr><td>Дата: 21.11.2015г.</td></tr>
                    </tbody>
                </table>
            </td>
            <td width="30%">
                <table >
                    <thead><th>ДОСТАВЧИК</th></thead>
                    <tbody>
                        <tr><td>Име: <?php echo $company_name; ?></td></tr>
                        <tr><td>Град: </td></tr>
                        <tr><td>Адрес: </td></tr>
                        <tr><td>ЕИК: </td></tr>
                        <tr><td>МОЛ: </td></tr>
                    </tbody>
                </table>
            </td>
<!--         </tr>
        <tr> -->
            <table>

                <thead>
                    <th width="2%">№</th>
                    <th>НАИМЕНОВАНИЕ НА СТОКИТЕ / УСЛУГИТЕ</th>
                    <th>КОЛ-ВО</th>
                    <th>МЯРКА</th>
                    <th>ЕД. ЦЕНА</th>
                    <th>СТОЙНОСТ</th>
                </thead>
                <tbody>
<?php
                    for($i = 0; $i <= $invoice_item_column_number; $i++) {
?>                        
                        <tr>
                            <td><?php echo $i+1; ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'name'.$i, true ); ?></td>
                            <td><?php echo get_post_meta( $invoiceID, 'quantity'.$i, true ); ?></td>
                            <td>бр.</td>
                            <td><?php echo get_post_meta( $invoiceID, 'price'.$i, true ); ?></td>
                            <td>200лв.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </tr>
    </table>