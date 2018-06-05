<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Pocono
 */

get_header(); ?>

	<section id="primary" class="content-single content-area">
		<main id="main" class="site-main" role="main">

		<?php pocono_breadcrumbs(); ?>

		<?php while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', 'page' );

			comments_template();

		endwhile; ?>

<pre>
<?php
	$items = false;
	// $items = new WP_Query( array(
	// 	'post_type' => 'aha-action-step',
	// 	'posts_per_page' => 100,
	// 	'paged' => 1,
	// 	'meta_key' => 'cc_post_parent',
	// 	'meta_value' => 0,
	// 	'meta_compare' => '>',
	// ) );
	// $items = new WP_Query( array(
	// 	'post_type' => 'aha-action-progress',
	// 	'posts_per_page' => 100,
	// 	'paged' => 1,
	// 	'meta_key' => 'cc_post_parent',
	// 	'meta_value' => 0,
	// 	'meta_compare' => '>',
	// ) );
	// $items = new WP_Query( array(
	// 	'post_type' => 'aha_survey_response',
	// 	'posts_per_page' => 100,
	// 	'paged' => 1,
	// 	'meta_key' => 'cc_post_parent',
	// 	'meta_value' => 0,
	// 	'meta_compare' => '>',
	// ) );

	if ( $items && $items->have_posts() ) {
		while ( $items->have_posts() ) {
			$items->the_post();
			$old_post_parent = get_post_meta( $post->ID, 'cc_post_parent', true );
			if ( $old_post_parent ) {
				echo PHP_EOL . "{$post->ID} old_post_parent: {$old_post_parent}. Updated: ";
				var_dump( wp_update_post( array(
				      'ID'          => $post->ID,
				      'post_parent' => absint( $old_post_parent ),
				) ) );
			} else {
				echo PHP_EOL . "{$post->ID} old_post_parent: {$old_post_parent}. Nothing to do.";
			}
		}
	}

?>
</pre>

		</main><!-- #main -->
	</section><!-- #primary -->

	<?php get_sidebar(); ?>

<?php get_footer(); ?>
