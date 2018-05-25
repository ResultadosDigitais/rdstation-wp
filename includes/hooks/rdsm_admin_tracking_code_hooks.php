<?php
class RDSMAdminTrackingCodeHooks {
  private $api;

  public function __construct($api_client = null) {
    $this->api = $api_client;
  }

  public function active_hooks() {
    $this->enable_tracking_code_opt_in();
  }

  public function rdsm_handle_tracking_code_option() {
    if ($this->can_enable_tracking_code()) {
      $this->persist_tracking_code();
    }
  }

  private function can_enable_tracking_code() {
    $options = get_option('rdsm_general_settings');

    if (is_array($options) && !!$options['enable_tracking_code']) {
      return true;
    }

    return false;
  }

  public function persist_tracking_code() {
    $response = $this->api->tracking_code();

    if (wp_remote_retrieve_response_code($response) == 401) {
      $this->delete_access_token();

      return false;
    }

    $body = wp_remote_retrieve_body($response);
    $parsed_body = json_decode($body);

    if ($parsed_body->path) {
      update_option('rdsm_tracking_code', $parsed_body->path);

      return true;
    }

    return false;
  }

  private function delete_access_token() {
    delete_option('rdsm_access_token');
  }

  private function enable_tracking_code_opt_in() {
    add_action('update_option_rdsm_general_settings', array($this, 'rdsm_handle_tracking_code_option'), 1);
  }
}
