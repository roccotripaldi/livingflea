<?php
/**
 * @package Original Flea
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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

	<?php
		$comments = get_comments( array( 'post_id' => get_the_ID() ) );
		$empty = count( $comments ) === 0 ? 'empty' : '';
	?>
		<div class="post-comments <?php echo $empty; ?>" id="post-comments-<?php echo the_ID(); ?>">
			<?php if ( ! empty( $comments ) ) : ?>
				<?php foreach( $comments as $comment ) : ?>
					<p>
						<a href="<?php echo get_author_posts_url( $comment->user_id ); ?>"><?php echo $comment->comment_author; ?></a>
						<?php echo $comment->comment_content; ?>
					</p>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	<?php if ( is_user_logged_in() ) : ?>
		<div class="post-comment-form" id="comment-form-<?php echo the_ID(); ?>">
			<a name="comments"></a>
			<input name="post-comment" class="post-comment-input" data-id="<?php echo the_ID(); ?>" placeholder="Add a comment..." />
		</div>
	<?php else: ?>
		<p class="comment-login">
			<a
				class="comment-login-button"
				data-template="login-comment"
				data-element="#shaph-page"
				data-postId="<?php echo the_ID(); ?>"
				data-postName="<?php echo $post->post_name; ?>">
				Login to comment...
			</a>
		</p>
	<?php endif; ?>

	<?php echo sharing_display(); ?>
</div>