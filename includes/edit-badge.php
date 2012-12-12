<?php
if(!isset($_GET['badge_id']) || !is_numeric($_GET['badge_id'])) {
	wp_die(__('Something went wrong.', 'cgc_ub'), __('Error', 'cgc_ub'));
}
$badge = cgc_ub_get_badge($_GET['badge_id']);
?>
<h2><?php _e('Edit Badge', 'cgc_ub'); ?> - <a href="<?php echo admin_url('users.php?page=cgc-badges'); ?>" class="button-secondary"><?php _e('Go Back', 'cgc_ub'); ?></a></h2>
<form id="cgc_ub-edit-discount" action="" method="POST">
	<table class="form-table">
		<tbody>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="cgc_ub-name"><?php _e('Name', 'cgc_ub'); ?></label>
				</th>
				<td>
					<input name="name" id="cgc_ub_name" type="text" value="<?php echo esc_attr($badge['name']); ?>" style="width: 300px;"/>
					<p class="description"><?php _e('The name of this badge', 'cgc_ub'); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top">
					<label for="cgc_ub_image"><?php _e('Image', 'cgc_ub'); ?></label>
				</th>
				<td>
					<input type="text" class="image_src" id="cgc_ub_image" name="image" value="<?php echo esc_attr($badge['image']); ?>" style="width: 300px;"/>
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
						<option value="manual" <?php if(isset($badge['method'])) { selected('manual', $badge['method']); } ?>><?php _e('Manual', 'cgc_ub'); ?></option>
						<option value="conditional" <?php if(isset($badge['method'])) { selected('conditional', $badge['method']); } ?>><?php _e('Conditional', 'cgc_ub'); ?></option>
					</select>
					<img src="<?php echo CGC_UB_PLUGIN_URL . 'images/loading.gif'; ?>" style="display: none;" id="cgc_ub_ajax"/>
					<p class="description"><?php _e('Choose the application method for this badge.', 'cgc_ub'); ?></p>
				</td>
			</tr>
			<?php
			if(isset($badge['method']) && $badge['method'] == 'conditional') {
				echo cgc_ub_get_conditionals_select($badge['condition']);
			}
			?>
		</tbody>
	</table>
	<p class="submit">
		<input type="hidden" name="cgc_ub_action" value="edit_badge"/>
		<input type="hidden" name="badge_id" value="<?php echo $_GET['badge_id']; ?>"/>
		<input type="hidden" name="cgc_ub_redirect" value="<?php echo admin_url('users.php?page=cgc-badges'); ?>"/>
		<input type="hidden" name="cgc_ub_edit_nonce" value="<?php echo wp_create_nonce('cgc_ub_edit_nonce'); ?>"/>
		<input type="submit" value="<?php _e('Update Badge', 'cgc_ub'); ?>" class="button-primary"/>
	</p>
</form>