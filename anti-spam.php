<?php
/*
Plugin Name: Anti-spam
Plugin URI: http://wordpress.org/plugins/anti-spam/
Description: No spam in comments. No captcha.
Version: 4.2
Author: webvitaly
Text Domain: anti-spam
Author URI: http://web-profile.com.ua/wordpress/plugins/
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}

$antispam_send_spam_comment_to_admin = false; // if true, than rejected spam comments will be sent to admin email
$antispam_log_spam_comment = false; // if true, than rejected spam comments will be logged to wp-content/plugins/anti-spam/log/anti-spam-2015-11.log
$antispam_allow_trackbacks = false; // if true, than trackbacks will be allowed
// trackbacks almost not used by users, but mostly used by spammers; pingbacks are always enabled
// more about the difference between trackback and pingback - http://web-profile.com.ua/web/trackback-vs-pingback/

define('ANTISPAM_PLUGIN_VERSION', '4.2');

$antispam_settings = array(
	'send_spam_comment_to_admin' => $antispam_send_spam_comment_to_admin,
	'allow_trackbacks' => $antispam_allow_trackbacks,
	'admin_email' => get_option('admin_email'),
	'log_spam_comment' => $antispam_log_spam_comment
);

include('anti-spam-functions.php');
include('anti-spam-info.php');


function antispam_enqueue_script() {
	if (is_singular() && comments_open()) { // load script only for pages with comments form
		wp_enqueue_script('anti-spam-script', plugins_url('/js/anti-spam-4.2.js', __FILE__), null, null, true);
	}
}
add_action('wp_enqueue_scripts', 'antispam_enqueue_script');


function antispam_form_part() {
	global $antispam_settings;
	$rn = "\r\n"; // .chr(13).chr(10)

	if ( ! is_user_logged_in()) { // add anti-spam fields only for not logged in users
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
	global $antispam_settings;
	$rn = "\r\n"; // .chr(13).chr(10)

	extract($commentdata);

	$antispam_pre_error_message = '<p><strong><a href="javascript:window.history.back()">Go back</a></strong> and try again.</p>';
	$antispam_error_message = '';

	if (($antispam_settings['send_spam_comment_to_admin']) || ($antispam_settings['log_spam_comment'])) { // if sending email to admin is enabled or loging
		$post = get_post($comment->comment_post_ID);
		$antispam_message_spam_info  = 'Spam for post: "'.$post->post_title.'"' . $rn;
		$antispam_message_spam_info .= get_permalink($comment->comment_post_ID) . $rn.$rn;

		$antispam_message_spam_info .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . $rn;
		$antispam_message_spam_info .= 'User agent: ' . $_SERVER['HTTP_USER_AGENT'] . $rn;
		$antispam_message_spam_info .= 'Referer: ' . $_SERVER['HTTP_REFERER'] . $rn.$rn;

		$antispam_message_spam_info .= 'Comment data:'.$rn; // lets see what comment data spammers try to submit
		foreach ($commentdata as $key => $value) {
			$antispam_message_spam_info .= '$commentdata['.$key. '] = '.$value.$rn;
		}
		$antispam_message_spam_info .= $rn.$rn;

		$antispam_message_spam_info .= 'Post vars:'.$rn; // lets see what post vars spammers try to submit
		foreach ($_POST as $key => $value) {
			$antispam_message_spam_info .= '$_POST['.$key. '] = '.$value.$rn;
		}
		$antispam_message_spam_info .= $rn.$rn;

		$antispam_message_spam_info .= 'Cookie vars:'.$rn; // lets see what cookie vars spammers try to submit
		foreach ($_COOKIE as $key => $value) {
			$antispam_message_spam_info .= '$_COOKIE['.$key. '] = '.$value.$rn;
		}
		$antispam_message_spam_info .= $rn.$rn;

		$antispam_message_append = '-----------------------------'.$rn;
		$antispam_message_append .= 'This is spam comment rejected by Anti-spam plugin - wordpress.org/plugins/anti-spam/' . $rn;
		$antispam_message_append .= 'You may edit "anti-spam.php" file and disable this notification.' . $rn;
		$antispam_message_append .= 'You should find "$antispam_send_spam_comment_to_admin" and make it equal to "false".' . $rn;
	}

	if ( ! is_user_logged_in() && $comment_type != 'pingback' && $comment_type != 'trackback') { // logged in user is not a spammer
		$spam_flag = false;

		if ( trim($_POST['antspm-q']) != date('Y') ) { // year-answer is wrong - it is spam
			if ( trim($_POST['antspm-d']) != date('Y') ) { // extra js-only check: there is no js added input - it is spam
				$spam_flag = true;
				if (empty($_POST['antspm-q'])) { // empty answer - it is spam
					$antispam_error_message .= 'Error: empty answer. ['.esc_attr( $_POST['antspm-q'] ).']<br> '.$rn;
				} else {
					$antispam_error_message .= 'Error: answer is wrong. ['.esc_attr( $_POST['antspm-q'] ).']<br> '.$rn;
				}
			}
		}

		if ( ! empty($_POST['antspm-e-email-url-website'])) { // trap field is not empty - it is spam
			$spam_flag = true;
			$antispam_error_message .= 'Error: field should be empty. ['.esc_attr( $_POST['antspm-e-email-url-website'] ).']<br> '.$rn;
		}

		if ($spam_flag) { // it is spam
			$antispam_error_message .= '<strong>Comment was blocked because it is spam.</strong><br> ';
			if ($antispam_settings['send_spam_comment_to_admin']) {
				$antispam_subject = 'Spam comment on site ['.get_bloginfo('name').']'; // email subject
				$antispam_message = '';
				$antispam_message .= $antispam_error_message . $rn.$rn;
				$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
				$antispam_message .= $antispam_message_append;
				@wp_mail($antispam_settings['admin_email'], $antispam_subject, $antispam_message); // send spam comment to admin email
			}
			if ($antispam_settings['log_spam_comment']) {
				$antispam_message = $rn.$rn.'========== ========== =========='.$rn.$rn;
				$antispam_message .= $antispam_error_message . $rn.$rn;
				$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
				antispam_log( $antispam_message );
			}
			antispam_counter_stats();
			wp_die( $antispam_pre_error_message . $antispam_error_message ); // die - do not send comment and show errors
		}
	}

	if ( ! $antispam_settings['allow_trackbacks']) { // if trackbacks are blocked (pingbacks are alowed)
		if ($comment_type == 'trackback') { // if trackbacks ( || $comment_type == 'pingback')
			$antispam_error_message .= 'Error: trackbacks are disabled.<br> ';
			if ($antispam_settings['send_spam_comment_to_admin']) { // if sending email to admin is enabled
				$antispam_subject = 'Spam trackback on site ['.get_bloginfo('name').']'; // email subject
				$antispam_message = '';
				$antispam_message .= $antispam_error_message . $rn.$rn;
				$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
				$antispam_message .= $antispam_message_append;
				@wp_mail($antispam_settings['admin_email'], $antispam_subject, $antispam_message); // send trackback comment to admin email
			}
			antispam_counter_stats();
			wp_die($antispam_pre_error_message . $antispam_error_message); // die - do not send trackback
		}
	}

	return $commentdata; // if comment does not looks like spam
}


if ( ! is_admin()) {
	add_filter('preprocess_comment', 'antispam_check_comment', 1);
}


function antispam_plugin_meta($links, $file) { // add some links to plugin meta row
	if ( $file == plugin_basename( __FILE__ ) ) {
		$row_meta = array(
			'support' => '<a href="http://web-profile.com.ua/wordpress/plugins/anti-spam/" target="_blank"><span class="dashicons dashicons-editor-help"></span> ' . __( 'Anti-spam', 'anti-spam' ) . '</a>',
			'donate' => '<a href="http://web-profile.com.ua/donate/" target="_blank"><span class="dashicons dashicons-heart"></span> ' . __( 'Donate', 'anti-spam' ) . '</a>',
			'upgrage' => '<a href="http://codecanyon.net/item/antispam-pro/6491169?ref=webvitaly" target="_blank"><span class="dashicons dashicons-star-filled"></span> ' . __( 'Anti-spam Pro', 'anti-spam' ) . '</a>'
		);
		$links = array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter('plugin_row_meta', 'antispam_plugin_meta', 10, 2);