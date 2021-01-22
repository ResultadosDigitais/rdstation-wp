<?php

require_once('rdsm_api.php');

class RDSMFieldsAPI {
  private $api_client;
  
  function __construct($user_credentials) {
    if (!isset($user_credentials)) {
      throw new InvalidArgumentException("You must provide a valid RDSMUserCredentials object", 1);
    }

    $api = new RDSMAPI(API_URL, $user_credentials);
    $this->api_client = $api;
  }

  public function contacts_fields() {
    $response = $this->api_client->get(CONTACTS_FIELDS);    
    return $response;
  }
}
