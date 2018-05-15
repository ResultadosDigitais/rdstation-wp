<?php

class RDSettingsSection {
  public function register_sections() {
    add_settings_section(
      'rdsm_general_settings_section',
      __('General Settings', 'integracao-rd-station'),
      null,
      'rdsm_general_settings'
    );

    add_settings_section(
      'rdsm_woocommerce_settings_section',
      __('WooCommerce Integration', 'integracao-rd-station'),
      null,
      'rdsm_woocommerce_settings'
    );
  }
}
