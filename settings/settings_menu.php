<?php

add_action( 'admin_menu', 'rdstation_menu' );
function rdstation_menu() {
  add_options_page(
    __('Configurações RD Station', 'rdstation-wp'),
    __('Integração RD Station', 'rdstation-wp'),
    'manage_options',
    'rdstation-settings-page',
    'rdstation_settings_page_callback'
  );
}
