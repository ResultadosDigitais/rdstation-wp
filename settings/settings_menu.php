<?php

add_action( 'admin_menu', 'rdstation_menu' );
function rdstation_menu() {
  add_options_page(
    __('RD Station Settings', 'integracao-rd-station'),
    __('RD Station Integration', 'integracao-rd-station'),
    'manage_options',
    'rdstation-settings-page',
    'rdstation_settings_page_callback'
  );
}
