<?php

function cgc_ub_conditionals_select() {
	echo cgc_ub_get_conditionals_select();
	die();
}
add_action('wp_ajax_cgc_ub_conditionals_select', 'cgc_ub_conditionals_select');