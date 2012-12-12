<?php

function cgc_ub_action_links($actions, $user_object) {
	$actions['edit_badges'] = '<a href="' . wp_nonce_url( 
		add_query_arg(
			'cgc_ub_page', 
			'edit_users_badges', 
			add_query_arg(
				'user', 
				$user_object->ID,
				add_query_arg(
					'page',
					'cgc-badges'
				)
			)
		), 
		'cgc_ub_edit'
	) . '">' . __('Edit Badges', 'cgc_ub') . '</a>';
	return $actions;
}
if(is_main_site()) {
	add_filter('user_row_actions', 'cgc_ub_action_links', 10, 2);
}