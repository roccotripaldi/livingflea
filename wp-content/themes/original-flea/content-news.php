<?php
/**
 * The content for a news article.
 * 
 * @package Original Flea
 */
?>
<a class="post-title" href="<?php the_permalink(); ?>">
    <?php the_title(); ?>
</a>
<div class="post-content">
    <?php the_content(); ?>
</div>