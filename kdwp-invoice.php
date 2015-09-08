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
        add_action( 'the_content', array( $this, 'prepend_the_date' ) ); 
    }

    public function register_admin_plugin_scripts() {
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('/css/style.css', __FILE__ ), false, '1.0.0' );
    }

    public function register_admin_scripts() {
        wp_enqueue_script( 'jquery-datepicker', plugins_url('/js/datepicker.js', __FILE__ ), array( 'jquery' ), '', true );
    }

    public function create_invoice() {
        register_post_type( 'invoices',
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

        register_taxonomy( 'Genres', array('invoices'), 
            array(
                'hierarchical' => true,
                'label' => 'Genres',
                'singular_label' => 'Genre',
                'rewrite' => true
            )
        );

    }


    public function my_admin() {
        add_meta_box( 'dropdown-client', __( 'Choose client', 'kdwp-invoicer' ), array( $this, 'client_metabox' ), 'invoices', 'normal', 'high' );
        add_meta_box( 'the_date', __( 'The Date', 'kdwp-invoicer' ), array( $this, 'the_date_display' ), 'invoices', 'side', 'low' );
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
            'post_type'        => 'clients',
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
            <select name="the_client">
                <option> </option>
                 <?php  foreach ($all_clients as $client): ?>
                    <option value="<?php echo $client->ID; ?>"<?php echo selected( $client->ID, $chosen_client ); ?>> 
                        <?php echo $client->post_title; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
<?php   
    }

    public function the_date_display( $post ) {
        $chosen_date = esc_html( get_post_meta( $post->ID, 'chosen_date', true));
        echo '<input id="datepicker" type="text" name="the_date" value="' . $chosen_date . '" />';
    }

    public function add_invoice_fields( $invoice_id, $invoice ) {
        if ( isset( $_POST['the_client'] ) && $_POST['the_client'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_client', $_POST['the_client'] );
        }
        if ( isset( $_POST['the_date'] ) && $_POST['the_date'] != '' ) {
            update_post_meta( $invoice_id, 'chosen_date', $_POST['the_date'] );
        }
    }

    public function include_template_function( $template_path ) {
        if ( get_post_type() == 'invoices' ) {
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