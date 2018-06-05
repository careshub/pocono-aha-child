 <?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Pocono
 */

?>
	<section class="sidebar-navigation-container">
		<button id="sidebar-navigation-toggle" class="sidebar-navigation-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle sidebar navigation menu', 'pocono' ); ?>"></button>
		<div id="sidebar-navigation" class="sidebar-navigation widget-area clearfix" role="complementary">

			<?php // Check if there is a sidebar menu.
			if ( has_nav_menu( 'secondary' ) ) : ?>

				<aside class="widget widget_nav_menu sidebar-menu-widget clearfix">

					<div class="widget-header">
						<h3 class="widget-title"><?php esc_html_e( 'Navigation', 'pocono' ); ?></h3>
					</div>

					<nav id="sidebar-menu" class="secondary-navigation navigation menu-navigation-container clearfix" role="navigation">
						<?php
							// Display Header Navigation.
							wp_nav_menu( array(
								'theme_location' => 'secondary',
								'container' => false,
								'menu_class' => 'sidebar-navigation-menu menu',
								'echo' => true,
								'fallback_cb' => '',
								)
							);
						?>
					</nav><!-- #sidebar-navigation -->

				</aside>

			<?php endif;

			// Check if Sidebar has widgets.
			if ( is_active_sidebar( 'navigation-menu' ) ) :

				dynamic_sidebar( 'navigation-menu' );

			endif; ?>

		</div><!-- #sidebar-navigation -->
	</section>
