<?php

require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');
require_once(RDSM_SRC_DIR . '/entities/rdsm_legacy_user_credentials.php');

class RDSMAdminInitialized implements RDSMEventsInterface {
  function __construct() {
    $this->legacy_options = get_option('rd_settings');
    $this->new_woocommerce_options = get_option('rdsm_woocommerce_settings');
  }

  public function register_hooks() {
    add_action('admin_init', array($this, 'admin_init_hooks'));
  }

  public function admin_init_hooks() {
    initialize_rdstation_settings_page();
    $base_migrated = get_option('rdsm_base_migrated');

    if (empty($base_migrated)) {
      $this->migrate_legacy_woocoommerce_identifier();
      $this->migrate_legacy_tokens();
      update_option('rdsm_base_migrated', true);
    }
  }

  private function migrate_legacy_woocoommerce_identifier() {
    if ($this->should_migrate_identifier()) {
      $this->new_woocommerce_options['conversion_identifier'] = $this->legacy_options['rd_woocommerce_conversion_identifier'];
      update_option('rdsm_woocommerce_settings', $this->new_woocommerce_options);
    }
  }

  private function migrate_legacy_tokens() {
    if ($this->should_migrate_tokens()) {
      $legacy_credentials = new RDSMLegacyUserCredentials;
      $legacy_credentials->save_public_token($this->legacy_options['rd_public_token']);
      $legacy_credentials->save_private_token($this->legacy_options['rd_private_token']);
    }
  }

  private function should_migrate_tokens() {
    $legacy_tokens_exists = isset($this->legacy_options['rd_public_token']) && isset($this->legacy_options['rd_private_token']);
    $new_tokens_exists = get_option('rdsm_public_token') && get_option('rdsm_private_token');

    return $legacy_tokens_exists && !$new_tokens_exists;
  }

  private function should_migrate_identifier() {
    $legacy_identifier = 'rd_woocommerce_conversion_identifier';
    return isset($this->legacy_options[$legacy_identifier]) && !isset($this->new_woocommerce_options['conversion_identifier']);
  }
}
