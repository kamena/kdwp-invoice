<?php

class InvoiceItemForm {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));        
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
    }

    public function register_admin_plugin_scripts() {
        // wp_enqueue_style( 'boostrap', plugins_url('../assets/css/bootstrap.css', __FILE__ ), false, '1.0.0' );
        // wp_enqueue_style( 'bootstrap-min', plugins_url('../assets/css/bootstrap.min.css', __FILE__ ), false, '1.0.0' );
    }    

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-dynamic-table', plugins_url('../assets/scripts/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
    }

    public function my_admin() {
        add_meta_box( 'item-table', __( 'Items', 'kdwp-invoicer' ), array($this, 'item_table_metabox'), 'invoice', 'normal', 'high');
    }

    public function item_table_metabox( $invoice ) {
        $rows_number = 0;
?>                

<div class="container">
    <table id="tab_logic">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Продукт</th>
                <th class="text-center">Количество</th>
                <th class="text-center">Мярка</th>
                <th class="text-center">Ед. цена</th>
                <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;"></th>
            </tr>
        </thead>
        <tbody >
        <?php
			$try = get_post_meta( $invoice->ID, 'invoice_item_column_number', true );
            for($i = 0; $i < $try; $i++) {
            ?>
                <tr>
                    <input type="hidden" id="isRow" name="isRow" value="<?php echo $try ?>" />
                    <td><input id="numberItem" type="number" name="num<?php echo $i; ?>" value="<?php echo $i+1 ?>" style="width: 50px" /></td>
                    <td>
                        <input type="text" name='name<?php echo $i; ?>'  placeholder='Продукт' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'name'.$i, true ); ?>"/>
                    </td>
                    <td>
                        <input type="text" name='quantity<?php echo $i; ?>' placeholder='Количество' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'quantity'.$i, true ); ?>"/>
                    </td>
                    <td>
                        <select>
                            <option value"">Мярка</option>
                            <option value"1">бр.</option>
                            <option value"2">кг.</option>
                        </select>
                        <!-- <input type="text" name='quantity<?php echo $i; ?>' placeholder='Мярка' class="form-control"/> -->
                    </td>
                    <td>
                        <input type="text" name='price<?php echo $i; ?>' placeholder='Ед. цена' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'price'.$i, true ); ?>"/>
                    </td>
                    <td>
                        <button name="del<?php echo $i; ?>" class='btn btn-danger row-remove'>x</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <input type="hidden" id="invoice_item_column_number" name="invoice_item_column_number" value="<?php echo $rows_number; ?>" />
    <a id="add_row" class="btn btn-default pull-right">Add Row</a>
</div>

<?php }

    public function add_invoice_fields( $invoice_id) {
        if ( isset( $_POST['invoice_item_column_number'] ) && $_POST['invoice_item_column_number'] != '' ) {
            update_post_meta( $invoice_id, 'invoice_item_column_number' , $_POST['invoice_item_column_number'] );
        }
        $rows = !empty( $_POST['invoice_item_column_number'] ) ? (int) $_POST['invoice_item_column_number'] : 0;
        $num_row = 0;
        
        $i = 0;
        while ($num_row < $rows) {
            $boolean = 0;

            if ( isset( $_POST['isRow'] ) ) $boolean = 1;

            if ( isset( $_POST['name'.$i] ) ) {
                update_post_meta( $invoice_id, 'name'.$i , $_POST['name'.$i] );
            }
            if ( isset( $_POST['quantity'.$i] ) ) {
                update_post_meta( $invoice_id, 'quantity'.$i , $_POST['quantity'.$i] );
            }
            if ( isset( $_POST['measure'.$i] ) ) {
                update_post_meta( $invoice_id, 'measure'.$i , $_POST['measure'.$i] );
            }
            if ( isset( $_POST['price'.$i] ) ) {
                update_post_meta( $invoice_id, 'price'.$i , $_POST['price'.$i] );
            }
            if( $boolean === 1 ) $num_row++;
            $i++;          
        }
        
    }
}
new InvoiceItemForm();

?>