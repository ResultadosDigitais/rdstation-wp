<?php

add_action( 'admin_menu', 'rdstation_menu' );
function rdstation_menu() {
  $text_domain = 'rdstation-wp';

  add_options_page(
    __('Configurações RD Station', $text_domain),
    __('Integração RD Station', $text_domain),
    'manage_options',
    'rdstation-settings-page',
    'rdstation_settings_page_callback'
  );
}
