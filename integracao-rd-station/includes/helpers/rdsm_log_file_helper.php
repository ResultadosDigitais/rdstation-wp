<?php

class RDSMLogFileHelper {
  	
  	public static function write_to_log_file($value) {
	  	$file_path = RDSM_LOG_FILE_PATH . get_option('rdsm_refresh_token');
	    $time = date( "F jS Y, H:i P", time() );
	    $log = "#$time\r\n$value\r\n";
	    $open = fopen( $file_path, "a" );
	    if (file_exists($file_path)) {
		    fputs( $open, $log );
		    RDSMLogFileHelper::limit_log_file( $file_path );
		    fclose( $open );
		}
  	}

  	public static function get_log_file() {
		return file(RDSM_LOG_FILE_PATH . get_option('rdsm_refresh_token'));
  	}

  	public static function has_error() {
  		return (strpos(file_get_contents(RDSM_LOG_FILE_PATH . get_option('rdsm_refresh_token')), "errors") !== false);
  	}

  	private static function limit_log_file($file_path) {
		$file = file($file_path);
		for ($i = 0;count($file) > RDSM_LOG_FILE_LIMIT;$i++) {
		  	unset($file[$i]);
		}
		file_put_contents($file_path, $file);
  	}

  	public static function clear_log_file() {
  		return file_put_contents(RDSM_LOG_FILE_PATH . get_option('rdsm_refresh_token'), "");
  	}
}
