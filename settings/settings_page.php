<?php

require_once('settings_menu.php');
require_once('settings_sections.php');
require_once('settings_fields.php');

add_action('admin_init', 'initialize_rdstation_settings_page');
function initialize_rdstation_settings_page() {
  register_setting( 'rdstation-settings-page', 'rd_settings' );

  $sections = new RDSettingsSection;
  $sections->register_sections();

  $fields = new RDSettingsFields;
  $fields->register_fields();
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

function rd_woocommerce_conversion_identifier_callback() {
  $options = get_option( 'rd_settings' ); ?>
  <input type='text' name='rd_settings[rd_woocommerce_conversion_identifier]' size="32" value='<?php echo $options['rd_woocommerce_conversion_identifier']; ?>'>
  <?php
}
