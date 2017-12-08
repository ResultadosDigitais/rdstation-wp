<?php

class RDSettingsSection {

  private $text_domain = 'rdstation-wp';

  public function register_sections() {
    add_settings_section(
      'rd_general_settings_section',
      __('Configurações Gerais', $this->text_domain),
      null,
      'rdstation-settings-page'
    );

    add_settings_section(
      'rd_woocommerce_settings_section',
      __('Integração com WooCommerce', $this->text_domain),
      null,
      'rdstation-settings-page'
    );
  }
}
