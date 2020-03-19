<?php
/**
 * Template Name: Transamerica Report Page
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

						<div class="aha-screen-only" style="overflow:auto;">
						    <img src="/wp-content/plugins/cc-aha-extras/includes/im/ta-report/aha-logo.jpg" style="margin-left: 2em; width: 150px;" alt="American Heart Association organizational logo">
							<div class="ta-report-ta-logo" style="width: 200px; float:right; display:inline; margin-right: 2em;">
						  		<img src="/wp-content/plugins/cc-aha-extras/includes/im/ta-report/transamerica-logo.jpg" style="" alt="Transamerica company logo">
						  		<p style="text-align:left;margin-top:1em;margin-bottom:0;line-height:1.2;font-size:.6rem;">Transamerica is proud to be a national supporter of the American Heart Association</p>
						  	</div>
						</div>

						<header class="entry-header aha-screen-only" style="text-align:center;margin-bottom:1.2em;">

							<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
							<p class="aha-screen-only" style="margin:0 auto 0;max-width: 920px">Transamerica&reg; is proud to support the American Heart Association&rsquo;s Healthy for Good&trade; Movement. Through this collaboration, Transamerica and the American Heart Association (AHA) empower and equip Americans to live better today and worry less about tomorrow. Together, we&rsquo;ll guide people to make the most of their Wealth + Health&#8480; for a more fulfilling future.</p>

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
