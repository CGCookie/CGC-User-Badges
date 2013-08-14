<?php
/*
 * Metabox for selecting a related product
 *
 * This is for showing badges of verified buyers of the selected product
 */


function cgc_add_downloads_to_post_metabox() {
	global $post;

	if( is_main_site() )
		return;

	add_meta_box( 'related_download', 'Related Shop Product', 'cgc_render_related_download_metabox', 'post', 'side', 'default' );
	add_meta_box( 'related_download', 'Related Shop Product', 'cgc_render_related_download_metabox', 'forum', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'cgc_add_downloads_to_post_metabox' );

function cgc_render_related_download_metabox() {

	if( ! class_exists( 'Easy_Digital_Downloads' ) )
		return;

	// Switch to hub site
	switch_to_blog( 1 );

	echo EDD()->html->product_dropdown();

	restore_current_blog();

}