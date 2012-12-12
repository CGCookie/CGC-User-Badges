<?php

function cgc_ub_load_admin_scripts($hook) {
	global $cgc_ub_badges_page;
	if($hook == $cgc_ub_badges_page) {
		wp_enqueue_script('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('badges-admin', CGC_UB_PLUGIN_URL . 'js/badges-admin.js');
	}
}
add_action('admin_enqueue_scripts', 'cgc_ub_load_admin_scripts');