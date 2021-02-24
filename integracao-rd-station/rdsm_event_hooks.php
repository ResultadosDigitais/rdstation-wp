<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_site_initialized.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_admin_initialized.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_oauth_connected.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_oauth_disconnected.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_settings_page_loaded.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_plugin_uninstalled.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_tracking_status_updated.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_integration_form_changed.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_integration_form_woocommerce.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_log_file.php');

class RDSMEventHooks {
  public static function register($events) {
    foreach ($events as $event) {
      $event->register_hooks();
    }
  }
}

$rdsm_wp_events = array(
  new RDSMAdminInitialized,
  new RDSMSiteInitialized,
  new RDSMOauthConnected,
  new RDSMOauthDisconnected,
  new RDSMPluginUninstalled,
  new RDSMTrackingStatusUpdated,
  new RDSMSettingsPageLoaded,
  new RDSMIntegrationFormChanged,
  new RDSMIntegrationFormWooCommerce,
  new RDSMLogFile
);

RDSMEventHooks::register($rdsm_wp_events);
