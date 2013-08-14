<?php
/*
 * Has purchased product badges
 *
 * Only shown to moderators and admins
 */
function cgc_ub_edd_purchased_badge( $post_id = 0 ) {
	$related_product = get_post_meta( $post_id, 'cgc_related_edd_product', true );

	if( empty( $related_product ) )
		return;

	switch_to_blog( 1 );

	echo '<div id="purchased_edd_product">';
		echo '<span>' . get_the_title( $related_product ) . '</span>';
		if( edd_has_user_purchased( get_current_user_id(), $related_product ) ) {
			echo '&nbsp;<em>yes</em>';
		} else {
			echo '&nbsp;<em>no</em>';
		}
	echo '</div>';

	restore_current_blog();
}