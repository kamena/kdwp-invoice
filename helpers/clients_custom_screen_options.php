<?php 
add_filter( 'manage_edit-client_columns', 'edit_screen_options_columns_clients' ) ;

function edit_screen_options_columns_clients( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Клиент', 'kdwpinvoice' ),
		'company_name' => __( 'Име на фирмата', 'kdwpinvoice' ),
		'company_id' => __( 'ЕИК/Булстат', 'kdwpinvoice' ),
		'date' => __( 'Дата', 'kdwpinvoice' )
	);

	return $columns;
}

add_action( 'manage_client_posts_custom_column', 'manage_screen_options_columns_clients', 10, 2 );

function manage_screen_options_columns_clients( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'company_name' :
			$company_name = get_post_meta( $post_id, 'company_name', true);
			if ( empty( $company_name ) )	echo __( 'Unknown' );
			else echo __( $company_name );
			break;

		case 'company_id' :
			$company_id = get_post_meta( $post_id, 'company_id', true);
			if ( empty( $company_id ) )	echo __( 'Unknown' );
			else echo __( $company_id );
			break;

		default :
			break;
	}
}

add_filter( 'manage_edit-client_sortable_columns', 'sortable_columns_clients' );

function sortable_columns_clients( $columns ) {
	$columns['company_name'] = 'company_name';
	$columns['company_id'] = 'company_id';

	return $columns;
}

/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'clients_load' );

function clients_load() {
	add_filter( 'request', 'sort_column_clients' );
}

/* Sorts the columns. */
function sort_column_clients( $vars ) {

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