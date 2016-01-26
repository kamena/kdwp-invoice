<?php
class KDWP_Invoice_Class {
    public function __construct() {
        add_action( 'init', array( $this, 'create_invoice' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );

        //add action to call ajax
        add_action( 'wp_ajax_add_outlook_customer', array( $this, 'add_outlook_customer' ));
        add_action( 'wp_ajax_nopriv_add_outlook_customer',array( $this,  'add_outlook_customer' ));
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

        register_taxonomy( 'Genres', array('invoice'), 
            array(
                'hierarchical' => true,
                'label' => 'Genres',
                'singular_label' => 'Genre',
                'rewrite' => true
            )
        );

    }

    public function my_admin() {
        add_meta_box( 'dropdown-client', __( 'Choose client', 'kdwp-invoicer' ), array( $this, 'client_metabox' ), 'invoice', 'normal', 'high' );
        add_meta_box( 'the-date', __( 'The Date', 'kdwp-invoicer' ), array( $this, 'the_date_display' ), 'invoice', 'side', 'low' );
        add_meta_box( 'the-template', __( 'Invoice Template', 'kdwp-invoicer' ), array( $this, 'choose_template' ), 'invoice', 'side', 'low' );
        add_meta_box( 'kdwp-serial-num', __( 'Invoice Serial Number', 'kdwp-invoicer' ), array( $this, 'invoice_serial_number' ), 'invoice', 'side', 'high');
    }

    public function add_outlook_customer() {
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
            action: 'add_outlook_customer',
            chosen_client: this.value
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
            var res = response.split("~");
            console.log(res[1]);
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

    
    public function add_invoice_fields( $invoice_id ) {
        // @TODO - if not empty
        if ( isset( $_POST['the_client'] ) && $_POST['the_client'] != '' ) {
            // @TODO - escape
            update_post_meta( $invoice_id, 'chosen_client', $_POST['the_client'] );
        }
        if ( isset( $_POST['the_date'] ) && $_POST['the_date'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_date', $_POST['the_date'] );
        }
        if ( isset( $_POST['the_template'] ) && $_POST['the_template'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_template', $_POST['the_template'] );
        }
        if ( isset( $_POST['invoice_chosen_client_id'] ) && $_POST['invoice_chosen_client_id'] != '' ) {
            update_post_meta( $invoice_id, 'invoice_chosen_client_id' , $_POST['invoice_chosen_client_id'] );
        }
    }
}
new KDWP_Invoice_Class();