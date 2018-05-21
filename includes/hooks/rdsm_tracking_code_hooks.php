<?php
require_once(__DIR__ . '/../client/rdsm_settings_api.php');

class RDSMTrackingCodeHooks {
  private $api;

  public function __construct($api_client = null) {
    if (!isset($api_client)) {
      $api_client = new RDSMSettingsAPI;
    }

    $this->api = $api_client;
  }

  public function handle() {
    $options = get_option('rdsm_general_settings');
    
    if (is_array($options) && !!$options['enable_tracking_code']) {
      add_action('update_option_rdsm_enable_tracking_code_html', array($this, 'persist_tracking_code'));

      $this->enable();
    }
  }

  public function tracking_code_hook() {
    $tracking_code = get_option('rdsm_tracking_code');

    if (!empty($tracking_code)) {
      if (is_home() || is_single() || is_page()) {
        echo html_entity_decode($this->tracking_code_script_tag($tracking_code));
        
        return true;
      }

      return false;
    }
  
    return false; 
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

  private function enable() {
    add_action('wp_footer', array($this, 'tracking_code_hook'));
  }

  private function disable() {
    remove_action('wp_fotter', array($this, 'tracking_code_hook'));
  }

  private function tracking_code_script_tag($path) {
    return "<script type='text/javascript' async src='". $path ."'></script>";
  }
}
