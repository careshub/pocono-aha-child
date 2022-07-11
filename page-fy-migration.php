<?php

get_header(); ?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php //comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>
<pre><?php



/// Move unfinished pipeline items to the new year.
// Pipeline items: approved and have no progress item or have a “still working” progress item.
// Pipeline items: “still working” (shouldn’t have children)
// Progress items: progress items that are “still working” (bring parent, too)  - Will need to keep parent relationships intact (should be fine as long as we are not duplicating items)
// Tag anything tagged as FY21 target completion date as FY22
// Port org name and clinic id to new format

// Check
// Last year PSEs that are not approved should be 0 (yep)
// Last year progress items that are not approved should be 0 (nope, found 35)
// This year progress items that are approved should be 0 (yep)

// Cleanup
// Find progress items that are 2022 and not approved, get parent ids check parent status-- trashed?
$prog_ids = get_unapproved_progress_items();
// var_dump( count($prog_ids), $prog_ids );
print_r( "Incomplete progress items/progress items with incomplete parent PSEs" . "\n" );
print_r( "progress item ids: " .implode( ",", $prog_ids ) );
$progs = get_progress_items_by_ids( $prog_ids );

foreach ( $progs as $prog ) {
	$approval = get_the_terms( $prog, 'aha-review-status' );
	$a = current( $approval )->name;
	$parent_meta = get_post_meta( $prog, 'parent_item', true );
	print_r( "\n $prog->ID  $prog->post_title $prog->post_status  $a $parent_meta" );
}

$parents = get_parent_item_meta_for_items( $prog_ids );
print_r( "parent PSEs: " . implode( ",", $parents ) );

// var_dump( count($parents), $parents );
$parent_posts = get_pipelines_by_ids( $parents );
foreach ( $parent_posts as $parent ) {
	$approval = get_the_terms( $parent,	'aha-review-status' );
	$a = current( $approval )->name;

	print_r( "\n $parent->ID  $parent->post_title $parent->post_status $a" );
}

// Migrate a certain number of PSEs and their associated progress items in one go.
run_pse_migration();
function run_pse_migration( $dry_run = true ) {

	$pses_to_migrate = get_ids_of_pses_to_migrate( $dry_run );
	print_r( "\nIncomplete PSEs, count " . count( $pses_to_migrate ) . "\n" );
	print_r( "Incomplete PSEs, ids" );
	print_r( "\n" . implode( ', ', $pses_to_migrate ) );

	$assoc_progress_items = array();
	if ( $pses_to_migrate ) {
		$assoc_progress_items = get_ids_of_progress_items_to_migrate( $pses_to_migrate );
	}
	print_r( "\nProgress items assoc with thse incomplete PSEs, count " . count( $assoc_progress_items ) . "\n" );
	print_r( "Progress Items, ids" );
	print_r( "\n" . implode( ', ', $assoc_progress_items ) );

	if ( false === $dry_run ) {
		if ( $pses_to_migrate ) {
			migrate_pses(  $pses_to_migrate );
		}

		if ( $assoc_progress_items ) {
			migrate_progress_items(  $assoc_progress_items );
		}
	}
}

// Migrate a group of PSEs from this year to next year.
function migrate_pses( $ids = array() ) {
	// Update some pieces to new formats.
	foreach ( $ids as $post_id ) {
		wp_set_object_terms( $post_id, '2023', 'aha-benchmark-date-term' );

		// Update anything with a FY2022 target completion date as FY2023.
		$fy_achieve_goal = get_post_meta( $post_id, 'fy_achieve_goal', true );
		if ( 'FY2023' === $fy_achieve_goal ) {
			update_post_meta( $post_id, 'fy_achieve_goal', 'FY2023' );
		}

		// No meta format changes for 22-23. For examples, see 21-22 version.

	}

}

// Migrate a group of Progress Items from this year to next year.
function migrate_progress_items( $ids = array() ) {
	// Update benchmark date term.
	foreach ( $ids as $post_id ) {
		wp_set_object_terms( $post_id, '2023', 'aha-benchmark-date-term' );
	}
}

