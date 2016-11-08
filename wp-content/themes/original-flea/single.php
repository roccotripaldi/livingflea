<?php
/**
 * The template for displaying all single posts.
 *
 * @package Original Flea
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			
			<?php if ( has_term( 'news', 'category' ) ) : ?>
				<?php get_template_part( 'content-news' ); ?>
			<?php else : ?>
				<?php get_template_part( 'content' ); ?>
			<?php endif; ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
