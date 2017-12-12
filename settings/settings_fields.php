<?php

class RDSettingsFields {
  public function register_fields() {
    add_settings_field(
      'rd_public_token',
      __('Public Token', 'integracao-rd-station'),
      'rd_public_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_private_token',
      __('Private Token', 'integracao-rd-station'),
      'rd_private_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_woocommerce_conversion_identifier',
      __('Identifier from checkout conversions', 'integracao-rd-station'),
      'rd_woocommerce_conversion_identifier_callback',
      'rdstation-settings-page',
      'rd_woocommerce_settings_section'
    );
  }
}
