<?php

function cgc_ub_process_actions() {
	if(isset($_POST['cgc_ub_action'])) {
		do_action('cgc_ub_' . $_POST['cgc_ub_action'], $_POST);
	}
	if(isset($_GET['cgc_ub_action'])) {
		do_action('cgc_ub_' . $_GET['cgc_ub_action'], $_GET);
	}
}
add_action('admin_init', 'cgc_ub_process_actions');