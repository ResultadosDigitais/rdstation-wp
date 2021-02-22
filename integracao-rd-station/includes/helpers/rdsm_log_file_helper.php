<?php

class RDSMLogFileHelper {

  public static function write_to_log_file($value) {
    $time = date( "F jS Y, H:i", time()-10800 );
    $log = "#$time\r\n$value\r\n";
    $file = RDSM_LOG_FILE_PATH;
    $open = fopen( $file, "a" );
    $write = fputs( $open, $log );
    fclose( $open );
  }
}
