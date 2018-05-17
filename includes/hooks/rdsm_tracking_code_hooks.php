<?php

class RDSMTrackingCodelHooks {
  private $api;

  public function __construct($api_client) {
    $this->$api = $api_client;
  }

  public function enable() {
    $this->api->tracking_code();

    add_action('wp_footer', 'tracking_code_hook');
  }

  public function disable() {
    remove_action('wp_fotter', 'tracking_code_hook');
  }

  private function tracking_code_hook() {
    $options = get_option( 'rdsm_tag_manager_settings' );
  
    if (is_home() || is_single() || is_page()) {
        echo html_entity_decode($options['tracking_code']);
    }
  
     return;
  }
}
