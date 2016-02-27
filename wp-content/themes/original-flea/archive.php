<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Original Flea
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_author() ) : ?>

				<?php get_template_part( 'header', 'author' ); ?>

			<?php elseif ( is_tax( 'flea-markets' ) ) : ?>

				<?php get_template_part( 'header', 'market' ); ?>

			<?php endif; ?>


			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</div><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
