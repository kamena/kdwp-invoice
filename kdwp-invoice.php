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

add_action( 'admin_enqueue_scripts', 'register_admin_plugin_scripts' );

function register_admin_plugin_scripts() {
    wp_register_style( 'custom_wp_admin_css', plugins_url('/css/client_form.css', __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}

add_action( 'init', 'create_invoice' );

function create_invoice() {
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

add_action( 'admin_init', 'my_admin' );

function my_admin() {
    add_meta_box( 'invoice_meta_box',
        'Movie Review Details',
        'display_invoice_meta_box',
        'invoices', 'normal', 'high'
    );
}

function display_invoice_meta_box( $invoice ) {
    $author = esc_html( get_post_meta( $invoice->ID, 'author', true ) );
    $book_rating = intval( get_post_meta( $invoice->ID, 'book_rating', true ) );
    ?>
    <table>
        <tr>
            <td style="width: 100%">Author</td>
            <td><input type="text" size="80" name="invoice_author" value="<?php echo $author; ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Book Rating</td>
            <td>
                <select style="width: 100px" name="invoice_rating">
                <?php
                // Generate all items of drop-down list
                for ( $rating = 5; $rating >= 1; $rating -- ) {
                ?>
                    <option value="<?php echo $rating; ?>" <?php echo selected( $rating, $book_rating ); ?>>
                    <?php echo $rating; ?> stars <?php } ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}


add_action( 'save_post', 'add_invoice_fields', 10, 2 );

function add_invoice_fields( $invoice_id, $invoice ) {
    if ( $invoice->post_type == 'invoices' ) {
        if ( isset( $_POST['invoice_author'] ) && $_POST['invoice_author'] != '' ) {
            update_post_meta( $invoice_id, 'author', $_POST['invoice_author'] );
        }
        if ( isset( $_POST['invoicew_rating'] ) && $_POST['invoice_rating'] != '' ) {
            update_post_meta( $invoice_id, 'book_rating', $_POST['invoice_rating'] );
        }
    }
}

add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
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

?>