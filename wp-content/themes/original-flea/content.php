<?php
/**
 * The content section used in looped lists of posts, as well as single posts.
 * This template sets a wrapper div, and loads in the content for the appropriate type
 * 
 * @package Original Flea
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_term( 'news', 'category' ) ) : ?>
		<?php get_template_part( 'content', 'news' ); ?>
	<?php else : ?>
		<?php get_template_part( 'content', 'image' ); ?>
	<?php endif; ?>
</div>