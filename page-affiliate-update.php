<?php

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php //comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

<pre>
<?php

// Pre 1 - add the affiliate and state for those that are missing.
// find_unaffiliated_progress_items();
function find_unaffiliated_progress_items() {
	$args = array(
	    'post_type' => array( 'aha-action-progress' ),
	    'post_status' => 'any',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'aha-affiliate-term',
	            'operator' => 'NOT EXISTS',
	        ),
	    ),
	    'posts_per_page' => 500,
	    'page'     => 1,
	);
	$query = new WP_Query( $args );

	if ( empty( $query->posts ) ) {
		print_r( "Nothing found" );
		return;
	} else {
		var_dump( $query->posts );
		$cpt = new CC_AHA_Progress_Reports_CPT_Tax();

		foreach ( $query->posts as $post ) {
			// var_dump( PHP_EOL . "Post: " . $post->ID . $cpt->add_affiliate_and_state( $post ) );
		}

	}
}

// Pre 2
delete_silicon_area_items();
function delete_silicon_area_items(){
	$args = array(
	    'post_type' => array( 'aha_board', 'aha-action-step', 'aha-priority', 'aha-action-progress' ),
	    'post_status' => 'any',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'aha-board-term',
	            'field'    => 'slug',
	            'terms'    => 'WSA19',
	        ),
	    ),
	    'posts_per_page' => 100,
	    'page'     => 1,
	    'fields'   => 'ids'
	);
	$query = new WP_Query( $args );

	foreach ( $query->posts as $post_id ) {
		print_r( PHP_EOL . $post_id . ": " );
		var_dump( wp_delete_post( $post_id ) );
	}
}

// 1. Create the new terms
// aha_add_new_terms();
function aha_add_new_terms() {
	$new_terms = array(	'Western States', 'Southwest', 'Midwest', 'Southeast', 'Eastern States' );

	foreach ($new_terms as $term_name) {
		if ( ! term_exists( $term_name, 'aha-region-term' ) ) {
			var_dump( PHP_EOL . wp_insert_term(	$term_name, 'aha-region-term' ) );
		}
	}
}

// Direct translation
// terms               => new terms
// 'founders' => 'eastern-states',
// 'greater-southeast' => 'southeast',
// 'midwest-wyoming'   => 'midwest',
// 'southwest'         => 'southwest',
// 'western-states'    => 'western-states',

// More complicated
// 'great-rivers' => (midwest, eastern)
// 'mid-atlantic'	=> (southeast, eastern states)

// array( 'founders', 'greater-southeast', 'midwest-wyoming', 'southwest', 'western-states' )
// update_affilates_direct( 'founders',  1 );
// update_affilates_direct( 'greater-southeast',  1 );
// update_affilates_direct( 'midwest-wyoming',  1 );
// update_affilates_direct( 'southwest',  1 );
// update_affilates_direct( 'western-states',  1 );

function update_affilates_direct( $old_term, $page = 1 ) {
	$args = array(
	    'post_type' => array( 'aha_board', 'aha-action-step', 'aha-priority', 'aha-action-progress' ),
	    'post_status' => 'any',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'aha-affiliate-term',
	            'field'    => 'slug',
	            'terms'    => $old_term,
	        ),
	    ),
	    'posts_per_page' => 500,
	    'page'     => $page,
	    'fields'   => 'ids'
	);
	$query = new WP_Query( $args );

	if ( empty( $query->posts ) ) {
		print_r( "Nothing found" );
		return;
	}

	// Get Term ID for setting new term
	$term_map = array(
		'founders'          => 'eastern-states',
		'greater-southeast' => 'southeast',
		'midwest-wyoming'   => 'midwest',
		'southwest'         => 'southwest',
		'western-states'    => 'western-states',
	);

	$term = term_exists( $term_map[ $old_term ], 'aha-region-term' );
	if ( ! $term ) {
		print_r( "No term found" );
		return;
	}
	$term_id = (int) $term['term_id'];

	foreach ( $query->posts as $post_id ) {
		print_r( PHP_EOL . $post_id . ": " );
		var_dump( wp_set_object_terms( $post_id, $term_id, 'aha-region-term' ) );
	}

	$togo = $query->found_posts - 500 * $page;
	echo PHP_EOL . "All done. {$togo} more to go";
}


// update_affilates_by_state( 'great-rivers', 'oh',  1 );
// update_affilates_by_state( 'great-rivers', 'de',  1 );
// update_affilates_by_state( 'mid-atlantic', 'nc',  1 );
// update_affilates_by_state( 'mid-atlantic', 'dc',  1 );
function update_affilates_by_state( $old_term, $state_key, $page = 1 ) {
	switch ( $state_key ) {
		case 'oh':
			$states = array( 'oh', 'ky' );
			$new_term = 'midwest';
			break;
		case 'de':
			$states = array( 'de', 'pa', 'wv' );
			$new_term = 'eastern-states';
			break;
		case 'nc':
			$states = array( 'nc', 'sc' );
			$new_term = 'southeast';
			break;
		case 'dc':
			$states = array( 'dc', 'md', 'va' );
			$new_term = 'eastern-states';
			break;

		default:
			# code...
			break;
	}

	$args = array(
	    'post_type' => array( 'aha_board', 'aha-action-step', 'aha-priority', 'aha-action-progress' ),
	    'post_status' => 'any',
	    'tax_query' => array(
	        array(
	            'taxonomy' => 'aha-affiliate-term',
	            'field'    => 'slug',
	            'terms'    => $old_term,
	        ),
	        array(
	            'taxonomy' => 'aha-state-term',
	            'field'    => 'slug',
	            'terms'    => $states,
	        ),
	    ),
	    'posts_per_page' => 500,
	    'page'     => $page,
	    'fields'   => 'ids'
	);
	$query = new WP_Query( $args );

	if ( empty( $query->posts ) ) {
		print_r( "Nothing found" );
		return;
	}

	$term = term_exists( $new_term, 'aha-region-term' );
	if ( ! $term ) {
		print_r( "No term found" );
		return;
	}
	$term_id = (int) $term['term_id'];

	foreach ( $query->posts as $post_id ) {
		print_r( PHP_EOL . $post_id . ": " );
		var_dump( wp_set_object_terms( $post_id, $term_id, 'aha-region-term' ) );
	}

	$togo = $query->found_posts - 500 * $page;
	echo PHP_EOL . "All done. {$togo} more to go";
}

?>
</pre>

		</div>
	</div>

<?php get_footer(); ?>
