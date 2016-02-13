<?php
class KDWP_Invoice_Class {
    public function __construct() {
        add_action( 'init', array( $this, 'create_invoice' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );

        //add action to call ajax
        add_action( 'wp_ajax_chosen_customer_info', array( $this, 'chosen_customer_info' ));
        add_action( 'wp_ajax_nopriv_chosen_customer_info',array( $this,  'chosen_customer_info' ));
    }

    public function create_invoice() {
        register_post_type( 'invoice',
            array(
                'labels' => array( 
                    'name' => 'Фактури',
                    'singular_name' => 'Фактури',
                    'add_new' => 'Добави нова фактура',
                    'add_new_item' => 'Добави нова фактура',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Invoice',
                    'new_item' => 'Нова фактура',
                    'view' => 'View PDF',
                    'view_item' => 'View Invoice',
                    'search_items' => 'Search Invoice',
                    'not_found' => 'No Invoices found',
                    'not_found_in_trash' => 'No Invoices found in Trash',
                    'parent' => 'Parent Invoice'
                ),
     
                'public' => true,
                'menu_position' => 15,
                'supports' => array( 'title' ),
                'taxonomies' => array( '' ),
                'menu_icon' => plugins_url( '../images/invoice-icon.png', __FILE__ ),
                'has_archive' => true
            )
        );
        // Taxonomies
        $labels = array(
            'name' => _x( 'Кол. мярка', 'taxonomy general name' ),
            'singular_name' => _x( 'Мярка за количество на продуктите', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search measure' ),
            'popular_items' => __( 'Popular' ),
            'all_items' => __( 'All' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit' ),
            'update_item' => __( 'Update' ),
            'add_new_item' => __( 'Добави нова мярка' ),
            'new_item_name' => __( 'Нова мярка' ),
        ); 

        register_taxonomy('measure','invoice',array(
            'hierarchical' => false,
            'labels' => $labels,
            'public' => true,
            'show_tagcloud' => true,
            'show_ui' => true,
            'query_var' => 'measure',
            'rewrite' => array( 'slug' => 'measure' ),
        ));

        wp_insert_term('бр.', 'measure');
        wp_insert_term('кг.', 'measure');

        $labelsStatus = array(
            'name' => __( 'Статус на фактурата' ),
            'singular_name' => __( 'Статус' ),
            'search_items' =>  __( 'Намери статус' ),
            'popular_items' => __( 'Популярни статуси' ),
            'all_items' => __( 'All' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __( 'Edit' ),
            'update_item' => __( 'Update' ),
            'add_new_item' => __( 'Добави нов статус' ),
            'new_item_name' => __( 'Нов статус' ),
        ); 

        register_taxonomy('status','invoice',array(
            'hierarchical' => false,
            'labels' => $labelsStatus,
            'public' => true,
            'show_tagcloud' => true,
            'show_ui' => true,
            'query_var' => 'status',
            'rewrite' => array( 'slug' => 'status' ),
        ));

        wp_insert_term('Платена', 'status');
        wp_insert_term('Неплатена', 'status');

        $labelsPayment = array(
            'name'              => __( 'Методи за заплащане' ),
            'singular_name'     => __( 'Метод' ),
            'search_items'      => __( 'Намери метод' ),
            'all_items'         => __( 'Вс. методи' ),
            'edit_item'         => __( 'Edit' ),
            'update_item'       => __( 'Update' ),
            'add_new_item'      => __( 'Добави нов метод' ),
            'new_item_name'     => __( 'Нов метод' ),
            'menu_name'         => __( 'Методи за заплащане' ),
        );

        register_taxonomy('payment','invoice',array(
            'hierarchical' => false,
            'labels' => $labelsPayment,
            'public' => true,
            'show_tagcloud' => true,
            'show_ui' => true,
            'query_var' => 'payment',
            'rewrite' => array( 'slug' => 'payment' ),
        ));

        wp_insert_term('В брой', 'payment');
        wp_insert_term('По банков път', 'payment');
    }

    public function my_admin() {
        remove_meta_box( 'tagsdiv-measure', 'invoice', 'side' );
        remove_meta_box( 'tagsdiv-payment', 'invoice', 'side' );
        add_meta_box( 'dropdown-client', __( 'Choose client', 'kdwp-invoicer' ), array( $this, 'client_metabox' ), 'invoice', 'normal', 'high' );
        add_meta_box( 'the-date', __( 'The Date', 'kdwp-invoicer' ), array( $this, 'the_date_display' ), 'invoice', 'side', 'low' );
        add_meta_box( 'the-template', __( 'Invoice Template', 'kdwp-invoicer' ), array( $this, 'choose_template' ), 'invoice', 'side', 'low' );
        add_meta_box( 'kdwp-serial-num', __( 'Invoice Serial Number', 'kdwp-invoicer' ), array( $this, 'invoice_serial_number' ), 'invoice', 'side', 'high');
        add_meta_box( 'kdwp-payment-methods', __( 'Методи за заплащане', 'kdwp-invoicer' ), array( $this, 'invoice_payment_methods' ), 'invoice', 'side', 'low');
    }

    public function chosen_customer_info() {
        $customer_chosen = isset( $_POST['chosen_client'] ) ? $_POST['chosen_client'] : "";

        $customer_name = get_post_meta( $customer_chosen, 'company_name', true );
        $customer_city = get_post_meta( $customer_chosen, 'company_city', true );
        $company_address = get_post_meta( $customer_chosen, 'company_address', true );
        $company_id = get_post_meta( $customer_chosen, 'company_id', true );
        $responsible_person = get_post_meta( $customer_chosen, 'responsible_person', true );
        $client_name = get_post_meta( $customer_chosen, 'client_name', true );
        $company_mail = get_post_meta( $customer_chosen, 'company_mail', true );

        echo $customer_chosen . "~" . $customer_name . "~" . $customer_city . "~" . $company_address . "~" . $company_id . "~" . $responsible_person . "~" . $client_name . "~" . $company_mail;

        exit;
    }

    public function client_metabox( $post ) {
        global $wpdb;

        $args = array(
            'orderby'          => 'date',
            'order'            => 'DESC',
            'post_type'        => 'client',
            'post_status'      => 'publish',
            'suppress_filters' => true 
        ); 

        $chosen_client = get_post_meta( $post->ID, 'chosen_client', true );
        $all_clients = get_posts( $args );
        ?>
        <p>
            <label>Client: </label>
            <select name="the_client" id="clients_list">
                <option value=""> </option>
                 <?php  foreach ($all_clients as $client): ?>
                    <option value="<?php echo $client->ID; ?>"<?php echo selected( $client->ID, esc_html( $chosen_client ) ); ?>> 
                        <?php echo $client->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <script type="text/javascript" >
        jQuery('#clients_list').on('change', function($){
            var data = {
                action: 'chosen_customer_info',
                chosen_client: this.value
            };

            jQuery.post(ajaxurl, data, function(response) {
                var res = response.split("~");
                jQuery("input#user_information_company_name").val(res[1]);
                jQuery("input#user_information_company_city").val(res[2]);
                jQuery("textarea#user_information_company_address").val(res[3]);
                jQuery("input#user_information_company_id").val(res[4]);
                jQuery("input#user_information_responsible_person").val(res[5]);
                jQuery("input#user_information_client_name").val(res[6]);
                jQuery("input#user_information_company_mail").val(res[7]);
            });
        });
        </script>
        <label>Име на фирмата</label>
        <div><input id="user_information_company_name" name="user_information_company_name" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_name', true ) ); ?>" size="8"></div>        
        <label>Град</label>
        <div><input id="user_information_company_city" name="user_information_company_city" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_city', true ) ); ?>" size="8"></div>
        <label>Адрес на фирмата</label>
        <div><textarea id="user_information_company_address" name="user_information_company_address" rows="5" cols="50" ><?php echo esc_html( get_post_meta( $chosen_client, 'company_address', true ) ); ?></textarea></div>
        <label>ЕИК/Булстат</label>
        <div><input id="user_information_company_id" name="user_information_company_id" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_id', true ) ); ?>" size="8"></div>
        <label>МОЛ</label>
        <div><input id="user_information_responsible_person" name="user_information_responsible_person" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'responsible_person', true ) ); ?>" size="8"></div>
        <label>Име на получател</label>
        <div><input id="user_information_client_name" name="user_information_client_name" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'client_name', true ) ); ?>" size="8"></div>
        <label>E-mail на фирмата</label>
        <div><input id="user_information_company_mail" name="user_information_company_mail" type="email" spellcheck="false" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_mail', true ) ); ?>" maxlength="255"> </div>
<?php   
    }

