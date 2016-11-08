<?php
/**
 * @package Original Flea
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <a class="post-title" href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
    </a>
    <div class="post-content">
        <?php the_content(); ?>
    </div>
</div>