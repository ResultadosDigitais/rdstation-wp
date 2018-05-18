<?php

require_once('rdsm_api.php');

class RDSMConversionsAPI {
  private $api_client;

  function __construct() {
    $api = new RDSMAPI(LEGACY_API_URL);
    $this->$api_client = $api;
  }

  public function create_lead_conversion($args) {
    $response = $this->$api_client->post(CONVERSIONS, $args);
    return $response;
  }
}
