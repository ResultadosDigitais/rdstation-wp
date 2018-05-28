<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMAdminInitialized implements RDSMEventsInterface {
  public function register_hooks() {
    add_action('admin_init', 'initialize_rdstation_settings_page');
  }
}
