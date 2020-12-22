<?php

require_once('integration.php');
require_once(RDSM_SRC_DIR . '/resources/rdsm_event.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_events_api.php');

$resource = new RDSMEvent();
$api_client = new RDSMEventsAPI();

$integration = new RDSMWoocommerceIntegration($resource, $api_client);
$integration->setup();
