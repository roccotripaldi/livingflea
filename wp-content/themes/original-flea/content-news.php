<?php
/**
 * The content for a news article.
 * Mark-up changes if `is_single()`
 *
 * @package Original Flea
 */

?>
<?php if ( is_single() ) : ?>
    <h2 class="page-title"><?php the_title(); ?></h2>
    <p>By <?php the_author(); ?>, <?php the_time( 'M j, Y' ); ?></p>
    <div class="post-content news">
        <?php the_content(); ?>
    </div>
    <?php get_template_part( 'comments' ); ?>
<?php else : ?>
    <?php get_template_part( 'article', 'header' ); ?>
    <span class="post-title news-flash">* * * * * News Flash * * * * *</span>
    <div class="post-content news">
        <a class="news-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <?php the_excerpt(); ?>
    </div>
<?php endif; ?>

