<?php
/**
 * The content for an image or gallery post.
 * Mark-up changes if `is_single()`.
 *
 * @package Original Flea
 */

get_template_part( 'article', 'header' );
?>

<?php if ( is_single() ) : ?>
    <span class="post-title">
        <?php the_title(); ?>
    </span>
<?php else : ?>
    <a class="post-title" href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
    </a>
<?php endif; ?>

<div class="post-content">
    <?php the_content(); ?>
</div>

<?php get_template_part( 'comments' ); ?>
