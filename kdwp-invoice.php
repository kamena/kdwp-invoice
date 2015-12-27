<?php
/*
Plugin Name: KDWP-Invoice
Description: Declares a plugin that will create invoices.
Version: 1.0
Author: kamena
License: GPLv2
*/


include( plugin_dir_path( __FILE__ ) . 'custom_screen_options.php');
include( plugin_dir_path( __FILE__ ) . 'clients_custom_screen_options.php');
include( plugin_dir_path( __FILE__ ) . 'clients_menu.php');
include( plugin_dir_path( __FILE__ ) . 'settings_menu.php');
include( plugin_dir_path( __FILE__ ) . 'invoice-item-form.class.php');


class KDWPinvoice {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));
        add_action( 'init', array( $this, 'create_invoice' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );
        add_filter( 'template_include', array( $this, 'include_template_function' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

        add_action('admin_footer-post.php', array( $this, 'wptuts_add_my_meta_box') );
        // add_action( 'admin_footer', array( $this, 'my_admin_footer_function') ); // Write our JS below here
        // add_action( 'wp_ajax_my_action', array( $this, 'my_action_callback' ) );

                  //add action to call ajax
        add_action( 'wp_ajax_add_outlook_customer', array( $this, 'add_outlook_customer' ));
        add_action( 'wp_ajax_nopriv_add_outlook_customer',array( $this,  'add_outlook_customer' ));

        add_action( 'admin_init', array( $this, 'kdwp_invoice_admin_init' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/css/style.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'print_css', plugins_url('/css/print.css', __FILE__ ), false, '1.0.0', 'print' );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-datepicker', plugins_url('/js/datepicker.js', __FILE__ ), array( 'jquery' ), '', true );
        // wp_enqueue_script( 'jquery-dynamic-table', plugins_url('/js/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
    }

// add_action( 'admin_footer-post.php', 'my_post_edit_page_footer' );

function wptuts_add_my_meta_box(){
  echo "<p>This paragraph will be shown in footer of the post edit page.</p>";
}

public function wptuts_my_meta_box_callback() {
    echo "Metabox here";
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
                'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
                'taxonomies' => array( '' ),
                'menu_icon' => plugins_url( 'images/invoice-icon.png', __FILE__ ),
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
    }

    public function add_outlook_customer() {
        $customer_chosen = isset($_POST['whatever']) ? $_POST['whatever'] : "";
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

        $chosen_client = esc_html( get_post_meta( $post->ID, 'chosen_client', true ));
        $all_clients = get_posts( $args );
?>
        <p>
            <label>Client: </label>
            <select name="the_client" id="clients_list">
                <option value=""> </option>
                 <?php  foreach ($all_clients as $client): ?>
                    <option value="<?php echo $client->ID; ?>"<?php echo selected( $client->ID, $chosen_client ); ?>> 
                        <?php echo $client->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

    <script type="text/javascript" >
    jQuery('#clients_list').on('change', function($){
        var data = {
            action: 'add_outlook_customer',
            whatever: this.value
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
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
        $chosen_date = esc_html( get_post_meta( $post->ID, 'chosen_date', true));
        echo '<input id="datepicker" type="text" name="the_date" value="' . $chosen_date . '" />';
    }

    public function add_invoice_fields( $invoice_id) {
        if ( isset( $_POST['the_client'] ) && $_POST['the_client'] != '' ) 
            update_post_meta( $invoice_id, 'chosen_client', $_POST['the_client'] );
        if ( isset( $_POST['the_date'] ) && $_POST['the_date'] != '' )
            update_post_meta( $invoice_id, 'chosen_date', $_POST['the_date'] );
        if ( isset( $_POST['invoice_chosen_client_id'] ) && $_POST['invoice_chosen_client_id'] != '' ) {
            update_post_meta( $invoice_id, 'invoice_chosen_client_id' , $_POST['invoice_chosen_client_id'] );
        }
    }

    public function include_template_function( $template_path ) {
        if ( get_post_type() == 'invoice' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-invoces.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/single-invoces.php';
                }
            }
        }

        return $template_path;
    }

    public function kdwp_invoice_admin_init() {
        register_setting( 'invoice_plugin_options', 'kdwp_invoice_options' );
    }   
}

new KDWPinvoice();  

?>