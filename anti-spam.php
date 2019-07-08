<?php
/*
Plugin Name: Anti-spam
Plugin URI: http://wordpress.org/plugins/anti-spam/
Description: No spam in comments. No captcha.
Version: 5.5
Author: webvitaly
Text Domain: anti-spam
Author URI: http://web-profile.net/wordpress/plugins/
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) { // Avoid direct calls to this file and prevent full path disclosure
	exit;
}

define('ANTISPAM_PLUGIN_VERSION', '5.5');

include('anti-spam-functions.php');
include('anti-spam-settings.php');
include('anti-spam-info.php');


function antispam_enqueue_script() {
	global $withcomments; // WP flag to show comments on all pages
	if ((is_singular() || $withcomments) && comments_open()) { // load script only for pages with comments form
		wp_enqueue_script('anti-spam-script', plugins_url('/js/anti-spam-5.5.js', __FILE__), null, null, true);
	}
}
add_action('wp_enqueue_scripts', 'antispam_enqueue_script');


function antispam_form_part() {
	$rn = "\r\n"; // .chr(13).chr(10)

	if ( ! is_user_logged_in()) { // add anti-spam fields only for not logged in users
		echo $rn.'<!-- Anti-spam plugin v.'.ANTISPAM_PLUGIN_VERSION.' wordpress.org/plugins/anti-spam/ -->'.$rn;
		echo '		<p class="antispam-group antispam-group-q" style="clear: both;">
			<label>Current ye@r <span class="required">*</span></label>
			<input type="hidden" name="antspm-a" class="antispam-control antispam-control-a" value="'.date('Y').'" />
			<input type="text" name="antspm-q" class="antispam-control antispam-control-q" value="'.ANTISPAM_PLUGIN_VERSION.'" autocomplete="off" />
		</p>'.$rn; // question (hidden with js)
		echo '		<p class="antispam-group antispam-group-e" style="display: none;">
			<label>Leave this field empty</label>
			<input type="text" name="antspm-e-email-url-website" class="antispam-control antispam-control-e" value="" autocomplete="off" />
		</p>'.$rn; // empty field (hidden with css); trap for spammers because many bots will try to put email or url here
	}
}
add_action('comment_form', 'antispam_form_part'); // add anti-spam inputs to the comment form


function antispam_check_comment($commentdata) {
	$antispam_settings = antispam_get_settings();
	
	extract($commentdata);

	if ( ! is_user_logged_in() && $comment_type != 'pingback' && $comment_type != 'trackback') { // logged in user is not a spammer
		if( antispam_check_for_spam() ) {
			if( $antispam_settings['save_spam_comments'] ) {
				antispam_store_comment($commentdata);
			}
			antispam_counter_stats();
			wp_die('Comment is a spam.'); // die - do not send comment and show error message
		}
	}
	
	if ($comment_type == 'trackback') {
		if( $antispam_settings['save_spam_comments'] ) {
			antispam_store_comment($commentdata);
		}
		antispam_counter_stats();
		wp_die('Trackbacks are disabled.'); // die - do not send trackback and show error message
	}

	return $commentdata; // if comment does not looks like spam
}

if ( ! is_admin()) { // without this check it is not possible to add comment in admin section
	add_filter('preprocess_comment', 'antispam_check_comment', 1);
}


function antispam_plugin_meta($links, $file) { // add some links to plugin meta row
	if ( $file == plugin_basename( __FILE__ ) ) {
		$row_meta = array(
			'support' => '<a href="http://web-profile.net/wordpress/plugins/anti-spam/" target="_blank">' . __( 'Anti-spam', 'anti-spam' ) . '</a>',
			'donate' => '<a href="http://web-profile.net/donate/" target="_blank">' . __( 'Donate', 'anti-spam' ) . '</a>',
			'upgrage' => '<a href="http://codecanyon.net/item/antispam-pro/6491169?ref=webvitalii" target="_blank">' . __( 'Anti-spam Pro', 'anti-spam' ) . '</a>'
		);
		$links = array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter('plugin_row_meta', 'antispam_plugin_meta', 10, 2);
