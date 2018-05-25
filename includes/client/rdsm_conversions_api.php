<?php

require_once('rdsm_api.php');
require_once(SRC_DIR . '/entities/null-objects/rdsm_user_credentials.php');

class RDSMConversionsAPI {
  private $api_client;

  const DEFAULT_REQUEST_ARGS = array(
    'timeout' => 10,
    'headers' => array('Content-Type' => 'application/json')
  );

  function __construct() {
    $null_user_credentials = new RDSMUserCredentialsNullObject;

    $api = new RDSMAPI(LEGACY_API_URL, $null_user_credentials);
    $this->api_client = $api;
  }

  public function post($conversion) {
    if($conversion->valid_payload()) {
      $body = array('body' => json_encode($conversion->payload));
      $args = array_merge(self::DEFAULT_REQUEST_ARGS, $body);

      $response = $this->api_client->post(CONVERSIONS, $args);

      if (is_wp_error($response)) {
        unset($conversion->payload);
      }

      return $response;
    }
  }
}
