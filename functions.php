<?php
/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.1
 */
function virtue_child_arc_scripts() {
	// Include the needed js file.
	// wp_enqueue_script( 'virtue-child-arc-base-scripts', get_stylesheet_directory_uri( '/js/public.js' ), array( 'jquery' ), '1.0.1', true );

    wp_enqueue_style( 'pocono-stylesheet', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'pocono-child-aha-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'pocono-stylesheet' ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'virtue_child_arc_scripts' );

/**
 * Add the Google "noscript" tag immediately after the opening of the body element.
 *
 * @since 1.0.0
 *
 */
function pocono_child_aha_add_google_tag_manager_noscript_tag() {
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=ACCOUNT_ID_GOES_HERE"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
// add_action( 'pocono_child_aha_after_body', 'pocono_child_aha_add_google_tag_manager_noscript_tag' );

function cares_virt_child_add_google_tag_manager_script() {
	?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','ACCOUNT ID GOES HERE');</script>
	<!-- End Google Tag Manager -->
	<?php
}
// add_action( 'wp_head', 'pocono_child_aha_add_google_tag_manager_script' );
