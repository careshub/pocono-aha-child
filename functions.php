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

/**
 * Add the required heart.org header.
 *
 * @since 1.0.0
 *
 */
function add_aha_required_header() {
	?>
	<script type="text/javascript">
    // HEADER OPTIONS - - - - - - - - - - - - -
    // This will load Twitter Bootstrap v3.3.0
    var ahaHeaderLoadBootstrap = false;
    var ahaLogo = true; // Show the Heart and Torch logo in the utility bar
    var ahaDonateShow = false;//set to false to remove donate button
    // Optional add campaign code to donate button
    var ahaDonateCampaignCode = '';//String
    var ahaDonateCustomLink = '';//Custom url instead of AHA standard donate link, example, http://donatenow.heart.org
    // Go to http://americanheartassociation.github.io/docs/new-header-footer.html for example
    var ahaSearch = false; //Optional AHA search, uncomment and set to true for search
    // Optional custom search url search outside of heart.org as default
    // If blank then will default to '//www.heart.org/HEARTORG/search/searchResults.jsp'
    var ahaSearchURL = '';
    // Optional custom search form html
    // If leave blank then will default to AHA approved search form html
    var ahaCustomSearchFormHTML = '';
    // Example of form
    // <form method="" action="" id="name=""><label for=""><input name="" id="" type="text" placeholder="My Search"><button type="submit" id="" class="btn btn-primary">Submit</button></form>
    // Uncomment if want header to appear
    //Sub logo image has to be max-height at 80px
    // var subLogoHeaderOptions = {
    //     'subLink':'http://professional.heart.org/professional/index.jsp',//required
    //     'subImgPath':'//static.heart.org/ahaanywhere/responsive/img/AHALogo_full_red_blk@2x.png',//required
    //     'subImgAltText':'Professional Heart Daily',//required
    //     'subText':''//optional
    // };
	</script>
	<script src="//static.heart.org/ahaanywhere/responsive/js/aha-header-external-responsive.v2.js" type="text/javascript"></script>
	<?php
}
add_action( 'pocono_child_aha_after_body', 'add_aha_required_header' );

/**
 * Add the required heart.org header.
 *
 * @since 1.0.0
 *
 */
function add_aha_required_footer() {
	?>
	<script type="text/javascript">
    // FOOTER OPTIONS - - - - - - - - - - - - - - -
    // This is for the content in the yellow region
    // Comment out if not needed
    var footer_info_Object = [
        {
            "header":{"content":"Technical Support"},
            "body":{"content":"David Cavins<br>(573) 882-7458<br><a href='cavinsd@missouri.edu'>cavinsd@missouri.edu</a>"}
        },
        {
            "header":{"content":"Impact Central Support"},
            "body":{"content":"Cherish Hart<br>7272 Greenville Ave, Dallas, TX 75231<br>(360) 471-5273<br><a href='mailto:cherish.hart@heart.org'>cherish.hart@heart.org</a>"}
        }
    ];
    //Customize your contact info under the logo
    //Uncomment if you want to customize the contact info
    //Comment out OR delete the footer_contact block if you want to use default contact info
    // Set true to show AHA dual logo with Heart & Stroke, set false to display AHA Heart logo
    var showDualLogo = false;
    // Set to true if need to inject Twitter Bootstrap
    var ahaFooterLoadBootstrap = false;
	</script>
	<script id="footerScriptTag" src="//static.heart.org/ahaanywhere/responsive/js/aha-footer-external-responsive.v2.js" data-site-contact="true" type="text/javascript"></script>

	<?php
}
add_action( 'wp_footer', 'add_aha_required_footer', 88 );
