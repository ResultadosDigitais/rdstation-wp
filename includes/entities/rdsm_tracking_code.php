<?php
class RDSMTrackingCode {
  private $api;

  public function __construct($api_client = null) {
    $this->api = $api_client;
  }

  public function persist_tracking_code() {
    $response = $this->api->tracking_code();
    $body = wp_remote_retrieve_body($response);
    $parsed_body = json_decode($body);

    if ($parsed_body->path) {
      update_option('rdsm_tracking_code', $parsed_body->path);

      return true;
    }

    return false;
  }

  public function enable() {
    $options = get_option('rdsm_general_settings');
    $options['enable_tracking_code'] = "1";
    update_option('rdsm_general_settings', $options);
  }

  public function disable() {
    $options = get_option('rdsm_general_settings');
    $options['enable_tracking_code'] = "";
    update_option('rdsm_general_settings', $options);
  }
}
