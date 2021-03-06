<?php

function cgc_ub_get_badges() {
	if ( is_multisite() ) {
		switch_to_blog( 1 );
	}
	$badges = get_option( 'cgc_ub_badges' );
	if ( false === $badges ) {
		add_option( 'cgc_ub_badges' );
	}
	if ( is_multisite() ) {
		restore_current_blog();
	}
	if ( $badges )
		return $badges;
	return false;
}

function cgc_ub_get_badge( $id ) {
	if ( is_multisite() ) {
		switch_to_blog( 1 );
	}

	$badges = cgc_ub_get_badges();
	if ( $badges ) {
		$badge = isset( $badges[$id] ) ? $badges[$id] : false;
	}

	if ( is_multisite() ) {
		restore_current_blog();
	}
	return $badge;
}

function cgc_ub_get_conditional_badges() {

	$badges = cgc_ub_get_badges();
	if ( $badges ) {
		$conditional_badges = array();
		foreach ( $badges as $id => $badge ) {
			if ( $badge['method'] == 'conditional' ) {
				$conditional_badges[$id] = $badge;
			}
		}
		if ( count( $conditional_badges >= 1 ) ) {
			return $conditional_badges;
		}
	}

	return false;
}

function cgc_ub_insert_badge( $data ) {
	if ( isset( $data['cgc_ub_add_nonce'] ) && wp_verify_nonce( $data['cgc_ub_add_nonce'], 'cgc_ub_add_nonce' ) ) {
		$badge = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'cgc_ub_add_nonce' && $key != 'cgc_ub_action' )
				$badge[$key] = strip_tags( addslashes( $value ) );
		}
		$save = cgc_store_badge( $badge );
	}
}
add_action( 'cgc_ub_add_badge', 'cgc_ub_insert_badge' );

function cgc_ub_edit_badge( $data ) {
	if ( isset( $data['cgc_ub_edit_nonce'] ) && wp_verify_nonce( $data['cgc_ub_edit_nonce'], 'cgc_ub_edit_nonce' ) ) {
		$badge = array();
		foreach ( $data as $key => $value ) {
			if ( $key != 'cgc_ub_edit_nonce' && $key != 'cgc_ub_action' && $key != 'badge_id' && $key != 'cgc_ub_redirect' )
				$badge[$key] = strip_tags( addslashes( $value ) );
		}
		if ( cgc_store_badge( $badge, $data['badge_id'] ) ) {
			wp_redirect( add_query_arg( 'cgc-message', 'badge_updated', $data['cgc_ub_redirect'] ) ); exit;
		} else {
			wp_redirect( add_query_arg( 'cgc-message', 'badge_update_failed', $data['cgc_ub_redirect'] ) ); exit;
		}
	}
}
add_action( 'cgc_ub_edit_badge', 'cgc_ub_edit_badge' );

/*
* Stores a badge.
* If the badge exists, it updates it, otherwise it creates a new one
*/
function cgc_store_badge( $badge_details, $id = null ) {
	if ( cgc_badge_exists( $id ) && !is_null( $id ) ) { // update an existing discount
		$badges = cgc_ub_get_badges();
		if ( !$badges ) $badges = array();
		$badges[$id] = $badge_details;
		update_option( 'cgc_ub_badges', $badges );

		return true; // badge updated

	} else { // add the badge
		$badges = cgc_ub_get_badges();
		if ( !$badges ) $badges = array();
		$badges[] = $badge_details;

		update_option( 'cgc_ub_badges', $badges );

		return true; // badge updated
	}

	return false; // something went wrong
}

function cgc_ub_call_delete( $data ) {
	if ( isset( $data['_wpnonce'] ) && wp_verify_nonce( $data['_wpnonce'], 'cgc_ub_delete' ) ) {
		cgc_delete_badge( $data['badge_id'] );
	}
}
add_action( 'cgc_ub_delete_badge', 'cgc_ub_call_delete' );

// removes a badge
function cgc_delete_badge( $badge_id ) {
	$badges = cgc_ub_get_badges();
	if ( $badges ) {
		unset( $badges[$badge_id] );
		update_option( 'cgc_ub_badges', $badges );
	}
}


// checks to see if a badge already exists
function cgc_badge_exists( $badge_id ) {
	$badges = cgc_ub_get_badges();

	if ( !$badges ) return false; // no badges, so does not exist

	if ( isset( $badges[$badge_id] ) ) return true; // a badge with this id has been found

	return false; // no badge with the specified ID exists
}

