<?php
require_once(__DIR__ . '/../client/rdsm_settings_api.php');

class RDSMTrackingCodeHooks {
  public function handle() {
    if ($this->can_enable_tracking_code()) {
      $this->enable_tracking_code_on_site();
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

  private function can_enable_tracking_code() {
    $options = get_option('rdsm_general_settings');
    if (is_array($options) && !!$options['enable_tracking_code']) {
      return true;
    }
    return false;
  }
  
  private function enable_tracking_code_opt_in() {
    add_action('update_option_rdsm_general_settings', array($this, 'rdsm_handle_tracking_code_option'), 2);
  }
  
  private function enable_tracking_code_on_site() {
    add_action('wp_footer', array($this, 'tracking_code_hook'), 1);
  }
  
  private function disable_tracking_code_on_site() {
    remove_action('wp_footer', array($this, 'tracking_code_hook'), 1);
  }
  
  private function tracking_code_script_tag($path) {
    return "<script type='text/javascript' async src='". $path ."'></script>";
  }
}
