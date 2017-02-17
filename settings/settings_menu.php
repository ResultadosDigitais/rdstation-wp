<?php

add_action( 'admin_menu', 'rdstation_menu' );
function rdstation_menu() {
  add_options_page(
    'Configurações RD Station',
    'RD Station',
    'manage_options',
    'rdstation-settings-page',
    'rdstation_settings_page_callback'
  );
}