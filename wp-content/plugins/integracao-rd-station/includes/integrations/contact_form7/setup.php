<?php

require_once('integration.php');
require_once(RDSM_SRC_DIR . '/resources/rdsm_conversion.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_conversions_api.php');

$resource = new RDSMConversion();
$api_client = new RDSMConversionsAPI();

$integration = new RDContactForm7Integration($resource, $api_client);
$integration->setup();
