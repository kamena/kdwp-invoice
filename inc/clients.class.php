<?php 
class Client {

    public function __construct() {
        add_action( 'init', array( $this, 'add_submenu_clients' ));
        add_action( 'add_meta_boxes', array( $this, 'kdwp_client_metabox_add' ));
        add_action( 'save_post', array( $this, 'add_client_fields'), 10, 2 );
    }

    public function add_submenu_clients() {
        register_post_type( 'client',
            array(
                'labels' => array( 
                    'name' => __('Клиенти', 'kdwpinvoice'),
                    'singular_name' => __('Клиенти', 'kdwpinvoice'),
                    'add_new' => __('Добави нов клиент', 'kdwpinvoice'),
                    'add_new_item' => __('Добави клиент', 'kdwpinvoice'),
                    'edit' => __('Edit', 'kdwpinvoice'),
                    'edit_item' => __('Edit Cutomer', 'kdwpinvoice'),
                    'new_item' => __('Нов клиент', 'kdwpinvoice'),
                    'view' => __('View PDF', 'kdwpinvoice'),
                    'view_item' => __('View Customer', 'kdwpinvoice'),
                    'search_items' => __('Search Customer', 'kdwpinvoice'),
                    'not_found' => __('No Customer found', 'kdwpinvoice'),
                    'not_found_in_trash' => __('No Customer found in Trash', 'kdwpinvoice'),
                    'parent' => __('Parent Customer', 'kdwpinvoice')
                ),
     
                'public' => true,
                'menu_position' => 17,
                'supports' => array( 'title' ),
                'menu_icon' => plugins_url( '../images/clients-icon.png', __FILE__ ),
                'has_archive' => true
            )
        );
    }

    public function kdwp_client_metabox_add() {
        add_meta_box( 'client-info', 
            __('Информация за купувача', 'kdwpinvoice'), 
            array( $this, 'kdwp_client_metabox' ), 
            'client', 'normal', 'high' );
    }

    public function kdwp_client_metabox( $client ) {

        $company_name = esc_html( get_post_meta( $client->ID, 'company_name', true ) );
        $company_city = esc_html( get_post_meta( $client->ID, 'company_city', true) );
        $company_address = esc_html( get_post_meta( $client->ID, 'company_address', true ) );
        $company_id = esc_html( get_post_meta( $client->ID, 'company_id', true ) );
        $responsible_person = esc_html( get_post_meta( $client->ID, 'responsible_person', true ) );
        $client_name = esc_html( get_post_meta( $client->ID, 'client_name', true ) );  
        $company_mail = esc_html( get_post_meta( $client->ID, 'company_mail', true ) );
    ?>

        <div>
            <label><?php _e('Име на фирмата', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_company_name" type="text" value="<?php echo $company_name; ?>" size="8"></div>
        </div>

        <div>
            <label><?php _e('Град', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_company_city" type="text" value="<?php echo $company_city; ?>" size="8"></div>
        </div>

        <div>
            <label><?php _e('Адрес на фирмата', 'kdwpinvoice'); ?></label>
            <div><textarea name="user_information_company_address" rows="5" cols="50" ><?php echo $company_address; ?></textarea></div>
        </div>

        <div>
            <label><?php _e('ЕИК/Булстат', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_company_id" type="text" value="<?php echo $company_id; ?>" size="8"></div>
        </div>

        <div>
            <label><?php _e('МОЛ', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_responsible_person" type="text" value="<?php echo $responsible_person; ?>" size="8"></div>
        </div>

        <div>
            <label><?php _e('Име на получател', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_client_name" type="text" value="<?php echo $client_name; ?>" size="8"></div>
        </div>

        <hr>

        <div>
            <label><?php _e('E-mail на фирмата', 'kdwpinvoice'); ?></label>
            <div><input name="user_information_company_mail" type="email" spellcheck="false" value="<?php echo $company_mail; ?>" maxlength="255"> </div>
        </div>
    <?php
    }


    public function add_client_fields( $client_id, $client ) {
        if ( $client->post_type == 'client' ) {
            if ( isset( $_POST['user_information_company_name'] ) && $_POST['user_information_company_name'] != '' ) {
                update_post_meta( $client_id, 'company_name', $_POST['user_information_company_name'] );
            }
            if ( isset( $_POST['user_information_company_city'] ) && $_POST['user_information_company_city'] != '' ) {
                update_post_meta( $client_id, 'company_city', $_POST['user_information_company_city'] );
            }
            if ( isset( $_POST['user_information_company_address'] ) && $_POST['user_information_company_address'] != '' ) {
                update_post_meta( $client_id, 'company_address', $_POST['user_information_company_address'] );
            }
            if ( isset( $_POST['user_information_company_id'] ) && $_POST['user_information_company_id'] != '' ) {
                update_post_meta( $client_id, 'company_id', $_POST['user_information_company_id'] );
            }
            if ( isset( $_POST['user_information_responsible_person'] ) && $_POST['user_information_responsible_person'] != '' ) {
                update_post_meta( $client_id, 'responsible_person', $_POST['user_information_responsible_person'] );
            }
            if ( isset( $_POST['user_information_client_name'] ) && $_POST['user_information_client_name'] != '' ) {
                update_post_meta( $client_id, 'client_name', $_POST['user_information_client_name'] );
            }
            if ( isset( $_POST['user_information_company_mail'] ) && $_POST['user_information_company_mail'] != '' ) {
                update_post_meta( $client_id, 'company_mail', $_POST['user_information_company_mail'] );
            }
        }
    }

}

new Client();
