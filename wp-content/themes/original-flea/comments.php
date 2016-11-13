<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Original Flea
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

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
