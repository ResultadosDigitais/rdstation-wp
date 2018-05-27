<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_settings_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_admin_tracking_code_hooks.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMTrackingStatusUpdated implements RDSMEventsInterface {
  public function register_hooks() {
    add_action('wp_ajax_rdsm-update-tracking-code-status', array($this, 'trigger'), 2);
  }

  public function trigger() {
    $enabled = $_POST['checked'];
    $access_token = get_option('rdsm_access_token');
    $refresh_token = get_option('rdsm_refresh_token');

    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMSettingsAPI($user_credentials);

    $rdsm_admin_tracking_code_hook = new RDSMAdminTrackingCodeHooks($api_instance);

    if ($enabled == 'true') {
      $rdsm_admin_tracking_code_hook->enable();
      $rdsm_admin_tracking_code_hook->persist_tracking_code();
    } else {
      $rdsm_admin_tracking_code_hook->disable();
    }

    die();
  }
}
