<?php

class RDSMTrackingCodeHooks {
  private $api;

  public function __construct($api_client) {
    $this->api = $api_client;
  }

  public function enable() {
    add_action('wp_footer', array($this, 'tracking_code_hook'));
  }

  public function disable() {
    remove_action('wp_fotter', array($this, 'tracking_code_hook'));
  }

  public function tracking_code_hook() {
    $options = get_option( 'rdsm_tag_manager_settings' );

    if (is_home() || is_single() || is_page()) {
        echo html_entity_decode($this->tracking_code_script_tag($options[ 'tracking_code' ]));
    }

    return;
  }

  public function persist_tracking_code() {
    $response = $this->api->tracking_code();
  }

  private function tracking_code_script_tag($path) {
    return sprintf("<script type='text/javascript'async src='%s'></script>", $path);
  }
}