    public function the_date_display( $post ) {
        // wp_nonce_field( plugin_basename( __FILE__ ), 'wp-jquery-date-picker-nonce' ); 
        $chosen_date = get_post_meta( $post->ID, 'chosen_date', true);
        echo '<input id="datepicker" type="text" name="the_date" value="' . esc_html( $chosen_date ) . '" />';
    }

    public function choose_template( $post ) { 
        $chosen_template = get_post_meta( $post->ID, 'chosen_template', true ); ?>

        <select  name="the_template" id="templates_list">
        <?php 
        $number_templates = 2;
        for ($i = 1; $i <= $number_templates; $i++) { ?>
            <option value="<?php echo $i; ?>"<?php echo selected( $i, esc_html( $chosen_template ) ); ?>> 
                <?php echo 'kdwp-invoice_template_'.$i; ?>
            </option>
        <?php } ?>
        </select>
    <?php }

    public function invoice_serial_number( $post ) {
        $invoice_serial_number = get_post_meta( $post->ID, 'invoice_serial_number', true );
        if ( get_post_status ( $post->ID ) != 'publish' && isset( $invoice_serial_number ) ) {
            $prev_post = get_previous_post();
            if ( !empty( $prev_post ) ): 
                $kdwp_invoice_options = get_option( 'kdwp_invoice_options' );
                $prev_post_serial_num = get_post_meta( $prev_post->ID, 'invoice_serial_number', true );
                $kdwp_serial_number = isset($kdwp_invoice_options['kdwp_serial_number']) ? $kdwp_invoice_options['kdwp_serial_number'] : "1";

                $invoice_serial_number = $prev_post_serial_num + $kdwp_serial_number;     
            else: 
                $invoice_serial_number = 1;
            endif; 
            update_post_meta( $post->ID, 'invoice_serial_number', $invoice_serial_number );
        }
        echo "№ " . str_pad($invoice_serial_number, 10, "0", STR_PAD_LEFT);
    }

