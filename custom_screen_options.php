<?php
// GET FEATURED IMAGE
// function ST4_get_featured_image($invoice) {
//     $post_thumbnail_id = get_post_thumbnail_id($invoice);
//     if ($post_thumbnail_id) {
//         $meta = get_post_meta($invoice->ID, 'client_name', true ); 
//         echo "..." . $meta;
//         $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');
//         return $meta;
//     }
// }

 
// SHOW THE FEATURED IMAGE
// function ST4_columns_content($column_name, $invoice) {
//     if ($column_name == 'featured_image') {
//         // $post_featured_image = ST4_get_featured_image($invoice);
//         $meta = get_post_meta($invoice->ID, 'client_name', true );
//         echo "Hellooo?";
//         echo "Name: " . get_post_meta($invoice->ID, 'client_name', true );;
//         if ($post_featured_image) {
//             echo "Hellooo?";
//             echo $post_featured_image;
//             //echo '<img height="70" src="' . $post_featured_image . '" />';
//         }
//     }
// }

// ADD NEW COLUMN
// function custom_columns_head($columns) {
//     $columns['cust_col'] = 'Купувач';
//     $columns['companyName'] = 'Име на фирмата';
//     return $columns;
// }

// add_filter('manage_invoice_posts_columns', 'custom_columns_head');


// function cust_field_text($column_name){
    // if($column_name === 'cust_col'){
    //     // the_meta();
    //     echo get_post_meta(get_the_ID(), 'client_name', true );
    // }
    // if($column_name === 'companyName') {
    //     echo get_post_meta(get_the_ID(), 'company_name', true );
    // }
// }
// add_action('manage_posts_custom_column', 'cust_field_text', 10, 2);

// add_action('manage_posts_custom_column', 'ST4_columns_content', 10, 2);

add_filter( 'manage_edit-invoice_columns', 'edit_screen_options_columns' ) ;

function edit_screen_options_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Книга' ),
		'user_names' => __( 'Купувач' ),
		'company_name' => __( 'Име на фирмата' ),
		'genre' => __( 'Жанр' ),
		'date' => __( 'Дата' )
	);

	add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );

	return $columns;
}

// add_filter( 'manage_invoice_tables', 'add_new_table' );
// function add_new_table() {
// 	echo "Hi!";
// }

add_action( 'manage_invoice_posts_custom_column', 'manage_screen_options_columns', 10, 2 );

function manage_screen_options_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'user_names' :

			/* Get the post meta. */
			$user_names = get_post_meta( $post_id, 'client_name', true );

			if ( empty( $user_names ) )	echo __( 'Unknown' );
			else echo __( $user_names );

			break;

		case 'company_name' :
			$company_name = get_post_meta( $post_id, 'company_name', true);
			if ( empty( $company_name ) )	echo __( 'Unknown' );
			else echo __( $company_name );
			break;

		case 'genre' :

			/* Get the genres for the post. */
			$terms = get_the_terms( $post_id, 'Genres' );

			/* If terms were found. */
			if ( !empty( $terms ) ) {

				$out = array();

				/* Loop through each term, linking to the 'edit posts' page for the specific term. */
				foreach ( $terms as $term ) {
					$out[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
					);
				}

				/* Join the terms, separating them with a comma. */
				echo join( ', ', $out );
			}

			/* If no terms were found, output a default message. */
			else {
				_e( 'No Genres' );
			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_filter( 'manage_edit-invoice_sortable_columns', 'sortable_columns' );

function sortable_columns( $columns ) {

	// $columns = array(
	// 	'user_names' => 'user_names',
	// 	'company_name' => 'company_name',
	// 	'genre' => 'genre' 
	// );
	$columns['user_names'] = 'user_names';
	$columns['company_name'] = 'company_name';
	$columns['genre'] = 'genre';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'my_edit_movie_load' );

function my_edit_movie_load() {
	add_filter( 'request', 'sort_column' );
}

/* Sorts the movies. */
function sort_column( $vars ) {

	/* Check if we're viewing the 'movie' post type. */
	if ( isset( $vars['post_type'] ) && 'movie' == $vars['post_type'] ) {

		/* Check if 'orderby' is set to 'duration'. */
		if ( isset( $vars['orderby'] ) && 'user_names' == $vars['orderby'] ) {

			/* Merge the query vars with our custom variables. */
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'asdasd',
					'orderby' => 'meta_value_num'
				)
			);
		}
	}

	return $vars;
}

?>