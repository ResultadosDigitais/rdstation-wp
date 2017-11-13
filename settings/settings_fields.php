<?php

class RDSettingsFields {

  private $text_domain = 'rdstation-wp';

  public function register_fields() {
    add_settings_field(
      'rd_public_token',
      __('Token Público', $text_domain),
      'rd_public_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_private_token',
      __('Token Privado', $text_domain),
      'rd_private_token_callback',
      'rdstation-settings-page',
      'rd_general_settings_section'
    );

    add_settings_field(
      'rd_woocommerce_conversion_identifier',
      __('Identificador das conversões de checkout', $text_domain),
      'rd_woocommerce_conversion_identifier_callback',
      'rdstation-settings-page',
      'rd_woocommerce_settings_section'
    );
  }
}
