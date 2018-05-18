<?php

class RDSMTrackingCodeHooks {
  private $api;
  private $options;

  public function __construct($api_client) {
    $this->$api = $api_client;
    $this->$options = get_option( 'rdsm_general_settings' );
  }

  public function handle() {
    if (!!$this->$options[ 'enable_tracking_code' ]) {
      $this->enable();
    }
  }

  public function tracking_code_hook() {
    if (is_home() || is_single() || is_page()) {
        echo html_entity_decode($this->tracking_code_script_tag($this->$options[ 'rdsm_tracking_code' ]));
    }
  
    return; 
  }

  public function persist_tracking_code() {
    $response = $this->$api->tracking_code();

    if (!empty($response->{ 'path' })) {
      update_option( 'rdsm_tracking_code', $response->{ 'path' } );
      
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
    return sprintf("<script type='text/javascript' async src='%s'></script>", $path);
  }
}
