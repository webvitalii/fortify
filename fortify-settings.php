<?php
/*
Fortify settings code
used WordPress Settings API - http://codex.wordpress.org/Settings_API
*/

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}


function fortify_menu() { // add menu item
	add_options_page('Fortify', 'Fortify', 'manage_options', 'fortify', 'fortify_settings');
}
add_action('admin_menu', 'fortify_menu');


function fortify_admin_init() {
	register_setting('fortify_settings_group', 'fortify_settings', 'fortify_settings_validate');

	add_settings_section('fortify_settings_automatic_section', '', 'fortify_section_callback', 'fortify_automatic_page');

	add_settings_field('save_spam_comments', 'Save spam comments', 'fortify_field_save_spam_comments_callback', 'fortify_automatic_page', 'fortify_settings_automatic_section');

}
add_action('admin_init', 'fortify_admin_init');


function fortify_settings_init() { // set default settings
	global $fortify_settings;
	$fortify_settings = fortify_get_settings();
	update_option('fortify_settings', $fortify_settings);
}
add_action('admin_init', 'fortify_settings_init');


function fortify_settings_validate($input) {
	$default_settings = fortify_get_settings();
	
	// checkbox
	$output['save_spam_comments'] = $input['save_spam_comments'];

	return $output;
}


function fortify_section_callback() { // Fortify settings description
	echo '';
}


function fortify_field_save_spam_comments_callback() {
	$settings = fortify_get_settings();
	echo '<label><input type="checkbox" name="fortify_settings[save_spam_comments]" '.checked(1, $settings['save_spam_comments'], false).' value="1" />';
	echo ' Save spam comments into spam section</label>';
	echo '<p class="description">Useful for testing how the plugin works. <a href="'. admin_url( 'edit-comments.php?comment_status=spam' ) . '">View spam section</a>.</p>';
}


function fortify_settings() {
	$fortify_stats = get_option('fortify_stats', array());

	if (array_key_exists('blocked_total', $fortify_stats)) {
	    $blocked_total = $fortify_stats['blocked_total'];
    } else {
        $blocked_total = 0;
    }

	?>
	<div class="wrap">
		
		<h2><span class="dashicons dashicons-admin-generic"></span> Fortify</h2>

		<div class="fortify-panel-info">
			<p style="margin: 0;">
				<span class="dashicons dashicons-chart-bar"></span>
				<strong><?php echo esc_html($blocked_total); ?></strong> spam comments were blocked
				by <a href="https://wordpress.org/plugins/fortify/" target="_blank">Fortify</a> plugin so far.
			</p>
		</div>

		<form method="post" action="options.php">
			<?php settings_fields('fortify_settings_group'); ?>
			<div class="fortify-group-automatic">
				<?php do_settings_sections('fortify_automatic_page'); ?>
			</div>
			<?php submit_button(); ?>
		</form>

	</div>
	<?php
}
