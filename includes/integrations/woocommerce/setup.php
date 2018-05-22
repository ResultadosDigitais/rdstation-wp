<?php

require_once('integration.php');
require_once(SRC_DIR . '/resources/rdsm_conversion.php');
require_once(SRC_DIR . '/client/rdsm_conversions_api.php');

$resource = new RDSMConversion();
$api_client = new RDSMConversionsAPI();

$integration = new RDSMWoocommerceIntegration($resource, $api_client);
$integration->setup();
