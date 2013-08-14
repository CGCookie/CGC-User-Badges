<?php
/*
 * bbPress compatibility
 * Displays the user badges next to bbPress forum topics and replies
 */

function cgc_ub_bbpress_badges() {

	$reply_id = bbp_get_reply_id();

	if( empty( $reply_id ) )
		return;

	$author_id = bbp_get_reply_author_id( $reply_id );

	if( empty( $author_id ) )
		return;

	cgc_ub_show_user_badges( $author_id );

	cgc_ub_edd_purchased_badge( bbp_get_forum_id(), $author_id );
}
add_action( 'bbp_theme_after_reply_author_details', 'cgc_ub_bbpress_badges' );