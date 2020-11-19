<?php

class RDSettingsFields {
  public function register_fields() {
    self::general_fields();
    self::woocommerce_fields();
  }

  private function general_fields() {
    add_settings_field(
      'rdsm_enable_tracking_code',
      __('Tracking Code:', 'integracao-rd-station'),
      'rdsm_enable_tracking_code_html',
      'rdsm_general_settings',
      'rdsm_general_settings_section'
    );
  }

  private function woocommerce_fields() {
    add_settings_field(
      'rdsm_woocommerce_conversion_identifier',
      __('Identifier from checkout conversions', 'integracao-rd-station'),
      'rdsm_woocommerce_conversion_identifier_html',
      'rdsm_woocommerce_settings',
      'rdsm_woocommerce_settings_section'
    );
  }
}
