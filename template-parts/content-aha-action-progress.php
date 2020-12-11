<?php
/**
 * The template for displaying single posts
 *
 * @package Pocono
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php the_post_thumbnail(); ?>

	<?php pocono_entry_categories(); ?>

	<div class="post-content clearfix">

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

			<?php pocono_entry_meta(); ?>

		</header><!-- .entry-header -->

		<div class="entry-content clearfix">

			<?php if ( function_exists( 'aha_render_meta' ) ) {
				aha_render_meta();
			} ?>

			<?php if ( current_user_can( 'aha_review_item', get_the_ID() ) && function_exists( 'aha_review_controls' ) ) {
				aha_review_controls();
			} ?>

		</div><!-- .entry-content -->

		<footer class="entry-footer">

			<?php pocono_entry_tags(); ?>
			<?php // pocono_post_navigation(); ?>

		</footer><!-- .entry-footer -->

	</div>

</article>
