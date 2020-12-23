<?php

require_once('integration.php');
require_once(RDSM_SRC_DIR . '/resources/rdsm_event.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_events_api.php');
require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');

$access_token = get_option('rdsm_access_token');
$refresh_token = get_option('rdsm_refresh_token');
$credentials = new RDSMUserCredentials($access_token, $refresh_token);

$resource = new RDSMEvent();
$api_client = new RDSMEventsAPI($credentials);

$integration = new RDSMGravityFormsIntegration($resource, $api_client);
$integration->setup();
