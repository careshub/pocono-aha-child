<?php
/**
 * Template Name: Financial Advisors Report Page
 * Template Post Type: post, page
 *
 * Description: A custom template for displaying a fullwidth layout with no sidebar.
 *
 * @package Pocono
 */

get_header(); ?>

	<section id="primary" class="fullwidth-content-area content-area">
		<main id="main" class="site-main" role="main">

			<?php pocono_breadcrumbs(); ?>

			<?php while ( have_posts() ) : the_post();

				if ( 'post' === get_post_type() ) :

					get_template_part( 'template-parts/content', 'single' );

				else :
				?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<?php the_post_thumbnail(); ?>

					<div class="post-content clearfix">

						<div class="aha-screen-only" style="overflow:auto; display: flex; align-items: end; justify-content: space-between; flex-wrap: wrap;">
						    <img src="/wp-content/plugins/cc-aha-extras/includes/im/ta-report/aha-logo.jpg" style="margin-left: 2em; width: 150px;" alt="American Heart Association organizational logo">
							<div class="ta-report-ta-logo" style="width: 200px; float:right; display:inline; margin-right: 2em; margin-left:2em">
						  		<img src="/wp-content/plugins/cc-aha-extras/includes/im/ta-report/ej-logo.PNG" style="" alt="Edward Jones company logo">
						  	</div>
						</div>

						<header class="entry-header aha-screen-only" style="text-align:center;margin-bottom:1.2em;">

							<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
							<p class="aha-screen-only" style="margin:0 auto 0;max-width: 920px">Edward Jones is proud to be a national supporter of the American Heart Association (AHA). Through this collaboration, Edward Jones and the AHA empower and equip all Americans &mdash; clients, colleagues, and community members &mdash; with tools and resources to promote optimal health and financial security.</p>

						</header><!-- .entry-header -->

						<div class="entry-content clearfix">

							<?php the_content(); ?>

							<?php wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'pocono' ),
								'after'  => '</div>',
							) ); ?>

						</div><!-- .entry-content -->

					</div>

				</article>
				<?php

				endif;

				comments_template();

			endwhile; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
