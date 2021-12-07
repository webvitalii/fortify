<?php

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}

function fortify_admin_notice() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php'):
		$user_id = get_current_user_id();
		$fortify_info_visibility = get_user_meta($user_id, 'fortify_info_visibility', true);
		if ($fortify_info_visibility == 1 OR $fortify_info_visibility == ''):
			$blocked_total = 0; // show 0 by default
			$fortify_stats = get_option('fortify_stats', array());
			if (isset($fortify_stats['blocked_total'])) {
				$blocked_total = $fortify_stats['blocked_total'];
			}
			?>
			<div class="update-nag fortify-panel-info">
				<p style="margin: 0;">
					<?php echo $blocked_total; ?> spam comments have been blocked by <a href="http://wordpress.org/plugins/fortify/">Fortify</a> plugin so far.
				</p>
			</div>
			<?php
		endif; // end of if($fortify_info_visibility)
	endif; // end of if($pagenow == 'edit-comments.php')
}
add_action('admin_notices', 'fortify_admin_notice');


function fortify_display_screen_option() {
	global $pagenow;
	if ($pagenow == 'edit-comments.php'):
		$user_id = get_current_user_id();
		$fortify_info_visibility = get_user_meta($user_id, 'fortify_info_visibility', true);

		if ($fortify_info_visibility == 1 OR $fortify_info_visibility == '') {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}

		?>
		<script>
			jQuery(function($){
				$('.fortify_screen_options_group').insertAfter('#screen-options-wrap #adv-settings');
			});
		</script>
		<form method="post" class="fortify_screen_options_group" style="padding: 20px 0 5px 0;">
			<input type="hidden" name="fortify_option_submit" value="1" />
			<label>
				<input name="fortify_info_visibility" type="checkbox"
                    value="1" <?php echo esc_html($checked); ?> />
				Fortify info
			</label>
			<input type="submit" class="button" value="<?php _e('Apply'); ?>" />
		</form>
		<?php
	endif; // end of if($pagenow == 'edit-comments.php')
}


function fortify_register_screen_option() {
	add_filter('screen_layout_columns', 'fortify_display_screen_option');
}
add_action('admin_head', 'fortify_register_screen_option');


function fortify_update_screen_option() {
	if (isset($_POST['fortify_option_submit']) AND $_POST['fortify_option_submit'] == 1) {
		$user_id = get_current_user_id();
		if (isset($_POST['fortify_info_visibility']) AND $_POST['fortify_info_visibility'] == 1) {
			update_user_meta($user_id, 'fortify_info_visibility', 1);
		} else {
			update_user_meta($user_id, 'fortify_info_visibility', 0);
		}
	}
}
add_action('admin_init', 'fortify_update_screen_option');
