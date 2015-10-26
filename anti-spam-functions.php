<?php

defined('ABSPATH') OR exit; // prevent full path disclosure

function antispam_counter_stats() {
	$antispam_stats = get_option('antispam_stats', array());
	if (array_key_exists('blocked_total', $antispam_stats)){
		$antispam_stats['blocked_total']++;
	} else {
		$antispam_stats['blocked_total'] = 1;
	}
	update_option('antispam_stats', $antispam_stats);
}


function antispam_log( $spam_comment = '' ) {

	$log_file_name = plugin_dir_path( __FILE__ ).'log/anti-spam-'.date('Y-m').'.log';
	$log_file = fopen( $log_file_name, 'a' );
	if ($log_file) {
		fwrite( $log_file, $spam_comment );
		fclose( $log_file );
	}

	// delete old files
	$time_past = strtotime( '-1 year', time() );
	$date_past = date( 'Y-m', $time_past );
	$log_file_name_to_delete = plugin_dir_path( __FILE__ ).'log/anti-spam-'.$date_past.'.log';
	if (file_exists( $log_file_name_to_delete )) {
		unlink( $log_file_name_to_delete );
	}

}