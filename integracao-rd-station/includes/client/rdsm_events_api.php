<?php

require_once('rdsm_api.php');
require_once(RDSM_SRC_DIR . '/entities/null-objects/rdsm_user_credentials.php');

class RDSMEventsAPI {
  private $api_client;

  function __construct() {
    $null_user_credentials = new RDSMUserCredentialsNullObject;

    $api = new RDSMAPI(API_URL, $null_user_credentials);
    $this->api_client = $api;
  }

  public function post() {
    $response = $this->api_client->post(EVENTS);

    return $response;
  }
}
