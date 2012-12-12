<?php
if(!isset($_GET['user']) || !is_numeric($_GET['user'])) {
	wp_die(__('Something went wrong.', 'cgc_ub'), __('Error', 'cgc_ub'));
}
?>
<h2><?php _e('Edit Badges', 'cgc_ub'); ?> - <a href="<?php echo admin_url('users.php?page=cgc-badges'); ?>" class="button-secondary"><?php _e('Go Back', 'cgc_ub'); ?></a></h2>
<form id="cgc_ub-edit-discount" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row" valign="top"><?php _e('Badges', 'cgc_ub'); ?></th>
				<td>
					<?php
					$badges = cgc_ub_get_badges();
					$users_badges = cgc_ub_get_users_manual_badges($_GET['user']);
					//print_r($users_badges); exit;
					if($badges) {
						foreach($badges as $id => $badge) {
							echo '<p>';
								echo '<img src="' . $badge['image'] . '"/>&nbsp;';
								echo '<input type="checkbox" name="cgc_ub_user_badges[' . $id . ']" ' . checked(true, in_array($id, $users_badges), false) . ' value="' . $id . '"/><br/>';
							echo '</p>';
						}
					} else {
						echo '<p>' . __('No badges have been created yet', 'cgc_ub') . '</p>';
					}
					?>
					<p class="description"><?php _e('Select the badges for this user.', 'cgc_ub'); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="cgc_ub_action" value="edit_user_badges"/>
		<input type="hidden" name="user_id" value="<?php echo $_GET['user']; ?>"/>
		<input type="hidden" name="cgc_ub_edit_nonce" value="<?php echo wp_create_nonce('cgc_ub_edit_nonce'); ?>"/>
		<input type="submit" value="<?php _e('Update User\'s Badge', 'cgc_ub'); ?>" class="button-primary"/>
	</p>
</form>