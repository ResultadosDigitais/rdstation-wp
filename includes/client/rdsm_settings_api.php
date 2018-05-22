<?php

require_once('rdsm_api.php');

class RDSMSettingsAPI {
  private $api_client;

  function __construct() {
    $api = new RDSMAPI(API_URL);
    $this->api_client = $api;
  }

  public function tracking_code() {
    $response = $this->api_client->get(TRACKING_CODE);

    return wp_remote_retrieve_body($response);
  }
}
