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
include( plugin_dir_path( __FILE__ ) . 'invoice-item-form.class.php');


class KDWPinvoice {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));
        add_action( 'init', array( $this, 'create_invoice' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );
        add_filter( 'template_include', array( $this, 'include_template_function' ), 1 );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/css/style.css', __FILE__ ), false, '1.0.0' );
        wp_enqueue_style( 'print_css', plugins_url('/css/print.css', __FILE__ ), false, '1.0.0', 'print' );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-datepicker', plugins_url('/js/datepicker.js', __FILE__ ), array( 'jquery' ), '', true );
        // wp_enqueue_script( 'jquery-dynamic-table', plugins_url('/js/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
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

    public function client_metabox( $post ) {

        $args = array(
            'posts_per_page'   => 200,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'client',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'author'       => '',
            'post_status'      => 'publish',
            'suppress_filters' => true 
        ); 

        $chosen_client = esc_html( get_post_meta( $post->ID, 'chosen_client', true ));
        $all_clients = get_posts( $args );
        // $invoice_chosen_client_id = 0;
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
            <!-- <input type="button" value="Export data" /> -->
        </p>
<script>
// jQuery('#clients_list').on('change', function($){
//     if(this.value != "") {
//         alert(this.value);
//     }
    // $.ajax({
    //     method: "GET",
    //     url: ajax_url,
    //     dataType: "text",
    //     data:{
    //         'action': 'wp_postmeta',
    //         'ID': post_id,
    //         'meta_key': company_name,
    //         'meta_value': meta_value
    //     },
    //     success: function( data ) {
    //         $( '.message' )
    //         .addClass( 'success' )
    //         .html( data );
    //     },
    //     error: function() {
    //         $( '.message' )
    //         .addClass( 'error' )
    //         .html( data );
    //     } 
    // });
    // success: function( data ) {
    //     if( data.status == 'error' ) {
    //         alert(data.message);
    //     } else {
    //         // same as above but with success
    //     }
    // },
// });

</script>
<?php
// function wp_postmeta() {

//     $post['ID'] = $_POST['ID'];
//     $post['meta_key'] = $_POST['company_name'];
//     $post['meta_value'] = 'meta_value';

//     $id = wp_update_post( $post, true );

//     $response = array();

//     if ( $id == 0 ) {
//         $response['status'] = 'error';
//         $response['message'] = 'This failed';
//     } else {
//         $response['status'] = 'success';
//         $response['message'] = 'This was successful';
//     }

//     echo json_encode($response);
// }
?>
        <input id="invoice_chosen_client_id" name="invoice_chosen_client_id" value="<?php echo $chosen_client; ?>" disabled/>
        <label>Име на фирмата</label>
        <div><input name="user_information_company_name" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_name', true ) ); ?>" size="8"></div>        
        <label>Град</label>
        <div><input name="user_information_company_city" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_city', true ) ); ?>" size="8"></div>
        <label>Адрес на фирмата</label>
        <div><textarea name="user_information_company_address" rows="5" cols="50" ><?php echo esc_html( get_post_meta( $chosen_client, 'company_address', true ) ); ?></textarea></div>
        <label>ЕИК/Булстат</label>
        <div><input name="user_information_company_id" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_id', true ) ); ?>" size="8"></div>
        <label>МОЛ</label>
        <div><input name="user_information_responsible_person" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'responsible_person', true ) ); ?>" size="8"></div>
        <label>Име на получател</label>
        <div><input name="user_information_client_name" type="text" value="<?php echo esc_html( get_post_meta( $chosen_client, 'client_name', true ) ); ?>" size="8"></div>
        <label>E-mail на фирмата</label>
        <div><input name="user_information_company_mail" type="email" spellcheck="false" value="<?php echo esc_html( get_post_meta( $chosen_client, 'company_mail', true ) ); ?>" maxlength="255"> </div>

<script>
jQuery('#clients_list').on('change', function() {
        // alert(this.value);
        jQuery('input#invoice_chosen_client_id').val(this.value);
});
</script>
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
}

new KDWPinvoice();  

?>