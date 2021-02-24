<?php

class RDSMLogFileHelper {
  	
  	public static function write_to_log_file($value) {
	  	$file_path = RDSM_LOG_FILE_PATH;
	    $time = date( "F jS Y, H:i P", time() );
	    $log = "#$time\r\n$value\r\n";
	    $open = fopen( $file_path, "a" );
	    fputs( $open, $log );
	    RDSMLogFileHelper::clear_log_file( $file_path );
	    fclose( $open );
  	}

  	private static function clear_log_file($file_path) {  	
		$file = file($file_path);
		for ($i = 0;count($file) > RDSM_LOG_FILE_LIMIT;$i++) {
		  	unset($file[$i]);
		  	file_put_contents($file_path, $file);
		}
  	}
}
