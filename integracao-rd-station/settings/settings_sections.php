<?php

class RDSettingsSection {
  public function register_sections() {
    add_settings_section(
      'rdsm_general_settings_section',
      null,
      null,
      'rdsm_general_settings'
    );

    add_settings_section(
      'rdsm_woocommerce_settings_section',
      null,
      null,
      'rdsm_woocommerce_settings'
    );

    add_settings_section(
      'rdsm_integrations_log_settings_section',
      null,
      null,
      'rdsm_integrations_log_settings'
    );
  }
}
