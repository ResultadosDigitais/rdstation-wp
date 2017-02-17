<?php

require_once('settings_menu.php');

add_action('admin_init', 'initialize_rdstation_settings_page');
function initialize_rdstation_settings_page() {
  register_setting( 'rdstation-settings-page', 'rd_settings' );

  add_settings_section(
    'rd_general_settings_section',
    'Configurações Gerais',
    null,
    'rdstation-settings-page'
  );

  add_settings_section(
    'rd_woocommerce_settings_section',
    'Integração com WooCommerce',
    null,
    'rdstation-settings-page'
  );

  add_settings_field(
    'rd_public_token',
    'Token Público',
    'rd_public_token_callback',
    'rdstation-settings-page',
    'rd_general_settings_section'
  );

  add_settings_field(
    'rd_private_token',
    'Token Privado',
    'rd_private_token_callback',
    'rdstation-settings-page',
    'rd_general_settings_section'
  );

  add_settings_field(
    'rd_woocommerce_conversion_identifier',
    'Identificador das conversões de checkout',
    'rd_woocommerce_conversion_identifier_callback',
    'rdstation-settings-page',
    'rd_woocommerce_settings_section'
  );
}

function rdstation_settings_page_callback() {
  ?>
  <form action='options.php' method='post'>

    <h1>RD Station</h1>

    <?php
    settings_fields( 'rdstation-settings-page' );
    do_settings_sections( 'rdstation-settings-page' );
    submit_button();
    ?>

  </form>
  <?php
}

function rd_public_token_callback() {
  $options = get_option( 'rd_settings' ); ?>
	<input type='text' name='rd_settings[rd_public_token]' size="32" value='<?php echo $options['rd_public_token']; ?>'>
  <?php
}

function rd_private_token_callback() {
  $options = get_option( 'rd_settings' ); ?>
	<input type='text' name='rd_settings[rd_private_token]' size="32" value='<?php echo $options['rd_private_token']; ?>'>
  <?php
}

function rd_enable_woocommerce_integration_callback() {
  $options = get_option( 'rd_settings' ); ?>
  <input type='checkbox' name='rd_settings[rd_enable_woocommerce_integration]' size="32" value='<?php echo $options['rd_enable_woocommerce_integration']; ?>'>
  <?php
}

function rd_woocommerce_conversion_identifier_callback() {
  $options = get_option( 'rd_settings' ); ?>
  <input type='text' name='rd_settings[rd_woocommerce_conversion_identifier]' size="32" value='<?php echo $options['rd_woocommerce_conversion_identifier']; ?>'>
  <?php
}