    public function invoice_payment_methods( $post ) {
            $chosen_payment_method = get_post_meta( $post->ID, 'kdwp_payment_method', true );
            $methods = get_terms('payment', 'hide_empty=0');     
        ?>
        <select name="method" id="method">
             <?php  foreach ($methods as $method): ?>
                <option value="<?php echo $method->name; ?>"<?php echo selected( $method->name, esc_html( $chosen_payment_method ) ); ?>> 
                    <?php echo $method->name; ?>
                </option>
            <?php endforeach; ?>
        </select>   
        <?php    
    }

    
    public function add_invoice_fields( $invoice_id ) {
        // @TODO - if not empty
        if ( isset( $_POST['the_client'] ) && $_POST['the_client'] != '' ) {
            // @TODO - escape
            update_post_meta( $invoice_id, 'chosen_client', esc_html( $_POST['the_client'] ) );
        }
        if ( isset( $_POST['the_date'] ) && $_POST['the_date'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_date', $_POST['the_date'] );
        }
        if ( isset( $_POST['the_template'] ) && $_POST['the_template'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_template', $_POST['the_template'] );
        }
        if ( isset( $_POST['invoice_chosen_client_id'] ) && $_POST['invoice_chosen_client_id'] != '' ) {
            update_post_meta( $invoice_id, 'invoice_chosen_client_id' , esc_html( $_POST['invoice_chosen_client_id'] ) );
        }
        if ( isset( $_POST['method'] ) ) {
            update_post_meta( $invoice_id, 'payment_method' , $_POST['method'] );
        }
    }
}
new KDWP_Invoice_Class();