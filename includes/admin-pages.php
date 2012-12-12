<?php

function cgc_ub_admin_menu() {
	global $cgc_ub_badges_page;
	
	if(is_main_site()) {
		$cgc_ub_badges_page = add_users_page(__('User Badges', 'cgc_ub'), __('User Badges', 'cgc_ub'), 'manage_options', 'cgc-badges', 'cgc_ub_admin_page');
	}
}
add_action('admin_menu', 'cgc_ub_admin_menu', 10);

function cgc_ub_admin_page() {
	
	if(isset($_GET['cgc_ub_page']) && wp_verify_nonce($_GET['_wpnonce'], 'cgc_ub_edit')) { 
		switch($_GET['cgc_ub_page']) {
			case 'edit_badge' :
				include(CGC_UB_PLUGIN_DIR . '/includes/edit-badge.php');
				break;
			case 'edit_users_badges' :
				include(CGC_UB_PLUGIN_DIR . '/includes/edit-users-badges.php');
				break;
		}
	} else {
		ob_start(); ?>
		<div class="wrap">
			<h2><?php _e('User Badges', 'cgc_ub'); ?></h2>
			<table class="wp-list-table widefat fixed posts cgc_ub-payments">
				<thead>
					<tr>
						<th style="width: 60px;"><?php _e('ID', 'cgc_ub'); ?></th>
						<th><?php _e('Name', 'cgc_ub'); ?>
						<th><?php _e('Image', 'cgc_ub'); ?>
						<th><?php _e('Method', 'cgc_ub'); ?>
						<th><?php _e('Condition', 'cgc_ub'); ?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="width: 60px;"><?php _e('ID', 'cgc_ub'); ?></th>
						<th><?php _e('name', 'cgc_ub'); ?>
						<th><?php _e('Image', 'cgc_ub'); ?>
						<th><?php _e('Method', 'cgc_ub'); ?>
						<th><?php _e('Condition', 'cgc_ub'); ?>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$badges = cgc_ub_get_badges();
					if($badges) { 
						foreach($badges as $id => $badge) { ?>
						<tr>
							<td><?php echo $id; ?></td>
							<td>
								<?php echo $badge['name']; ?>
								<div class="row-actions">
									<?php 
									$row_actions = array(
										'edit' => '<a href="' . wp_nonce_url( add_query_arg('cgc_ub_page', 'edit_badge', add_query_arg('badge_id', $id)), 'cgc_ub_edit') . '">' . __('Edit', 'edd') . '</a>',
										'delete' => '<a href="' . wp_nonce_url(add_query_arg('cgc_ub_action', 'delete_badge', add_query_arg('badge_id', $id)), 'cgc_ub_delete') . '">' . __('Delete', 'edd') . '</a>'
									);
									$row_actions = apply_filters('cgc_ub_badge_row_actions', $row_actions, $$badge);
									$action_count = count($row_actions); $i = 1;
									foreach($row_actions as $key => $action) {
										if($action_count == $i) { $sep = ''; } else { $sep = ' | '; }
										echo '<span class="' . $key . '">' . $action . '</span>' . $sep;
										$i++;
									}
									?>
								</div>
							</td>
							<td><?php if(isset($badge['image'])) { echo '<img src="' . $badge['image'] . '"/>'; } ?></td>
							<td><?php if(isset($badge['method'])) { echo cgc_ub_badge_method_label($badge['method']); } ?></td>
							<td><?php if(isset($badge['condition'])) { echo cgc_ub_badge_condition_label($badge['condition']); } else { echo __('None', 'cgc_ub'); } ?></td>
						</tr>
						<?php 
						}
					} else { ?>
					<tr><td colspan=3><?php _e('No badges have been created yet.', 'cgc_ub'); ?></td></td>
					<?php } ?>
				</tbody>
			</table>	
			<h3><?php _e('Add New Badge', 'cgc_ub'); ?></h3>
			<form id="cgc_ub-add-discount" action="" method="POST">
				<table class="form-table">
					<tbody>
						<tr class="form-field">
							<th scope="row" valign="top">
								<label for="cgc_ub-name"><?php _e('Name', 'cgc_ub'); ?></label>
							</th>
							<td>
								<input name="name" id="cgc_ub_name" type="text" value="" style="width: 300px;"/>
								<p class="description"><?php _e('The name of this badge', 'cgc_ub'); ?></p>
							</td>
						</tr>
						<tr class="form-field" id="cgc_ub_image_row">
							<th scope="row" valign="top">
								<label for="cgc_ub_image"><?php _e('Image', 'cgc_ub'); ?></label>
							</th>
							<td>
								<input type="text" class="image_src" id="cgc_ub_image" name="image" value="" style="width: 300px;"/>
								<button class="cgc_ub_upload_image_button button-secondary"><?php _e('Choose Image', 'cgc_ub'); ?></button>
								<p class="description"><?php _e('Upload or choose an image for this badge', 'cgc_ub'); ?></p>
							</td>
						</tr>
						<tr class="form-field" id="cgc_ub_method_row">
							<th scope="row" valign="top">
								<label for="cgc_ub_type"><?php _e('Method', 'cgc_ub'); ?></label>
							</th>
							<td>
								<select name="method" id="cgc_ub_type">
									<option value="manual"><?php _e('Manual', 'cgc_ub'); ?></option>
									<option value="conditional"><?php _e('Conditional', 'cgc_ub'); ?></option>
								</select>
								<img src="<?php echo CGC_UB_PLUGIN_URL . 'images/loading.gif'; ?>" style="display: none;" id="cgc_ub_ajax"/>
								<p class="description"><?php _e('Choose the application method for this badge.', 'cgc_ub'); ?></p>
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<input type="hidden" name="cgc_ub_action" value="add_badge"/>
					<input type="hidden" name="cgc_ub_add_nonce" value="<?php echo wp_create_nonce('cgc_ub_add_nonce'); ?>"/>
					<input type="submit" value="<?php _e('Add Badge', 'cgc_ub'); ?>" class="button-primary"/>
				</p>
			</form>
		</div><!--end .wrap-->
		<?php
		echo ob_get_clean();
	}
}
