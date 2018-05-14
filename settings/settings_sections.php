<?php

class RDSettingsSection {
  public function register_sections() {
    add_settings_section(
      'rd_woocommerce_settings_section',
      __('WooCommerce Integration', 'integracao-rd-station'),
      null,
      'rdstation-settings-page'
    );
  }
}
