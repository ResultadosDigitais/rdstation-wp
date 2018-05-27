<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_settings_api.php');
require_once(RDSM_SRC_DIR . '/entities/rdsm_tracking_code.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMTrackingStatusUpdated implements RDSMEventsInterface {
  public function register_hooks() {
    add_action('wp_ajax_rdsm-update-tracking-code-status', array($this, 'update_tracking_code'), 2);
  }

  public function update_tracking_code() {
    $enabled = $_POST['checked'];
    $access_token = get_option('rdsm_access_token');
    $refresh_token = get_option('rdsm_refresh_token');

    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMSettingsAPI($user_credentials);

    $tracking_code_admin = new RDSMTrackingCode($api_instance);

    if ($enabled == 'true') {
      $tracking_code_admin->persist_tracking_code();
      $tracking_code_admin->enable();
    } else {
      $tracking_code_admin->disable();
    }

    die();
  }
}
