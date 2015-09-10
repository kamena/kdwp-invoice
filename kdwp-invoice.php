<?php
/*
Plugin Name: Movie Reviews
Description: Declares a plugin that will create a custom post type displaying movie reviews.
Version: 1.0
Author: kamena
License: GPLv2
*/


include( plugin_dir_path( __FILE__ ) . 'custom_screen_options.php');
include( plugin_dir_path( __FILE__ ) . 'clients_custom_screen_options.php');
include( plugin_dir_path( __FILE__ ) . 'clients_menu.php');

class KDWPinvoice {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_plugin_scripts' ));
        add_action( 'init', array( $this, 'create_invoice' ));
        add_action( 'admin_init', array( $this, 'my_admin' ));
        add_action( 'save_post', array( $this, 'add_invoice_fields'), 10, 2 );
        add_filter( 'template_include', array( $this, 'include_template_function', 1 ));
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/css/style.css', __FILE__ ), false, '1.0.0' );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-datepicker', plugins_url('/js/datepicker.js', __FILE__ ), array( 'jquery' ), '', true );
        wp_enqueue_script( 'jquery-dynamic-table', plugins_url('/js/invoice_item_dynamic_table.js', __FILE__ ), array( 'jquery' ), '', true );        
    }

    public function create_invoice() {
        register_post_type( 'invoice',
            array(
                'labels' => array( 
                    'name' => 'Book Annotations',
                    'singular_name' => 'Book Annotation',
                    'add_new' => 'Add New Annotation',
                    'add_new_item' => 'Add New Book Annotation',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Book Annotation',
                    'new_item' => 'New Book Annotation',
                    'view' => 'View',
                    'view_item' => 'View Book Annotation',
                    'search_items' => 'Search Book Annotations',
                    'not_found' => 'No Book Annotations found',
                    'not_found_in_trash' => 'No Book Annotations found in Trash',
                    'parent' => 'Parent Book Annotation'
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
        add_meta_box( 'item-table', __( 'Items', 'kdwp-invoicer' ), array($this, 'item_table_metabox'), 'invoice', 'normal', 'high');
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
            <input type="button" value="Export data" />
        </p>
<script>
jQuery('#clients_list').on('change', function(){
    if(this.value === "") {
        alert(this.value);
    }
});

</script>
        <label>Име на фирмата</label>
        <div><input id="form1" name="user_information_company_name" type="text" value="<?php echo esc_html( get_post_meta( $client->ID, 'company_name', true ) ); ?>" size="8"></div>        
        <label>Град</label>
        <div><input name="user_information_company_city" type="text" value="<?php echo esc_html( get_post_meta( $client->ID, 'company_city', true ) ); ?>" size="8"></div>
        <label>Адрес на фирмата</label>
        <div><textarea name="user_information_company_address" rows="5" cols="50" ><?php echo esc_html( get_post_meta( $client->ID, 'company_address', true ) ); ?></textarea></div>
        <label>ЕИК/Булстат</label>
        <div><input name="user_information_company_id" type="text" value="<?php echo esc_html( get_post_meta( $client->ID, 'company_id', true ) ); ?>" size="8"></div>
        <label>МОЛ</label>
        <div><input name="user_information_responsible_person" type="text" value="<?php echo esc_html( get_post_meta( $client->ID, 'responsible_person', true ) ); ?>" size="8"></div>
        <label>Име на получател</label>
        <div><input name="user_information_client_name" type="text" value="<?php echo esc_html( get_post_meta( $client->ID, 'client_name', true ) ); ?>" size="8"></div>
        <label>E-mail на фирмата</label>
        <div><input name="user_information_company_mail" type="email" spellcheck="false" value="<?php echo esc_html( get_post_meta( $client->ID, 'company_mail', true ) ); ?>" maxlength="255"> </div>
<?php   
    }

    public function the_date_display( $post ) {
        // wp_nonce_field( plugin_basename( __FILE__ ), 'wp-jquery-date-picker-nonce' ); 
        $chosen_date = esc_html( get_post_meta( $post->ID, 'chosen_date', true));
        echo '<input id="datepicker" type="text" name="the_date" value="' . $chosen_date . '" />';
    }

    public function item_table_metabox( $invoice ) {
        $rows_number = 0;
        $company_name = esc_html( get_post_meta( $invoice->ID, 'product', true ) );
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
                    <tr>
                        <td><input type="number" name="num0" value="1" style="width: 50px" /></td>
                        <td>
                            <input type="text" name='name0'  placeholder='Продукт' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'name0', true ); ?>"/>
                        </td>
                        <td>
                            <input type="text" name='quantity0' placeholder='Количество' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'quantity0', true ); ?>"/>
                        </td>
                        <td>
                            <select>
                                <option value"">Мярка</option>
                                <option value"1">бр.</option>
                                <option value"2">кг.</option>
                            </select>
<!--                             <input type="text" name='measure0' placeholder='Мярка' class="form-control"/> -->
                        </td>
                        <td>
                            <input type="text" name='price0' placeholder='Ед. цена' class="form-control" value="<?php echo get_post_meta( $invoice->ID, 'price0', true ); ?>"/>
                        </td>
                        <td>
                            <button name="del0" class='btn btn-danger row-remove'>x</button>
                        </td>
                    </tr>
                </tbody>

            </table>
            <input type="hidden" id="invoice_item_column_number" name="invoice_item_column_number" value="<?php echo $rows_number; ?>" />
    <a id="add_row" class="btn btn-default pull-right">Add Row</a>
</div>

<?php             
    }

    public function add_invoice_fields( $invoice_id) {
        if ( isset( $_POST['the_client'] ) && $_POST['the_client'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_client', $_POST['the_client'] );
        }
        if ( isset( $_POST['the_date'] ) && $_POST['the_date'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_date', $_POST['the_date'] );
        }
        $rows = !empty( $_POST['invoice_item_column_number'] ) ? (int) $_POST['invoice_item_column_number'] : 0;

        $num_row = 0;
        $i = 0;
        while ($num_row < $rows) {
            if ( isset( $_POST['name'.$i] ) && $_POST['name'.$i] != '' ) {
                update_post_meta( $invoice_id, 'name'.$i , $_POST['name'.$i] );
            }
            if ( isset( $_POST['quantity'.$i] ) && $_POST['quantity'.$i] != '' ) {
                update_post_meta( $invoice_id, 'quantity'.$i , $_POST['quantity'.$i] );
            }
            if ( isset( $_POST['measure'.$i] ) && $_POST['measure'.$i] != '' ) {
                update_post_meta( $invoice_id, 'measure'.$i , $_POST['measure'.$i] );
            }
            if ( isset( $_POST['price'.$i] ) && $_POST['price'.$i] != '' ) {
                update_post_meta( $invoice_id, 'price'.$i , $_POST['price'.$i] );
            }
            $num_row++;
            $i++;           
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