<?php
/**
 * The content for a news article.
 * Mark-up changes if `is_single()`
 *
 * @package Original Flea
 */
?>
<div class="post-header">
    <div class="avatar">
        <?php living_flea_the_avatar(); ?>
    </div>
    <div class="post-meta">
        <p class="post-author"><?php the_author(); ?></a></p>
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

<span class="post-title news-flash">* * * * * News Flash * * * * *</span>

<div class="post-content news">
    <p class="news-title"><?php the_title(); ?></p>
    <?php the_excerpt(); ?>
</div>