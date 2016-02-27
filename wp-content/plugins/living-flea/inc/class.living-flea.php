<?php

class Living_Flea  {

    private $fb_secret = '160f0df7f33a429b64906766a314dd10';
    private $fb_app_id = '1038781959488918';
    private $fb_api_base = 'https://graph.facebook.com/v2.4/';

    function __construct() {
        if ( is_admin() ) {
            add_action( 'wp_ajax_livingflea_fblogin', array( $this, 'fbauth' ) );
            add_action( 'wp_ajax_nopriv_livingflea_fblogin', array( $this, 'fbauth' ) );
            add_action( 'wp_ajax_livingflea_fblogout', array( $this, 'logout' ) );
            add_action( 'wp_ajax_nopriv_livingflea_fblogout', array( $this, 'logout' ) );
            add_action( 'wp_ajax_livingflea_process_upload', array( $this, 'proccess_shared_photos' ) );
            add_action( 'wp_ajax_nopriv_livingflea_process_upload', array( $this, 'proccess_shared_photos' ) );
        } else {
            add_filter( 'shaph-js-data', array( $this, 'modify_shaph_js_data' ) );
            add_filter( 'shaph-extension-pages', array( $this, 'add_shaph_extensions' ) );
            add_filter( 'shaph_template-uploader', array( $this, 'get_uploader_template' ) );
            add_filter( 'shaph_template-image-attributes', array( $this, 'get_attr_template' ) );
            add_action( 'wp_loaded', array( $this, 'register_assets' ) );
            add_action( 'wp_print_footer_scripts', array( $this, 'print_scripts' ) );
        }
    }

    public function get_attr_template() {
        ob_start();
        include LIFL_DIR . '/templates/template.image-attributes.php';
        $template = ob_get_contents();
        ob_end_clean();
        return $template;
    }

    public function get_uploader_template() {
        ob_start();
        include LIFL_DIR . '/templates/template.uploader.php';
        $template = ob_get_contents();
        ob_end_clean();
        return $template;
    }

    public function add_shaph_extensions( $list ) {
        $extensions =  array( 'post_data' => LIFL_DIR . '/templates/template.post-data.php' );

        if ( ! is_user_logged_in() ) {
            $extensions['login'] = LIFL_DIR . '/templates/template.login-upload.php';
        }

        return $extensions;
    }

    public function modify_shaph_js_data( $data ) {
        $data['processPost'] = 'livingflea_process_upload';
        return $data;
    }

    public function logout() {
        wp_logout();
        exit;
    }

    private function validate_fb_token( $token, $user_id ) {

        if( empty( $token ) || empty( $user_id ) ) {
            return false;
        }

        $app_access_token_response = wp_remote_get(
            $this->fb_api_base . 'oauth/access_token' .
            '?client_id=' . $this->fb_app_id .
            '&client_secret=' . $this->fb_secret .
            '&grant_type=client_credentials'
        );

        $app_access_token_data = json_decode( wp_remote_retrieve_body( $app_access_token_response ) );

       $validation_response = wp_remote_get(
            $this->fb_api_base . 'debug_token' .
            '?input_token=' . $token .
            '&access_token=' . $app_access_token_data->access_token
        );

        $validation_data = json_decode( wp_remote_retrieve_body( $validation_response ) );

        if (  $validation_data->data->user_id == $user_id ) {

            $profile_response = wp_remote_get(
                $this->fb_api_base . $user_id .
                '?access_token=' . $app_access_token_data->access_token .
                '&fields=id,about,bio,email,first_name,last_name,picture.type(large)'
            );

            $profile_data = json_decode( wp_remote_retrieve_body( $profile_response ) );
            return $profile_data;
        }

        return false;
    }

    private function set_fb_user_meta( $user_id, $fb_profile ) {
        update_user_meta( $user_id, '_fbuid', $fb_profile->id );
        update_user_meta( $user_id, '_fbpicture', $fb_profile->picture->data->url );
        $args = array(
            'ID' => $user_id,
            'display_name' => $fb_profile->first_name . ' ' . $fb_profile->last_name,
            'first_name' => $fb_profile->first_name,
            'last_name' => $fb_profile->last_name,
        );
        if ( isset( $fb_profile->email ) ) {
            $args['user_email'] = $fb_profile->email;
        }
        wp_update_user( $args );
    }

    private function get_user( $fb_profile ) {
        $users = get_users( array( 'meta_key' => '_fbuid', 'meta_value' => $fb_profile->id ) );
        if ( ! empty( $users ) ) {
            $user = $users[0];
            $this->set_fb_user_meta( $user->ID, $fb_profile );
            return $user;
        }

        $found = get_user_by( 'email', $fb_profile->email );

        if( $found ) {
            $this->set_fb_user_meta( $found->ID, $fb_profile );
            return $found;
        }

        $user_nicename = $fb_profile->first_name . '-' . $fb_profile->last_name . wp_generate_password( 8, false );
        $user_login = isset( $fb_profile->email ) ? $fb_profile->email : $user_nicename;

        $args = array(
            'user_pass' => wp_generate_password(20),
            'user_login' => $user_login,
            'description' => 'Flea Market adventurer since ' . date( 'm/d/Y' ),
            'role' => 'author',
            'user_nicename' => $user_nicename
        );

        $id = wp_insert_user( $args );
        $this->set_fb_user_meta( $id, $fb_profile );
        return get_user_by( 'id', $id );
    }

