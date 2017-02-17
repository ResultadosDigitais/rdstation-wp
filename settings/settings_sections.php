<?php

class RDSettingsSection {
  public function register_sections() {
    add_settings_section(
      'rd_general_settings_section',
      'Configurações Gerais',
      null,
      'rdstation-settings-page'
    );

    add_settings_section(
      'rd_woocommerce_settings_section',
      'Integração com WooCommerce',
      null,
      'rdstation-settings-page'
    );
  }
}