// This is the key piece--finding which PSEs need to be migrated.
function get_ids_of_pses_to_migrate( $return_all = false ) {
	$prog_ids = array(560197,560163,560130,559974,559814,559678,559645,559522,559511,559471,559470,559322,559119,558909,558876,558870,558813,558796,558555,558505,558368,558241,557600,557441,557403,557360,557358,557357,557260,557115,557058,557056,556708,556688);
	$pse_ids = array(555602,555603,554804,554804,556663,555409,557195,557195,557194,556037,557013,556099,555973,557907,555746,556251,557006,558772,558380,557228,557517,557897,555999,557710,557710,557802,555230,556844,556930,559275,559436,559140,559138,557544);

	// Get all PSEs for this year.
	$all_pses = get_all_pipeline_ids();
	print_r( "\nAll PSEs, count " . count( $all_pses ) . "\n" );

	// var_dump( "\nleftovers? ", array_diff( $pse_ids, $all_pses ) );

	// Get all approved progress items.
	$approved_progress_items = get_approved_progress_items();
	print_r( "\nApproved progress items, count " . count( $approved_progress_items ) . "\n" );
	// var_dump( "\nleftovers progs? ", array_diff( $prog_ids, $approved_progress_items ) );

	// From the approved progress items, get their parent_item meta.
	// These IDs are the "approved" or completed PSEs.
	$completed_pses  = get_parent_item_meta_for_items( $approved_progress_items );
	// var_dump( "\nleftovers pses? ", array_diff( $pse_ids, $completed_pses ) );

	// Remove the complete PSE IDs from the all PSE array, and you are left with the "incomplete" PSEs.
	$incomplete_pses = array_diff( $all_pses, $completed_pses );

	// Return them all or return a subset. We use subsets for the migration process to avoid time outs.
	if ( $return_all ) {
		return $incomplete_pses;
	} else {
		// Return the first so many.
		return array_slice( $incomplete_pses, 0, 50 );
	}
}

// Find the Progress Items that are associated with a set of PSEs.
function get_ids_of_progress_items_to_migrate( $parent_ids = array() ) {
	global $wpdb;

	$args = array(
		'post_type'      => 'aha-action-progress',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'     => 'parent_item',
				'value'   => $parent_ids,
				'compare' => 'IN',
			),
		),
	);
	$progs    = new WP_Query( $args );
	$post_ids = $progs->posts;
	return $post_ids;
}


// Get IDs of all pipeline items for this year.
	// post_type = aha_pse_goal
	// -> aha-benchmark-date-term = 2022
function get_all_pipeline_ids() {
	global $wpdb;
	$year_to_update = 2022;
	$args = array(
		'post_type'      => 'aha_pse_goal',
		// 'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'tax_query'      => array(
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'slug',
				'terms'    => (string) $year_to_update,
			),
		),
	);
	$priorities = new WP_Query( $args );
	$post_ids   = $priorities->posts;
	return $post_ids;
}

function get_pipelines_by_ids( $post_ids = array() ) {
	if ( empty( $post_ids ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'aha_pse_goal',
		// 'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		// 'fields'         => 'ids',
		'post__in'       => $post_ids,
	);
	$priorities = new WP_Query( $args );
	$pris       = $priorities->posts;
	return $pris;

}

function get_progress_items_by_ids( $post_ids = array() ) {
	if ( empty( $post_ids ) ) {
		return array();
	}

	$args = array(
		'post_type'      => 'aha-action-progress',
		// 'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		// 'fields'         => 'ids',
		'post__in'       => $post_ids,
	);
	$priorities = new WP_Query( $args );
	$pris       = $priorities->posts;
	return $pris;

}

// Get "approved" progress item IDs for this year
	// post_type = aha-action-progress
	// -> aha-benchmark-date-term = 2022
	// -> aha-review-status = approved
function get_approved_progress_items( $page = 1 ) {
	global $wpdb;

	$year_to_update = 2022;
	$args = array(
		'post_type'      => 'aha-action-progress',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		'paged'          => $page,
		'fields'         => 'ids',
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'slug',
				'terms'    => (string) $year_to_update,
			),
			array(
				'taxonomy' => 'aha-review-status',
				'field'    => 'slug',
				'terms'    => 'approved',
			),
		),
	);
	$priorities = new WP_Query( $args );
	$post_ids   = $priorities->posts;
	return $post_ids;
}

