<?php
/**
 * The template for displaying all single posts.
 *
 * @package Pocono
 */

get_header(); ?>

	<section id="primary" class="content-single content-area">
		<main id="main" class="site-main" role="main">

		<?php pocono_breadcrumbs(); ?>

		<?php while ( have_posts() ) : the_post();
			$template_name = ( 'post' === get_post_type() ) ? 'single' : get_post_type();
			get_template_part( 'template-parts/content', $template_name );

			if ( 'single' === $template_name ) {
				pocono_related_posts();
			}

			comments_template();

		endwhile; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
