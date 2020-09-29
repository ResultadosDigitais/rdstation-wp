<?php

/*
Plugin Name: 	Integração RD Station
Plugin URI: 	https://wordpress.org/plugins/integracao-rdstation
Description:  Integre seus formulários de contato do WordPress com o RD Station
Version:      4.0.8
Author:       RD Station
Author URI:   https://www.rdstation.com/
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

define('RDSM_PLUGIN_FILE', __FILE__);

require_once('config.php');

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once('rd_custom_post_type.php');
require_once('metaboxes/add_custom_scripts.php');

// plugin setup
require_once('initializers/contact_form7.php');
require_once('initializers/gravity_forms.php');
require_once('settings/settings_page.php');

// setup available integrations
require_once(RDSM_SRC_DIR . '/integrations/contact_form7/setup.php');
require_once(RDSM_SRC_DIR . '/integrations/gravity_forms/setup.php');
require_once(RDSM_SRC_DIR . '/integrations/woocommerce/setup.php');

// Setup hooks
require_once('rdsm_event_hooks.php');

// Load assets and scripts
require_once('rdsm_assets_loader.php');
RDSMAssetsLoader::load_assets();