function get_unapproved_progress_items( $page = 1 ) {
	global $wpdb;

	$year_to_update = 2022;
	$args = array(
		'post_type'      => 'aha-action-progress',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => -1,
		'paged'          => $page,
		'fields'         => 'ids',
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'slug',
				'terms'    => (string) $year_to_update,
			),
			array(
				'taxonomy' => 'aha-review-status',
				'field'    => 'slug',
				'terms'    => 'approved',
				'operator' => 'NOT IN'
			),
		),
	);
	$priorities = new WP_Query( $args );
	$post_ids   = $priorities->posts;
	return $post_ids;
}

// Do a single query to fetch all parent_item values for a list of progress item IDs
function get_parent_item_meta_for_items( $post_ids = array() ) {
	global $wpdb;

	$post_ids_strings = implode( ", ",  $post_ids );
	$sel_query = "SELECT meta_value FROM $wpdb->postmeta WHERE meta_key='parent_item' AND post_id IN ( {$post_ids_strings} )";
	$parent_ids = $wpdb->get_col( $sel_query );
	print_r( "\nCompleted PSEs num " . count( $parent_ids ) . "\n" );
	// print_r( implode( ", ",  $parent_ids ) . "\n" );
	return $parent_ids;
}

// Duplicate this year's strategy summaries over to the new year.
// duplicate_strategy_summaries( 9 );
function duplicate_strategy_summaries( $page = 1 ) {
	global $wpdb;
	$year_to_update = 2022;
	$args = array(
		'post_type'      => 'aha-priority',
		'post_status'    => array( 'publish' ),
		'posts_per_page' => 50,
		'paged'           => $page,
		'fields'         => 'ids',
		'tax_query'      => array(
			array(
				'taxonomy' => 'aha-benchmark-date-term',
				'field'    => 'slug',
				'terms'    => (string) $year_to_update,
			),
		),
	);
	$priorities = new WP_Query( $args );
	$post_ids   = $priorities->posts;

	foreach ( $post_ids as $post_id ) {
		// Copy basic post data
		$post = get_post( $post_id );

		print_r( "\nStarting dupe of " . $post_id );
		if ( isset( $post ) && $post != null ) {
			// Don't double duplicate.
			$args = array(
				'meta_key'   => 'duplicated_from',
				'meta_value' => $post_id,
				'post_type'  => $post->post_type,
				'fields'     => 'ids'
			);
			$dupes = new WP_Query( $args );
			if ( $dupes->have_posts() ) {
				$new_post_id = current( $dupes->posts );
				print_r( "\nFound dupe for post {$post_id}: {$new_post_id} ");
				continue;
			} else {
				$args = array(
					'comment_status' => $post->comment_status,
					'ping_status'    => $post->ping_status,
					'post_author'    => $post->post_author,
					'post_content'   => $post->post_content,
					'post_excerpt'   => $post->post_excerpt,
					'post_name'      => $post->post_name,
					'post_parent'    => $post->post_parent,
					'post_password'  => $post->post_password,
					'post_status'    => $post->post_status,
					'post_title'     => $post->post_title,
					'post_type'      => $post->post_type,
					'to_ping'        => $post->to_ping,
					'menu_order'     => $post->menu_order
				);

				$new_post_id = wp_insert_post( $args );
				print_r( "\nInserted " . $new_post_id );
			}

			// Set terms.
			$taxonomies = get_object_taxonomies( $post->post_type ); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ( $taxonomies as $taxonomy ) {
				if ( 'aha-benchmark-date-term' === $taxonomy ) {
					// We update this to the new year.
					$new_year = $year_to_update + 1;
					$success = wp_set_object_terms( $new_post_id, (string) $new_year, $taxonomy, false );
					// print_r( "\nadded object term {$taxonomy} success new year term " . (string) $new_year );
					// var_dump( $success );
				} else {
					$post_terms = wp_get_object_terms( $post_id, $taxonomy, array('fields' => 'slugs') );
					$success = wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
					// print_r( "\nadded object term {$taxonomy} success ");
					// var_dump( $success );
				}
			}

			// Set post meta.
			$post_meta_infos = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id" );
			if ( ! is_array( $post_meta_infos ) ) {
				$post_meta_infos = array();
			}
			$dupe_from             = new stdClass();
			$dupe_from->meta_key   = 'duplicated_from';
			$dupe_from->meta_value = $post_id;
			$post_meta_infos[]     = $dupe_from;

			// print_r( "\npost_meta_infos " );
			// var_dump( $post_meta_infos );

			if ( count( $post_meta_infos ) != 0 ) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) VALUES ";
				$sql_query_sel = array();
				foreach ( $post_meta_infos as $meta_info ) {
					$meta_key = $meta_info->meta_key;
					if ( in_array( $meta_key, array( '_edit_lock', '_wp_old_slug' ), true ) ) {
						continue;
					}
					// $meta_value = addslashes( $meta_info->meta_value );
					$sql_query_sel[]= $wpdb->prepare( "( %d, %s, %s )", $new_post_id, $meta_key, $meta_info->meta_value );
				}
				$sql_query.= implode( ", ", $sql_query_sel );
				$wpdb->query($sql_query);
				// var_dump( "added object meta via query ", $sql_query );
			}
		}
	}
}