function cgc_ub_badge_method_label( $method ) {
	switch ( $method ) {
	case 'manual' :
		return __( 'Manual', 'cgc_ub' );
		break;
	case 'conditional' :
		return __( 'Conditional', 'cgc_ub' );
		break;
	}
}

function cgc_ub_get_conditions() {
	$conditions = apply_filters( 'cgc_ub_conditions', array(
			'is_citizen'         => __( 'Is Citizen User', 'cgc_ub' ),
			'is_author'          => __( 'Is an Author', 'cgc_ub' ),
			'has_won'            => __( 'Has Won a Contest', 'cgc_ub' ),
			'got_second'         => __( 'Got Second in a Contest', 'cgc_ub' ),
			'got_third'          => __( 'Got Third in a Contest', 'cgc_ub' ),
			'won_community_vote' => __( 'Won the Community Vote', 'cgc_ub' ),
			'has_been_featured'  => __( 'Has Been Featured in Gallery', 'cgc_ub' ),
			'workshop_attendee'  => __( 'Attended a Workshop', 'cgc_ub' ),
			'has_gallery_images' => __( 'Has Gallery Images', 'cgc_ub' ),
			'member_1_years'     => __( 'Member for One Year', 'cgc_ub' ),
			'member_2_years'     => __( 'Member for Two Years', 'cgc_ub' ),
			'member_3_years'     => __( 'Member for Three Years', 'cgc_ub' ),
			'member_4_years'     => __( 'Member for Four Years', 'cgc_ub' ),
			'member_5_years'     => __( 'Member for Five Years', 'cgc_ub' ),
			'member_6_years'     => __( 'Member for Six Years', 'cgc_ub' ),
			'member_7_years'     => __( 'Member for Seven Years', 'cgc_ub' ),
			'lifetime_member'    => __( 'Lifetime Member', 'cgc_ub' ),
			'beta_user'    => __( 'Beta User', 'cgc_ub' )
		)
	);
	return $conditions;
}

function cgc_ub_badge_condition_label( $condition ) {
	$conditions = cgc_ub_get_conditions();
	return $conditions[$condition];
}

function cgc_ub_get_conditionals_select( $selected = null ) {
	ob_start(); ?>
	<tr class="form-field" id="cgc_ub_conditionals">
		<th scope="row" valign="top">
			<label for="cgc_ub_conditional"><?php _e( 'Conditional', 'cgc_ub' ); ?></label>
		</th>
		<td>
			<select name="condition" id="cgc_ub_conditional">
				<?php
	$conditions = cgc_ub_get_conditions();
	foreach ( $conditions as $id => $condition ) {
		echo '<option value="' . $id . '" ' . selected( $selected, $id, false ) . '>' . $condition . '</option>';
	}
?>
			</select>
			<p class="description"><?php _e( 'Choose the condition to use for this badge.', 'cgc_ub' ); ?></p>
		</td>
	</tr>
	<?php
	return ob_get_clean();
}

function cgc_ub_edit_users_badges( $data ) {
	if ( isset( $data['cgc_ub_edit_nonce'] ) && wp_verify_nonce( $data['cgc_ub_edit_nonce'], 'cgc_ub_edit_nonce' ) ) {
		if ( isset( $data['user_id'] ) && is_numeric( $data['user_id'] ) ) {
			$badges = $data['cgc_ub_user_badges'];
			$user_id = $data['user_id'];
			if ( !empty( $badges ) ) {
				update_user_meta( $user_id, 'cgc_ub_badges', $badges );
			} else {
				delete_user_meta( $user_id, 'cgc_ub_badges' );
			}
		}
	}
}
add_action( 'cgc_ub_edit_user_badges', 'cgc_ub_edit_users_badges' );


function cgc_ub_show_user_badges( $user_id ) {
	$conditional_badges = cgc_ub_get_users_conditional_badges( $user_id );
	$manual_badges = cgc_ub_get_users_manual_badges( $user_id );
	$badges = array_merge( $conditional_badges, $manual_badges );
	$output = '<ul id="cgc_ub_user_badges" class="profile--badges cgc_ub_user_badges_' . $user_id . '">';
	foreach ( $badges as $badge_id ) {
		$badge = cgc_ub_get_badge( $badge_id );
		$output .= '<li><img src="' . $badge['image'] . '" class="cgc_ub_badge no-loader" title="' . $badge['name'] . '"/></li>';
	}
	$output .= '</ul>';
	echo $output;
}

function cgc_ub_get_users_conditional_badges( $user_id ) {

	$conditional_badges = cgc_ub_get_conditional_badges();
	if ( $conditional_badges ) {
		$badges = array();
		foreach ( $conditional_badges as $id => $badge ) {
			if ( cgc_ub_user_meets_condition( $user_id, $badge['condition'] ) ) {
				$badges[] = $id;
			}
		}
		if ( count( $badges ) >= 1 ) {
			return $badges;
		}
	}
	return array();
}

