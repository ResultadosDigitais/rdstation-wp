<?php

require_once('rdsm_api');

class RDSSettingsMAPI {
  private $api_client;

  function __construct($api_client) {
    $api = new RDSMAPI("https://staging.rdstation.com.br/api/v2");
    $this->$api_client = $api;
  } 

  function tracking_code() {
    $respone = $this->$api_client->get('/settings/tracking_code'); 

    var_dump($reponse);
  }
}