// update centroids and county lists from a CSV.
// import_board_geo_data();
function import_board_geo_data( $dry_run = true ) {
	if ( ( $handle = fopen(cc_aha_get_plugin_base_path() . "working/2023-fy/AHA2021_Centroids.csv", "r") ) !== FALSE ) {
		$row = 1;
		while ( ( $data = fgetcsv( $handle, 1000, "," ) ) !== FALSE ) {

			// Find board.
			$board = cc_aha_get_board_by_id( $data[2] );

			if ( empty( $board->ID ) ) {
				echo "Missed Board ID: {$data[2]}\n";
				continue;
			}

			// Update the centroid.
			$long    = round( $data[5], 4 );
			$lat     = round( $data[4], 4 );
			$longlat = "{$long},{$lat}";

			// Update the countylist
			$counties = preg_split( '/(?<=, [A-Z]{2}),/', $data[3]);
			$counties = array_map( 'trim', $counties );
			$num      = count( $counties  );
			if ( $num > 1 ) {
				foreach ( $counties as $key => $county ) {
					// Remove the State, if the next entry shares the same state.
					if ( $key < ( $num - 1 ) && substr( $counties[ $key + 1 ], -2 ) === substr( $county, -2 ) ) {
						$counties[$key] = substr_replace( trim( $county ), '', -4 );
					} else {
						$county = trim( $county );
						$counties[$key] = "and {$county}";
					}
				}
			}
			$county_text = implode( ', ', $counties );

			if ( $dry_run ) {
				// Test data
				echo "POST ID: {$board->ID} | name: {$data[1]} | bid: {$data[2]} | longlat: {$longlat} | countylist: {$county_text}<br />\n";
			} else {
				echo "Updating Board ID: {$data[2]}\n";
				// Process data
				var_dump( "update longlat", $board->ID, update_post_meta( $board->ID, 'longlat', $longlat ) );
				var_dump( "update countylist", $board->ID, update_post_meta( $board->ID, 'countylist', $county_text ) );
			}

			$row++;
		}
		fclose($handle);
	}
}



?>
</pre>


		</div>
	</div>

<?php get_footer(); ?>
