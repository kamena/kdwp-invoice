<?php

class InvoiceItemForm {

    public function __construct() {
        // add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));        
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'boostrap', plugins_url('../assets/css/bootstrap.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'bootstrap-min', plugins_url('../assets/css/bootstrap.min.css', __FILE__ ), false, '1.0.0' );
    }    

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-dynamic-table', plugins_url('/../assets/scripts/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
    }

    public function my_admin() {
        add_meta_box( 'item-table', __( 'Продукти', 'kdwp-invoicer' ), array($this, 'item_table_metabox'), 'invoice', 'normal', 'high');
    }

    public function item_table_metabox( $invoice ) {
        $rows_number = 0;
        ?>                

        <div class="container">
            <table id="tab_logic">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center"><?php _e('Продукт', 'kdwpinvoice'); ?></th>
                        <th class="text-center"><?php _e('Количество', 'kdwpinvoice'); ?></th>
                        <th class="text-center"><?php _e('Мярка', 'kdwpinvoice'); ?></th>
                        <th class="text-center"><?php _e('Ед. цена', 'kdwpinvoice'); ?></th>
                        <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;"></th>
                    </tr>
                </thead>
                <tbody >
                <?php
        			$numberColumns = get_post_meta( $invoice->ID, 'invoice_item_column_number', true );
                    $rows_number = $numberColumns;
                    for($i = 0; $i <= $numberColumns; $i++) {
                    if ( get_post_meta( $invoice->ID, 'name'.$i, true ) == '' &&  get_post_meta( $invoice->ID, 'quantity'.$i , true ) == '' && 
                         get_post_meta( $invoice->ID, 'measure'.$i, true ) == '' &&  get_post_meta( $invoice->ID, 'price'.$i , true ) == '' &&
                         $i < $numberColumns ) {
                        $i++;
                    } else {           
                    ?>
                        <tr id="<?php echo $i; ?>">
                            <input type="hidden" id="isRow" name="isRow" value="<?php echo $numberColumns ?>" />
                            <td><input id="numberItem" type="number" name="num<?php echo $i; ?>" value="<?php echo $i+1 ?>" style="width: 50px" /></td>
                            <td>
                                <input type="text" name='name<?php echo $i; ?>'  placeholder='Продукт' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'name'.$i, true ); ?>"/>
                            </td>
                            <td>
                                <input type="text" name='quantity<?php echo $i; ?>' placeholder='Количество' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'quantity'.$i, true ); ?>"/>
                            </td>
                            <td> 
                            <?php
                                $chosen_measure = get_post_meta( $invoice->ID, 'measure'.$i, true );
                                $measures = get_terms('measure', 'hide_empty=0');     
                            ?>
                            <select name="measure<?php echo $i; ?>" id="measure<?php echo $i; ?>">
                                 <?php  foreach ($measures as $measure): ?>
                                    <option value="<?php echo $measure->name; ?>"<?php echo selected( $measure->name, esc_html( $chosen_measure ) ); ?>> 
                                        <?php echo $measure->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                          </td>
                            <td>
                                <input type="text" name='price<?php echo $i; ?>' placeholder='Ед. цена' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'price'.$i, true ); ?>"/>
                            </td>
                            <td>
                                <button name="del<?php echo $i; ?>" class='btn btn-danger row-remove'>x</button>
                            </td>
                        </tr>
                    <?php }
                } ?>
                </tbody>

            </table>

            <input type="hidden" id="invoice_item_column_number" name="invoice_item_column_number" value="<?php echo $rows_number; ?>" />
            <a id="add_row" class="btn btn-default pull-right"><?php _e('Добави продукт', 'kdwpinvoice'); ?></a>
            </br>
            <label><?php _e('ДДС', 'kdwpinvoice'); ?></label>
            <input id="vat" type="number" name="vat" value="<?php echo get_post_meta( $invoice->ID, 'vat', true ) ?>" style="width: 50px" />
            <label>%</label>
        </div>
<?php }

    public function add_invoice_fields( $invoice_id ) {

        if ( isset( $_POST['invoice_item_column_number'] ) && $_POST['invoice_item_column_number'] != '' ) {
            update_post_meta( $invoice_id, 'invoice_item_column_number' , $_POST['invoice_item_column_number'] );
        }
        $rows = !empty( $_POST['invoice_item_column_number'] ) ? (int) $_POST['invoice_item_column_number'] : 0;
        
        $i = 0;
        $no_more = 0;
        $total_price = 0;
        while ( $no_more == 0 ) {

            if ( isset( $_POST['name'.$i] ) ) {
                update_post_meta( $invoice_id, 'name'.$i , $_POST['name'.$i] );
            } else if ( get_post_meta( $invoice_id, 'name'.$i, true ) != '' ) {
                delete_post_meta( $invoice_id, 'name'.$i , $_POST['name'.$i] );
            }
            if ( isset( $_POST['quantity'.$i] ) ) {
                if ( is_numeric( $_POST['quantity'.$i] ) ){
                    update_post_meta( $invoice_id, 'quantity'.$i , $_POST['quantity'.$i] );
                }
            } else if ( get_post_meta( $invoice_id, 'quantity'.$i, true ) != '' ) {
                delete_post_meta( $invoice_id, 'quantity'.$i , $_POST['quantity'.$i] );
            }
            if ( isset( $_POST['measure'.$i] ) ) {
                update_post_meta( $invoice_id, 'measure'.$i , $_POST['measure'.$i] );
            } else if ( get_post_meta( $invoice_id, 'measure'.$i, true ) != '' ) {
                delete_post_meta( $invoice_id, 'measure'.$i , $_POST['measure'.$i] );
            }
            if ( isset( $_POST['price'.$i] ) ) {
                if ( is_numeric( $_POST['price'.$i] ) ){
                    update_post_meta( $invoice_id, 'price'.$i , $_POST['price'.$i] );
                    if ( isset( $_POST['measure'.$i] ) ) {
                        $total_price += ( $_POST['quantity'.$i] * $_POST['price'.$i] );
                    } else {
                        $total_price += $_POST['price'.$i];
                    }
                    update_post_meta( $invoice_id, 'total_price', $total_price );
                } 
            } else if ( get_post_meta( $invoice_id, 'price'.$i, true ) != '' ) {
                delete_post_meta( $invoice_id, 'price'.$i , $_POST['price'.$i] );
            }

            if ( get_post_meta( $invoice_id, 'name'.$i, true ) == '' &&  get_post_meta( $invoice_id, 'quantity'.$i , true ) == '' && 
                 get_post_meta( $invoice_id, 'measure'.$i, true ) == '' &&  get_post_meta( $invoice_id, 'price'.$i , true ) == '' &&
                 $i >= $rows ) {
                $no_more = 1;
            }
            $i++; 
        }
        if ( isset( $_POST['vat'] ) ) {
            update_post_meta( $invoice_id, 'vat' , $_POST['vat'] );
            $all_price = $total_price + $_POST['vat'] * $total_price / 100;
            update_post_meta( $invoice_id, 'all_price' , $all_price );
            $num_in_string = num_string_convertor( $all_price );
        } else $num_in_string = num_string_convertor( $total_price );
        update_post_meta( $invoice_id, 'num_in_string' , $num_in_string );
    }
    
}
new InvoiceItemForm();

?>