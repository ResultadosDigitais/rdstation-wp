<?php

add_action( 'admin_menu', 'rdstation_menu' );
function rdstation_menu() {
  global $rdsm_settings_page;

  $rdsm_settings_page = add_options_page(
    __('RD Station Settings', 'integracao-rd-station'),
    __('RD Station Settings', 'integracao-rd-station'),
    'manage_options',
    'rdstation-settings-page',
    'rdstation_settings_page_callback'
  );
}
