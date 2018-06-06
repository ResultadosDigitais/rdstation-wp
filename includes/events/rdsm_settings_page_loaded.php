<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMSettingsPageLoaded implements RDSMEventsInterface {
  public function register_hooks() {
    add_action('wp_ajax_rdsm-authorization-check',  array($this, 'check_authorization'));
  }

  public function check_authorization() {
    $response = array('token' => get_option('rdsm_access_token'));
    wp_send_json($response);
  }
}
