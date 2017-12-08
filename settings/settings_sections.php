<?php

class RDSettingsSection {
  public function register_sections() {
    add_settings_section(
      'rd_general_settings_section',
      __('Configurações Gerais', 'rdstation-wp'),
      null,
      'rdstation-settings-page'
    );

    add_settings_section(
      'rd_woocommerce_settings_section',
      __('Integração com WooCommerce', 'rdstation-wp'),
      null,
      'rdstation-settings-page'
    );
  }
}