    public function fbauth() {

        if ( ! wp_verify_nonce( $_POST['auth_nonce'], 'fb_auth' ) ) {
            echo 'Unauthorized';
            exit;
        }

        $fb_profile = $this->validate_fb_token( $_POST['access_token'], $_POST['user_id'] );

        if ( ! $fb_profile ) {
            echo 'Unauthorized';
            exit;
        }

        $user = $this->get_user( $fb_profile );
        $this->login_fb_user( $user );
        $response = 'ok';
        echo json_encode($response);
        exit;
    }

    public function login_fb_user( $user ) {
        ob_start();
        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID );
        do_action( 'wp_login', $user->user_login );
        ob_end_clean();
    }

    public function get_flea_market_by_name( $name ) {
        $term = get_term_by( 'name', $name, 'flea-markets', 'ARRAY_A' );
        if ( ! $term ) {
            $term = wp_insert_term( $name, 'flea-markets' );
        }
        return $term['term_id'];
    }

    public function living_flea_get_gallery_options( $images ) {
        if ( empty( $images ) ) {
            return false;
        }
        $ids = implode( ',', $images );
        return array(
            'size' => 'large',
            'link' => 'none',
            'ids' => $ids,
        );
    }

    public function proccess_shared_photos() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'shaph_upload' ) ) {
            die( json_encode( 'Unauthorized' ) );
        }

        if ( ! is_array( $_POST['files'] ) ) {
            die( json_encode( 'Unauthorized' ) );
        }

        global $current_user;
        $num_photos = 0;
        $gallery_images = array();

        foreach( $_POST['files'] as $image ) {
            if (!isset($image['attachment_id'])) {
                continue;
            }

            $attachment_id = $image['attachment_id'];

            if (!wp_verify_nonce($image['nonce'], 'shaph_attachment_' . $attachment_id)) {
                continue;
            }
            $gallery_images[] = $attachment_id;
        }

        $gallery_options = $this->living_flea_get_gallery_options( $gallery_images );

        if ( ! $gallery_options ) {
            die( json_encode( 'There was an error processing your images.' ) );
        }

        if ( is_user_logged_in() ) {
            $post_author_id = $current_user->ID;
            $post_author = $current_user;
            $post_status = 'publish';
        } else if ( $fb_profile = $this->validate_fb_token( $_POST['extensionData']['access_token'], $_POST['extensionData']['user_id'] ) ) {
            $new_user = $this->get_user( $fb_profile );
            $post_author_id = $new_user->ID;
            $post_author = $new_user;
            $post_status = 'publish';
            $this->login_fb_user( $new_user );
        } else {
            $post_author_id = get_option( 'shaph_anonymous_user' );
            $post_author = get_user_by( 'id', $post_author_id );
            $post_status = 'pending';
        }

        $post_title = empty( $_POST['extensionData']['postTitle'] ) ? 'Post by ' . $post_author->data->display_name : $_POST['extensionData']['postTitle'];
        $post_content = '[gallery';
        foreach( $gallery_options as $name => $value ) {
            $post_content .= ' ' . $name . '="' . $value . '"';
        }
        $post_content .= ']';

        $post_excerpt = ( empty( $_POST['files'][0]['caption'] ) ) ? 'Shared story on LivingFlea.com' : $_POST['files'][0]['caption'];

        $post_options = array(
            'post_title' => $post_title,
            'post_status' => $post_status,
            'post_author' => $post_author_id,
            'post_content' => $post_content,
            'post_excerpt' => $post_excerpt,
        );
        $post_id = wp_insert_post( $post_options );

        if ( isset( $_POST['extensionData']['marketName'] ) && ! empty( $_POST['extensionData']['marketName'] ) ) {
            $term_id = $this->get_flea_market_by_name( $_POST['extensionData']['marketName'] );
            wp_set_object_terms( $post_id, (int) $term_id, 'flea-markets' );
        }

        update_post_meta( $post_id, '_thumbnail_id', $_POST['files'][0]['attachment_id'] );

        foreach( $_POST['files'] as $image ) {
            $attachment_id = $image['attachment_id'];
            $attachment_options = array(
                'ID' => $attachment_id,
                'post_author' => $post_author_id,
                'post_parent' => $post_id
            );
            if( ! empty( $image['caption'] ) ) {
                $attachment_options['post_excerpt'] = $image['caption'];
            }
            wp_update_post( $attachment_options );
            $num_photos++;
        }

        $url = get_permalink( $post_id );

        if ( is_user_logged_in() || isset( $new_user ) ) {
            $message = sprintf( _n( '%s photo was published!', '%s photos were published', $num_photos ), $num_photos);
            $message .= "<br /><br /><a href='$url' class='button'>View post.</a>";
        } else {
            $message = sprintf(
                _n(
                    '%s photo is now in our inbox. It will be published pending review.',
                    '%s photos are now in our inbox. They will be published pending review.',
                    $num_photos
                ), $num_photos );
        }

        echo json_encode( array( 'message' => $message ) );
        exit;
    }

    public function register_assets() {
        $main_js = LIFL_PATH . 'js/living-flea.js';
        wp_register_script( 'living-flea-main', $main_js, array( 'jquery' ) );
    }

    public function print_scripts() {
        $args = array(
            'fields'            => 'names',
        );
        $markets = get_terms( 'flea-markets', $args);
        $living_flea_js_globals = array(
            'markets' => $markets,
            'loggedIn' => is_user_logged_in(),
            'nonce' => wp_create_nonce( 'fb_auth' ),
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        );
        wp_localize_script( 'living-flea-main', 'livingFlea', $living_flea_js_globals );
        wp_print_scripts( 'living-flea-main' );
        include LIFL_PATH . '/templates/template.login.php';
    }
}

$living_flea = new Living_Flea();