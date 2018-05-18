<?php

require_once('rdsm_api.php');

class RDSMSettingsAPI {
  private $api_client;

  function __construct() {
    $api = new RDSMAPI("https://staging.rdstation.com.br/api/v2");
    $this->$api_client = $api;
  } 

  public function tracking_code() {
    return $this->$api_client->get('/settings/tracking_code');
  }
}

