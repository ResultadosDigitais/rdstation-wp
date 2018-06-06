<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMPluginUninstalled implements RDSMEventsInterface {
  public function register_hooks() {
    register_uninstall_hook(RDSM_PLUGIN_FILE, array(get_class(), 'uninstallation_hooks'));
  }

  public static function uninstallation_hooks() {
    self::delete_authentication_columns();
  }

  public static function delete_authentication_columns() {
    delete_option('rdsm_refresh_token');
    delete_option('rdsm_access_token');
  }
}
