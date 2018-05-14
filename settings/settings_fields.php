<?php

class RDSettingsFields {
  public function register_fields() {
    add_settings_field(
      'rd_woocommerce_conversion_identifier',
      __('Identifier from checkout conversions', 'integracao-rd-station'),
      'rd_woocommerce_conversion_identifier_callback',
      'rdstation-settings-page',
      'rd_woocommerce_settings_section'
    );
  }
}
