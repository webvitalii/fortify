<?php

defined('ABSPATH') OR exit; // prevent full path disclosure

function antispam_admin_notice() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php'):
		$user_id = get_current_user_id();
		$antispam_info_visibility = get_user_meta($user_id, 'antispam_info_visibility', true);
		if ($antispam_info_visibility == 1 OR $antispam_info_visibility == ''):
			$blocked_total = 0; // show 0 by default
			$antispam_stats = get_option('antispam_stats', array());
			if (isset($antispam_stats['blocked_total'])) {
				$blocked_total = $antispam_stats['blocked_total'];
			}
			?>
			<div class="update-nag antispam-panel-info">
				<p style="margin: 0;">
					<?php echo $blocked_total; ?> spam comments were blocked by <a href="http://wordpress.org/plugins/anti-spam/">Anti-spam</a> plugin so far.
					<a href="http://codecanyon.net/item/antispam-pro/6491169?ref=webvitalii" title="Anti-spam Pro">Upgrade to Pro</a> for more advanced protection.
				</p>
			</div>
			<?php
		endif; // end of if($antispam_info_visibility)
	endif; // end of if($pagenow == 'edit-comments.php')
}
add_action('admin_notices', 'antispam_admin_notice');


function antispam_display_screen_option() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php'):
		$user_id = get_current_user_id();
		$antispam_info_visibility = get_user_meta($user_id, 'antispam_info_visibility', true);

		if ($antispam_info_visibility == 1 OR $antispam_info_visibility == '') {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}

		?>
		<script>
			jQuery(function($){
				$('.antispam_screen_options_group').insertAfter('#screen-options-wrap #adv-settings');
			});
		</script>
		<form method="post" class="antispam_screen_options_group" style="padding: 20px 0 5px 0;">
			<input type="hidden" name="antispam_option_submit" value="1" />
			<label>
				<input name="antispam_info_visibility" type="checkbox" value="1" <?php echo $checked; ?> />
				Anti-spam info
			</label>
			<input type="submit" class="button" value="<?php _e('Apply'); ?>" />
		</form>
		<?php
	endif; // end of if($pagenow == 'edit-comments.php')
}


function antispam_register_screen_option() {
	add_filter('screen_layout_columns', 'antispam_display_screen_option');
}
add_action('admin_head', 'antispam_register_screen_option');


function antispam_update_screen_option() {
	if (isset($_POST['antispam_option_submit']) AND $_POST['antispam_option_submit'] == 1) {
		$user_id = get_current_user_id();
		if (isset($_POST['antispam_info_visibility']) AND $_POST['antispam_info_visibility'] == 1) {
			update_user_meta($user_id, 'antispam_info_visibility', 1);
		} else {
			update_user_meta($user_id, 'antispam_info_visibility', 0);
		}
	}
}
add_action('admin_init', 'antispam_update_screen_option');
