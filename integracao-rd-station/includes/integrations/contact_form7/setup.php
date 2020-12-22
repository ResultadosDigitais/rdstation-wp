<?php

require_once('integration.php');
// require_once(RDSM_SRC_DIR . '/resources/rdsm_conversion.php');
// require_once(RDSM_SRC_DIR . '/client/rdsm_conversions_api.php');

require_once(RDSM_SRC_DIR . '/resources/rdsm_event.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_events_api.php');

// $resource = new RDSMConversion();
// $api_client = new RDSMConversionsAPI();

$resource = new RDSMEvent();
$api_client = new RDSMEventsAPI();

$integration = new RDContactForm7Integration($resource, $api_client);
$integration->setup();
