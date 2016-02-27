<?php

class Living_Flea_Comments {

    function __construct() {
        if ( is_admin() ) {
            add_action( 'wp_ajax_livingflea_comment', array( $this, 'add_comment' ) );
        } else {
            add_action( 'wp_print_footer_scripts', array( $this, 'print_scripts' ) );
        }
    }

    public function add_comment() {

        if ( ! is_user_logged_in() ) {
            echo 'Unauthorized';
            exit;
        }

        global $current_user;

        $commentdata = array(
            'comment_post_ID' => $_POST['post_id'],
            'comment_author' => $current_user->display_name,
            'comment_author_email' => $current_user->user_email,
            'comment_content' => $_POST['comment_content'],
            'user_id' => $current_user->ID,
        );

        $comment_id = wp_new_comment( $commentdata );
        $comment = get_comment( $comment_id );

        echo json_encode(
            array(
                'commentAuthor' => $comment->comment_author,
                'commentContent' => $comment->comment_content,
                'authorURL' => get_author_posts_url( $current_user->ID )
            )
        );
        exit;
    }

    public function print_scripts() {
        include LIFL_PATH . '/templates/template.comments.php';
    }
}

$living_flea_comments = new Living_Flea_Comments();
