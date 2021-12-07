<?php
/*
Plugin Name: Fortify
Plugin URI: http://wordpress.org/plugins/fortify/
Description: No spam in comments. No captcha.
Version: 1.0
Author: webvitaly
Text Domain: fortify
Author URI: http://web-profile.net/wordpress/plugins/
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}

define('FORTIFY_PLUGIN_VERSION', '1.0');

include('fortify-functions.php');
include('fortify-settings.php');
include('fortify-info.php');


function fortify_form_part() {
	$rn = "\r\n"; // .chr(13).chr(10)

	if ( ! is_user_logged_in()) { // add fortify fields only for not logged in users
		echo $rn.'<!-- Fortify plugin v.'.esc_html(FORTIFY_PLUGIN_VERSION).' wordpress.org/plugins/fortify/ -->'.$rn;
		echo '		<p class="fortify-group fortify-group-q" style="display: none;">
			<label>Current year <span class="required">*</span></label>
			<input type="text" name="fortify-q" class="fortify-control fortify-control-q" value="" />
			<input type="text" name="fortify-a" class="fortify-control fortify-control-a" value="'.esc_html(date('Y')).'"/>
		</p>'.$rn; // question which is populated by javascript
		echo '		<p class="fortify-group fortify-group-e" style="display: none;">
			<label>Leave this field empty</label>
			<input type="text" name="fortify-e-email-url-website" class="fortify-control fortify-control-e" value="" />
		</p>'.$rn; // empty field; trap for spammers because many bots will try to put email or url here
		echo '		<script type="text/javascript">
			let fortify_a = document.getElementsByClassName("fortify-control-a")[0].value;
			document.querySelector(".fortify-control-q").value = fortify_a;
		</script>';
	}
}
add_action('comment_form', 'fortify_form_part'); // add fortify inputs to the comment form


function fortify_check_comment($commentdata) {
	$fortify_settings = fortify_get_settings();
	
	extract($commentdata);

	if ( ! is_user_logged_in() && $comment_type != 'pingback' && $comment_type != 'trackback') { // logged in user is not a spammer
		if( fortify_check_for_spam() ) {
			if( $fortify_settings['save_spam_comments'] ) {
				fortify_store_comment($commentdata);
			}
			fortify_counter_stats();
			wp_die('Comment is spam.'); // die - do not send comment and show error message
		}
	}
	
	if ($comment_type == 'trackback') {
		if( $fortify_settings['save_spam_comments'] ) {
			fortify_store_comment($commentdata);
		}
		fortify_counter_stats();
		wp_die('Trackbacks are disabled.'); // die - do not send trackback and show error message
	}

	return $commentdata; // if comment does not looks like spam
}

if ( ! is_admin()) { // without this check it is not possible to add comment in admin section
	add_filter('preprocess_comment', 'fortify_check_comment', 1);
}


function fortify_plugin_meta($links, $file) { // add some links to plugin meta row
	if ( $file == plugin_basename( __FILE__ ) ) {
		$row_meta = array(
			'support' => '<a href="http://web-profile.net/wordpress/plugins/fortify/" target="_blank">' . __( 'Fortify', 'fortify' ) . '</a>',
			'donate' => '<a href="http://web-profile.net/donate/" target="_blank">' . __( 'Donate', 'fortify' ) . '</a>'
		);
		$links = array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter('plugin_row_meta', 'fortify_plugin_meta', 10, 2);
