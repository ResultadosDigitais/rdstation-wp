<?php

/*
Plugin Name: 	Integração RD Station
Plugin URI: 	https://wordpress.org/plugins/integracao-rdstation
Description:  Integre seus formulários de contato do WordPress com o RD Station
Version:      3.2.5
Author:       Resultados Digitais
Author URI:   http://resultadosdigitais.com.br
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  integracao-rd-station

Integração RD Station is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Integração RD Station is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Integração RD Station. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/

require_once('config.php');

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once('rd_custom_post_type.php');
require_once('metaboxes/add_custom_scripts.php');

// plugin setup
require_once('initializers/contact_form7.php');
require_once('initializers/gravity_forms.php');
require_once('settings/settings_page.php');

// setup available integrations
require_once(SRC_DIR . '/integrations/contact_form7/setup.php');
require_once(SRC_DIR . '/integrations/gravity_forms/setup.php');
require_once(SRC_DIR . '/integrations/woocommerce/setup.php');

// API client
require_once(SRC_DIR . '/client/rdsm_settings_api.php');

// Authorization tokens persistence
require_once(SRC_DIR . '/authorization/rdsm_tokens.php');
require_once(SRC_DIR . '/authorization/rdsm_authorization_check.php');
require_once(SRC_DIR . '/client/rdsm_legacy_tokens.php');

// Setup hooks
require_once(SRC_DIR . "/hooks/rdsm_activation_hooks.php");
require_once(SRC_DIR . "/hooks/rdsm_oauth_disconnect_hooks.php");
require_once(SRC_DIR . "/hooks/rdsm_tracking_code_hooks.php");
require_once(SRC_DIR . "/hooks/rdsm_admin_tracking_code_hooks.php");
require_once(SRC_DIR . "/hooks/rdsm_uninstall_hooks.php");

$rdsm_activation_hook = new RDSMActivationHooks;
register_activation_hook(__FILE__, array($rdsm_activation_hook, 'trigger'));

register_uninstall_hook(__FILE__, array('RDSMUninstallHooks', 'trigger'));

// Legacy persistente
$rdsm_token = new RDSMTokens;

// Tracking Code
add_action( 'admin_init',  'rdsm_tracking_code_hooks', 1);
function rdsm_tracking_code_hooks() {
  $access_token = get_option('rdsm_access_token');
  $refresh_token = get_option('rdsm_refresh_token');

  if (!empty($access_token) && !empty($refresh_token)) {
    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMSettingsAPI($user_credentials);

    $rdsm_admin_tracking_code_hook = new RDSMAdminTrackingCodeHooks($api_instance);
    $rdsm_admin_tracking_code_hook->active_hooks();
  }
}

// Enable on site script
$rdsm_tracking_code_hook = new RDSMTrackingCodeHooks;
$rdsm_tracking_code_hook->handle();


require_once("load_scripts.php");
