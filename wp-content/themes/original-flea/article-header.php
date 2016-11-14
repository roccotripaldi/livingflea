<?php
/**
 * A header that appears above an article in a looped list,
 * as well as above articles on single image pages.
 *
 * @package Original Flea
 */
?>
<div class="post-header">
    <div class="avatar">
        <?php living_flea_the_avatar(); ?>
    </div>
    <div class="post-meta">
        <p class="post-author"><?php the_author(); ?></p>
        <p class="post-market"><?php the_flea_market_link(); ?></p>
    </div>
    <div class="post-date">
        <p>
            <?php if ( is_single() ) : ?>
                <?php the_time( 'M j, Y' ); ?>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>"><?php the_time( 'M j, Y' ); ?></a>
            <?php endif; ?>
        </p>
    </div>
</div>