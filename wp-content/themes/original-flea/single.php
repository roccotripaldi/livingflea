<?php
/**
 * The template for displaying all single posts.
 * Simply sets some wrapper divs and grabs the content template.
 *
 * @package Original Flea
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content' ); ?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_footer(); ?>
