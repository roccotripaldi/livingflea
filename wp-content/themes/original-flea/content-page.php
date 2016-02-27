<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Original Flea
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h2 class="page-title"><?php the_title(); ?></h2>

	<div class="page-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</div><!-- #post-## -->
