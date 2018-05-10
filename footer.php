<?php
/**
 * The template for displaying the footer.
 *
 * Contains all content after the main content area and sidebar
 *
 * @package Pocono
 */

?>

	</div><!-- #content -->

	<?php do_action( 'pocono_before_footer' ); ?>

	<?php /*
	// AHA requires a syndicated footer, so it seems like the local footer will not be used.
	?>
	<div id="footer" class="footer-wrap">

		<footer id="colophon" class="site-footer container clearfix" role="contentinfo">

			<?php do_action( 'pocono_footer_menu' ); ?>

			<div id="footer-text" class="site-info">
				<?php do_action( 'pocono_footer_text' ); ?>
			</div><!-- .site-info -->

		</footer><!-- #colophon -->

	</div>
	<?php */ ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
