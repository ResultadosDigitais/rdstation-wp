<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');
require_once(RDSM_SRC_DIR . '/helpers/rdsm_log_file_helper.php');

class RDSMLogFile implements RDSMEventsInterface {

  public function register_hooks() {
    add_action('wp_ajax_rdsm-log-file', array($this, 'load_log_file'));
    add_action('wp_ajax_rdsm-clear-log-file', array($this, 'clear_log_file'));
  }

  public function load_log_file() {
    wp_send_json(RDSMLogFileHelper::get_log_file());
  }

  public function clear_log_file() {
    wp_send_json(RDSMLogFileHelper::clear_log_file());
  }
}

?>