function cgc_ub_get_users_manual_badges( $user_id ) {
	$badges = get_user_meta( $user_id, 'cgc_ub_badges', true );
	if ( $badges ) {
		return $badges;
	}
	return array(); // user doesn't have any badges set
}

function cgc_ub_user_meets_condition( $user_id, $condition ) {
	$return = false; // default return
	if ( has_filter( 'cgc_ub_' . $condition ) ) {
		return apply_filters( 'cgc_ub_' . $condition, $return, $user_id );
	}
	return false;
}

// checks whether a user is citizen
function cgc_ub_condition_is_citizen( $return, $user_id ) {

	$return = false;

	$check = class_exists('cgcUserApi') ? cgcUserApi::is_user_citizen( $user_id ) : false;

	if ( $check ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_is_citizen', 'cgc_ub_condition_is_citizen', 10, 2 );

// checks whether a user is an author
function cgc_ub_condition_is_author( $return, $user_id ) {
	if ( user_can( $user_id, 'edit_posts' ) ) {
		$return = true;
	}
	return $return;
}
add_filter( 'cgc_ub_condition_is_author', 'cgc_ub_is_author', 10, 2 );

// checks whether as user has had a featured iamge
function cgc_ub_condition_has_been_featured( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	//delete_transient('cgc_user_' . $user_id . '_has_been_featured');
	$featured = false; // user has not been featured by default
	$featured = get_transient( 'cgc_user_' . $user_id . '_has_been_featured' );
	if ( false === $featured ) {
		foreach ( $network_sites as $site ) :
			if ( !$featured ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query' => array(
						array(
							'key' => 'pig_featured',
							'value' => 'on'
						)
					)
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$featured = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_been_featured', $featured, 7200 );
	}
	if ( $featured ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_has_been_featured', 'cgc_ub_condition_has_been_featured', 10, 2 );

// checks whether as user has won  contest
function cgc_ub_condition_has_won( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	//delete_transient('cgc_user_' . $user_id . '_has_won');
	$won = false; // user has not won by default
	$won = get_transient( 'cgc_user_' . $user_id . '_has_won' );
	if ( false === $won ) {
		foreach ( $network_sites as $site ) :
			if ( !$won ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'pig_won',
							'value' => 'on'
						),
						array(
							'key' => 'pig_contest_place',
							'value' => 'First'
						)
					)
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$won = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_won', $won, 7200 );
	}
	if ( $won ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_has_won', 'cgc_ub_condition_has_won', 10, 2 );

// checks whether as user has won second place
function cgc_ub_condition_got_second( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	//delete_transient('cgc_user_' . $user_id . '_has_got_second');
	$won = false; // user has not won by default
	$won = get_transient( 'cgc_user_' . $user_id . '_has_got_second' );
	if ( false === $won ) {
		foreach ( $network_sites as $site ) :
			if ( !$won ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'pig_won',
							'value' => 'on'
						),
						array(
							'key' => 'pig_contest_place',
							'value' => 'Second'
						)
					)
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$won = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_got_second', $won, 7200 );
	}
	if ( $won ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_got_second', 'cgc_ub_condition_got_second', 10, 2 );

// checks whether as user has won third place
function cgc_ub_condition_got_third( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	//delete_transient('cgc_user_' . $user_id . '_has_got_third');
	$won = false; // user has not won by default
	$won = get_transient( 'cgc_user_' . $user_id . '_has_got_third' );
	if ( false === $won ) {
		foreach ( $network_sites as $site ) :
			if ( !$won ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'pig_won',
							'value' => 'on'
						),
						array(
							'key' => 'pig_contest_place',
							'value' => 'Third'
						)
					)
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$won = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_got_third', $won, 7200 );
	}
	if ( $won ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_got_third', 'cgc_ub_condition_got_third', 10, 2 );

// checks whether as user has won the community vote
function cgc_ub_condition_won_community_vote( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	//delete_transient('cgc_user_' . $user_id . '_has_won_community');
	$won = false; // user has notwon by default
	$won = get_transient( 'cgc_user_' . $user_id . '_has_won_community' );
	if ( false === $won ) {
		foreach ( $network_sites as $site ) :
			if ( !$won ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key' => 'pig_won',
							'value' => 'on'
						),
						array(
							'key' => 'pig_contest_place',
							'value' => 'Community Vote'
						)
					)
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$won = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_won_community', $won, 7200 );
	}
	if ( $won ) {
		$return = true;
	}

	return $return;
}
add_filter( 'cgc_ub_won_community_vote', 'cgc_ub_condition_won_community_vote', 10, 2 );

// checks whether as user has images in the gallery
function cgc_ub_condition_has_gallery_images( $return, $user_id ) {

	// get all network sites
	$network_sites = get_transient( 'cgc_network_sites' );
	if ( false === $network_sites ) {
		$network_sites = get_blogs_of_user( 1, false );
		set_transient( 'cgc_network_sites', $network_sites, 3600 );
	}
	$has_images = false;
	$has_images = get_transient( 'cgc_user_' . $user_id . '_has_gallery_images' );
	if ( false === $has_images ) {
		foreach ( $network_sites as $site ) :
			if ( !$has_images ) {
				switch_to_blog( $site->userblog_id );

				$image_args = array(
					'author' => $user_id,
					'post_type' => 'images',
					'posts_per_page' => 1,
					'fields' => 'ids',
					'cache_results' => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false
				);
				$the_query = new WP_Query( $image_args );
				if ( $the_query->have_posts() ) :
					$has_images = true;
				endif;
				restore_current_blog();
			}
		endforeach;
		set_transient( 'cgc_user_' . $user_id . '_has_gallery_images', $has_images, 7200 );
	}
	return $has_images;
}
add_filter( 'cgc_ub_has_gallery_images', 'cgc_ub_condition_has_gallery_images', 10, 2 );


// checks whether a user has ever registered for a workshop
function cgc_ub_condition_workshop_attendee( $return, $user_id ) {

	$return = false;
	$attended = false; // user has not attended by default
	//$attended = get_transient( 'cgc_user_' . $user_id . '_workshop_attendee' );
	if ( false === $attended ) {

		$user_data = get_userdata( $user_id );
		$email = $user_data->user_email;

		switch_to_blog( 15 );
		global $wpdb;
		$purchased = $wpdb->get_var( $wpdb->prepare( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '_cgc_payment_user_email' AND meta_value = '%s'", $email ) );
		if ( ! empty( $purchased ) ) {
			$attended = true;
		}
		restore_current_blog();

		set_transient( 'cgc_user_' . $user_id . '_workshop_attendee', $attended, 7200 );
	}
	if ( $attended )
		$return = true;

	return $return;
}
//add_filter( 'cgc_ub_workshop_attendee', 'cgc_ub_condition_workshop_attendee', 10, 2 );


function cgc_ub_condition_1_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-1 year' ) && $register_date > strtotime( '-2 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_1_years', 'cgc_ub_condition_1_year', 10, 2 );


function cgc_ub_condition_2_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-2 year' ) && $register_date > strtotime( '-3 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_2_years', 'cgc_ub_condition_2_year', 10, 2 );


function cgc_ub_condition_3_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-3 year' ) && $register_date > strtotime( '-4 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_3_years', 'cgc_ub_condition_3_year', 10, 2 );


function cgc_ub_condition_4_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-4 year' ) && $register_date > strtotime( '-5 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_4_years', 'cgc_ub_condition_4_year', 10, 2 );


function cgc_ub_condition_5_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-5 year' ) && $register_date > strtotime( '-6 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_5_years', 'cgc_ub_condition_5_year', 10, 2 );


function cgc_ub_condition_6_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-6 year' ) && $register_date > strtotime( '-7 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_6_years', 'cgc_ub_condition_6_year', 10, 2 );


function cgc_ub_condition_7_year( $return, $user_id ) {

	$return        = false;
	$user_data     = get_userdata( $user_id );
	$register_date = strtotime( $user_data->user_registered );

	if ( $register_date < strtotime( '-7 year' ) && $register_date > strtotime( '-8 years' ) )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_member_7_years', 'cgc_ub_condition_7_year', 10, 2 );


function cgc_ub_condition_lifetime_member( $return, $user_id ) {

	if( ! function_exists( 'rcp_get_subscription_id' ) )
		return $return;

	$subscription_id = rcp_get_subscription_id( $user_id );

	if( 10 == $subscription_id )
		$return = true;

	return $return;
}
add_filter( 'cgc_ub_lifetime_member', 'cgc_ub_condition_lifetime_member', 10, 2 );

// checks whether a user is a beta user
function cgc_ub_beta_user( $return, $user_id ) {

	if ( !class_exists('cgcUserApi') )
		return;

	if ( true == cgcUserApi::is_user_beta_user( $user_id ) ) {
		$return = true;
	}
	return $return;
}
add_filter( 'cgc_ub_beta_user', 'cgc_ub_beta_user', 10, 2 );
