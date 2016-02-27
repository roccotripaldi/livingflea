<?php
    $author = get_queried_object();
    $post_count = count_user_posts( $author->ID );
?>
<div class="archive-header" id="author-header">
    <div class='avatar'>
        <?php echo get_living_flea_avatar( $author->ID, $author->user_email, false, '200' ); ?>
    </div>
    <div class="info">

        <h2><?php echo $author->display_name; ?></h2>
        <p>
            <?php echo get_the_author_meta( 'description', $author->ID ); ?>
        </p>
    </div>
</div>

<p class="archive-count">
    <?php echo sprintf( _n( '%s story shared', '%s stories shared', $post_count ), $post_count ); ?>
</p>