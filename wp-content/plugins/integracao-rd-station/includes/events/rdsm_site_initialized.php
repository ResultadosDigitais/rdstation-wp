<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMSiteInitialized implements RDSMEventsInterface {
  function __construct() {
    $this->general_settings = get_option('rdsm_general_settings');
  }

  public function register_hooks() {
    if (!empty($this->general_settings['enable_tracking_code'])) {
      add_action('wp_footer', array($this, 'add_tracking_code_to_site'), 1);
    }
  }

  public function add_tracking_code_to_site() {
    $tracking_code = get_option('rdsm_tracking_code');

    if (!empty($tracking_code)) {
      if (!is_admin()) {
        echo html_entity_decode($this->tracking_code_script_tag($tracking_code));

        return true;
      }
      return false;
    }

    return false;
  }

  private function tracking_code_script_tag($path) {
    return "<script type='text/javascript' async src='". $path ."'></script>";
  }
}
