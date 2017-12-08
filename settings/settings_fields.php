<?php

class RDSettingsFields {
  public function register_fields() {
    add_settings_field(
      'rd_public_token',
      __('Token Público', 'rdstation-wp'),
      'rd_public_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_private_token',
      __('Token Privado', 'rdstation-wp'),
      'rd_private_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_woocommerce_conversion_identifier',
      __('Identificador das conversões de checkout', 'rdstation-wp'),
      'rd_woocommerce_conversion_identifier_callback',
      'rdstation-settings-page',
      'rd_woocommerce_settings_section'
    );
  }
}
