<?php

class RDSMActivationHooks {
  function __construct() {
    $this->legacy_options = get_option('rd_settings');
    $this->new_woocommerce_options = get_option('rdsm_woocommerce_settings');
  }

  public function trigger() {
    $this->migrate_legacy_woocoommerce_identifier();
    $this->migrate_legacy_tokens();
  }

  private function migrate_legacy_woocoommerce_identifier() {
    if ($this->should_migrate_identifier()) {
      $this->new_woocommerce_options['conversion_identifier'] = $this->legacy_options['rd_woocommerce_conversion_identifier'];
      update_option('rdsm_woocommerce_settings', $this->new_woocommerce_options);
    }
  }

  private function migrate_legacy_tokens() {
    if ($this->should_migrate_tokens()) {
      update_option('rdsm_public_token', $this->legacy_options['rd_public_token']);
      update_option('rdsm_private_token', $this->legacy_options['rd_private_token']);
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
