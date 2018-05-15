<?php

require_once('settings_menu.php');
require_once('settings_sections.php');
require_once('settings_fields.php');

add_action('admin_init', 'initialize_rdstation_settings_page');
function initialize_rdstation_settings_page() {
  register_setting('rdsm_general_settings', 'rdsm_general_settings');
  register_setting('rdsm_woocommerce_settings', 'rdsm_woocommerce_settings');

  $sections = new RDSettingsSection;
  $sections->register_sections();

  $fields = new RDSettingsFields;
  $fields->register_fields();
}

function rdstation_settings_page_callback() {
  $active_tab = rdsm_active_tab();
  ?>

  <h1>
    <?php echo __('RD Station Settings', 'integracao-rd-station') ?>
  </h1>

  <?php update_option('rdsm_access_token', null);
  if (get_option('rdsm_access_token')) :?>
      <?php echo __('Already connected to your RD Station account', 'integracao-rd-station') ?>
      <span class="dashicons dashicons-yes"></span>
  <?php else: ?>
    <button type="button" class="button button-warning rd-oauth-integration">
      <?php echo __('Connect to RD Station', 'integracao-rd-station') ?>
    </button>
  <?php endif; ?>

  <p class="nav-tab-wrapper">
    <a href="?page=rdstation-settings-page&tab=general" class="nav-tab <?php echo rdsm_tab_class('general') ?>">
      <?php echo __('General Settings', 'integracao-rd-station') ?>
    </a>

    <a href="?page=rdstation-settings-page&tab=woocommerce" class="nav-tab <?php echo rdsm_tab_class('woocommerce') ?>">
      <?php echo __('WooCommerce', 'integracao-rd-station') ?>
    </a>
  </p>

  <form action='options.php' method='post'>
    <?php
      switch ($active_tab) {
        case 'general':
          settings_fields('rdsm_general_settings');
          do_settings_sections('rdsm_general_settings');
          break;
        case 'woocommerce':
          settings_fields('rdsm_woocommerce_settings');
          do_settings_sections('rdsm_woocommerce_settings');
          break;
      }

      submit_button(); ?>
  </form>

  <?php
}

function rdsm_woocommerce_conversion_identifier_html() {
  $options = get_option( 'rdsm_woocommerce_settings' ); ?>
  <input type='text' name='rdsm_woocommerce_settings[conversion_identifier]' size="32" value='<?php echo $options['conversion_identifier']; ?>'>
  <?php
}

function rdsm_enable_tracking_code_html() {
  $options = get_option( 'rdsm_general_settings' );
  $current_value = isset($options['enable_tracking_code']) ? $options['enable_tracking_code'] : '' ?>
  <input type="checkbox" name="rdsm_general_settings[enable_tracking_code]" value="1" <?php checked($current_value, 1); ?> >
<?php }


// HELPER METHODS
function rdsm_active_tab() {
  return $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
}

function rdsm_tab_class($tab) {
  return $tab == rdsm_active_tab() ? 'nav-tab-active' : '';
}
