<?php
/**
 * The content for a news article.
 * Mark-up changes if `is_single()`
 *
 * @package Original Flea
 */
$author_id = get_the_author_meta( 'ID' );
$author_link = get_author_posts_url( $author_id );
?>
<?php if ( is_single() ) : ?>
    <h2 class="page-title"><?php the_title(); ?></h2>
    <p class="byline">By <a href="<?php echo $author_link; ?>"><?php the_author(); ?></a>, <?php the_time( 'M j, Y' ); ?></p>
    <div class="page-content news">
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

