<?php
/*
 * bbPress compatibility
 * Displays the user badges next to bbPress forum topics and replies
 */

function cgc_ub_bbpress_badges() {

	$reply_id = bbp_get_reply_id();

	$author_id = bbp_get_reply_author_id( $reply_id );


	cgc_ub_show_user_badges( $author_id );
}
add_action( 'bbp_theme_after_reply_author_admin_details', 'cgc_ub_bbpress_badges' );