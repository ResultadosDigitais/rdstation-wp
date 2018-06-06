<?php

require_once('settings_menu.php');
require_once('settings_sections.php');
require_once('settings_fields.php');

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
    <?php _e('RD Station Integration Settings', 'integracao-rd-station') ?>
  </h1>

  <div class="rd-oauth-connect-section">
    <p>
      <?php _e('It automatically activates the RD Station Marketing tracking code in your Wordpress pages, enabling features such as Lead Tracking and Pop ups. It also integrates the contact forms that capture Leads that convert in your website forms directly to RD Station Marketing.', 'integracao-rd-station') ?>
    </p>

    <div class="rdsm-connected hidden">
      <p>
        <?php _e('The plugin is connected and allows the use of Tracking Code, pop-ups and form integrations. If you want to disconnect, just click the button below.', 'integracao-rd-station') ?>
      </p>

      <button type="button" class="button rd-oauth-disconnect" id="rdsm-oauth-disconnect">
        <?php _e('Disconnect from RD Station', 'integracao-rd-station') ?>
      </button>
    </div>

    <div class="rdsm-disconnected hidden">
      <p>
        <?php _e('To continue to install the Tracking Code, we need first to confirm your access permissions.', 'integracao-rd-station') ?>
      </p>
      <button type="button" class="button rd-oauth-integration" id="rdsm-oauth-connect">
        <?php _e('Connect to RD Station', 'integracao-rd-station') ?>
      </button>
    </div>
  </div>

  <p class="nav-tab-wrapper">
    <a href="?page=rdstation-settings-page&tab=general" class="nav-tab <?php echo rdsm_tab_class('general') ?>">
      <?php _e('General Settings', 'integracao-rd-station') ?>
    </a>

    <a href="?page=rdstation-settings-page&tab=woocommerce" class="nav-tab <?php echo rdsm_tab_class('woocommerce') ?>">
      <?php _e('WooCommerce', 'integracao-rd-station') ?>
    </a>
  </p>

  <form action='options.php' method='post'>
    <?php
      switch ($active_tab) {
        case 'general':
          settings_fields('rdsm_general_settings');
          do_settings_sections('rdsm_general_settings');
          rdsm_tracking_code_warning_html();
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
  $current_value = isset($options['enable_tracking_code']) ? $options['enable_tracking_code'] : ''; ?>

  <label class="checkbox-switch">
    <input type="checkbox" id="rdsm-enable-tracking" name="rdsm_general_settings[enable_tracking_code]" value="1" <?php checked($current_value, 1); ?> >
    <span class="checkbox-slider checkbox-slider-round">
      <span class="checkbox-slider-off hidden"><?php _e('Off', 'integracao-rd-station') ?></span>
      <span class="checkbox-slider-on hidden"><?php _e('On', 'integracao-rd-station') ?></span>
    </span>
  </label>
  <small id="rdsm-tracking-warning" class="hidden"> <?php _e('You need to connect to RD Station before enable/disable this feature.', 'integracao-rd-station') ?> </small>
  <?php
}

function rdsm_tracking_code_warning_html() { ?>
  <div class="rdsm-tracking-code-validation-warning">
    <div class="notice notice-warning">
      <p>
        <?php _e('Warning: validate it on RD Station Marketing:', 'integracao-rd-station') ?>
        <a href="https://app.rdstation.com.br/configuracoes/analise-e-monitoramento" target="_blank">
          <?php _e('Validate', 'integracao-rd-station') ?>
        </a>
      </p>
    </div>
    <div class="notice notice-warning">
      <p>
        <?php _e("Some custom themes don't include the WordPress global footer, causing the tracking code to not work.", 'integracao-rd-station'); ?>
      </p>
    </div>
  </div>
<?php }


// HELPER METHODS
function rdsm_active_tab() {
  return $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
}

function rdsm_tab_class($tab) {
  return $tab == rdsm_active_tab() ? 'nav-tab-active' : '';
}
