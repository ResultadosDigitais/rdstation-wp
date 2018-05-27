<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_plugin_activated.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_site_initialized.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_oauth_connected.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_oauth_disconnected.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_settings_page_loaded.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_plugin_uninstalled.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_tracking_status_updated.php');

class RDSMEventHooks {
  public function register($events) {
    foreach ($events as $event) {
      $event->register_hooks();
    }
  }
}

$plugin_events = array(
  new RDSMPluginActivated,
  new RDSMSiteInitialized,
  new RDSMOauthDisconnected,
  new RDSMPluginUninstalled,
  new RDSMTrackingStatusUpdated,
  new RDSMSettingsPageLoaded
);

$event_hooks = new RDSMEventHooks();
$event_hooks->register($plugin_events);
