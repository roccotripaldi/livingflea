<?php
/**
 * The content section used in looped lists of posts, as well as single posts.
 * This template sets a wrapper div, and loads in the content for the appropriate type
 * 
 * @package Original Flea
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_term( 'words', 'category' ) ) : ?>
		<?php get_template_part( 'content', 'news' ); ?>
	<?php else : ?>
		<?php get_template_part( 'content', 'image' ); ?>
	<?php endif; ?>
</div>


<?php if ( is_single() ) :
	$author_id = get_the_author_meta( 'ID' );
	$author_link = get_author_posts_url( $author_id );
	?>
	<div id="see-more">
		<p>See more stories from <?php the_flea_market_link(); ?></p>
		<p>See more stories by <a href="<?php echo $author_link; ?>"><?php the_author(); ?></a></p>
	</div>
<?php endif; ?>

