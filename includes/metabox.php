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

	$selected = get_post_meta( get_the_ID(), 'cgc_related_edd_product', true );

	// Switch to hub site
	switch_to_blog( 1 );

	$products = get_posts( array(
		'post_type' => 'download',
		'nopaging'  => true,
		'orderby'   => 'title',
		'order'     => 'ASC'
	) );

	$options = array();

	if ( $products ) {
		foreach ( $products as $product ) {
			$options[ absint( $product->ID ) ] = esc_html( get_the_title( $product->ID ) );
		}
	} else {
		$options[0] = __( 'No products found', 'edd' );
	}

	echo EDD()->html->select( array(
		'name'             => 'edd_products',
		'selected'         => $selected,
		'options'          => $options,
		'show_option_all'  => false,
		'show_option_none' => __( 'None', 'edd' )
	) );

	restore_current_blog();

}

function cgc_related_download_save( $post_id ) {
	global $post;

	if ( ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) return $post_id;

	if ( isset( $post->post_type ) && $post->post_type == 'revision' )
		return $post_id;

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return $post_id;

	if( empty( $_POST['edd_products'] ) )
		delete_post_meta( $post_id, 'cgc_related_edd_product' );
	else
		update_post_meta( $post_id, 'cgc_related_edd_product', $_POST['edd_products'] );
}
add_action( 'save_post', 'cgc_related_download_save' );