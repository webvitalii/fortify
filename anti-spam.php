<?php
/*
Plugin Name: Anti-spam
Plugin URI: http://wordpress.org/plugins/anti-spam/
Description: No spam in comments. No captcha.
Version: 5.0
Author: webvitaly
Text Domain: anti-spam
Author URI: http://web-profile.net/wordpress/plugins/
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) { // prevent full path disclosure
	exit;
}

define('ANTISPAM_PLUGIN_VERSION', '5.0');

$antispam_settings = array(
	'send_spam_comment_to_admin' => false,
	'save_spam_comments' => true
);

include('anti-spam-functions.php');
include('anti-spam-info.php');


function antispam_enqueue_script() {
	global $withcomments; // WP flag to show comments on all pages
	if ((is_singular() || $withcomments) && comments_open()) { // load script only for pages with comments form
		wp_enqueue_script('anti-spam-script', plugins_url('/js/anti-spam-5.0.js', __FILE__), null, null, true);
	}
}
add_action('wp_enqueue_scripts', 'antispam_enqueue_script');


function antispam_process_comments( $comment_ID, $comment_approved ) {
	global $antispam_settings;
	if ( $antispam_settings['save_spam_comments'] ) {
		if( antispam_check_for_spam() ) {
			wp_set_comment_status( $comment_ID, 'spam' );
			antispam_counter_stats();
		}
	}
}
add_action( 'comment_post', 'antispam_process_comments', 10, 2 );


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

	//$antispam_pre_error_message = '<p><strong><a href="javascript:window.history.back()">Go back</a></strong> and try again.</p>';
	$antispam_error_message = '';

	if ( $antispam_settings['send_spam_comment_to_admin'] ) { // if sending email to admin is enabled
		$post = get_post($comment->comment_post_ID);
		$antispam_message_spam_info  = 'Spam for post: "'.$post->post_title.'"' . $rn;
		$antispam_message_spam_info .= get_permalink($comment->comment_post_ID) . $rn.$rn;
	}

	if ( ! is_user_logged_in() && $comment_type != 'pingback' && $comment_type != 'trackback') { // logged in user is not a spammer
		$spam_flag = false;
		
		$antspm_q = '';
		if (isset($_POST['antspm-q'])) {
			$antspm_q = trim($_POST['antspm-q']);
		}
		$antspm_d = '';
		if (isset($_POST['antspm-d'])) {
			$antspm_d = trim($_POST['antspm-d']);
		}
		$antspm_e = '';
		if (isset($_POST['antspm-e-email-url-website'])) {
			$antspm_e = trim($_POST['antspm-e-email-url-website']);
		}
		
		if ( $antspm_q != date('Y') ) { // year-answer is wrong - it is spam
			if ( $antspm_d != date('Y') ) { // extra js-only check: there is no js added input - it is spam
				$spam_flag = true;
				if (empty($antspm_q)) { // empty answer - it is spam
					$antispam_error_message .= 'Error: empty answer. ['.esc_attr( $antspm_q ).']<br> '.$rn;
				} else {
					$antispam_error_message .= 'Error: answer is wrong. ['.esc_attr( $antspm_q ).']<br> '.$rn;
				}
			}
		}

		if ( ! empty($antspm_e)) { // trap field is not empty - it is spam
			$spam_flag = true;
			$antispam_error_message .= 'Error: field should be empty. ['.esc_attr( $antspm_e ).']<br> '.$rn;
		}

		if ($spam_flag) { // it is spam
			$antispam_error_message .= '<strong>Comment was blocked because it is spam.</strong><br> ';
			if ($antispam_settings['send_spam_comment_to_admin']) {
				$antispam_subject = 'Spam comment on site ['.get_bloginfo('name').']'; // email subject
				$antispam_message = '';
				$antispam_message .= $antispam_error_message . $rn.$rn;
				$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
				@wp_mail(get_option('admin_email'), $antispam_subject, $antispam_message); // send spam comment to admin email
			}
			antispam_counter_stats();
			//wp_die( $antispam_pre_error_message . $antispam_error_message ); // die - do not send comment and show errors
		}
	}
	
	/*echo '<pre>';
	var_dump($commentdata);
	echo '</pre>';
	wp_die( '<pre>'.$commentdata.'</pre>' );*/

	return $commentdata; // if comment does not looks like spam
}
add_filter('preprocess_comment', 'antispam_check_comment', 1);


function antispam_check_for_spam() {
	$spam_flag = false;
		
	$antspm_q = '';
	if (isset($_POST['antspm-q'])) {
		$antspm_q = trim($_POST['antspm-q']);
	}
	
	$antspm_d = '';
	if (isset($_POST['antspm-d'])) {
		$antspm_d = trim($_POST['antspm-d']);
	}
	
	$antspm_e = '';
	if (isset($_POST['antspm-e-email-url-website'])) {
		$antspm_e = trim($_POST['antspm-e-email-url-website']);
	}
	
	if ( $antspm_q != date('Y') ) { // year-answer is wrong - it is spam
		if ( $antspm_d != date('Y') ) { // extra js-only check: there is no js added input - it is spam
			$spam_flag = true;
			if (empty($antspm_q)) { // empty answer - it is spam
				//$antispam_error_message .= 'Error: empty answer. ['.esc_attr( $antspm_q ).']<br> '.$rn;
			} else {
				//$antispam_error_message .= 'Error: answer is wrong. ['.esc_attr( $antspm_q ).']<br> '.$rn;
			}
		}
	}

	if ( ! empty($antspm_e)) { // trap field is not empty - it is spam
		$spam_flag = true;
		//$antispam_error_message .= 'Error: field should be empty. ['.esc_attr( $antspm_e ).']<br> '.$rn;
	}
	
	return $spam_flag;
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