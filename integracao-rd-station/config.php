<?php

define('RDSM_ASSETS_URL', plugin_dir_url(__FILE__) . '/assets');
define('RDSM_SRC_DIR', dirname(__FILE__) . '/includes');

// URIs
define('LEGACY_API_URL', 'https://app.rdstation.com.br/api/1.3');
define('API_URL', 'https://api.rd.services');
define('REFRESH_TOKEN_URL', 'https://wp.rd.services/prod/oauth/refresh');

// Endpoints
define('CONVERSIONS', '/conversions');
define('EVENTS', '/platform/events');
define('CONTACTS', '/platform/contacts/');
define('TRACKING_CODE', '/marketing/tracking_code');
define('CONTACTS_FIELDS', '/platform/contacts/fields');

// File
define('RDSM_LOG_FILE_PATH', plugin_dir_path( __FILE__ ) . '/log');
define('RDSM_LOG_FILE_LIMIT', 1000);
