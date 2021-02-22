<?php

require_once('rdsm_api.php');
require_once(RDSM_SRC_DIR . '/helpers/rdsm_log_file_helper.php');

class RDSMEventsAPI {
  private $api_client;

  private $default_request_args = array(
    'timeout' => 10,
    'headers' => array('Content-Type' => 'application/json')
  );

  function __construct($user_credentials) {
    if (!isset($user_credentials)) {
      throw new InvalidArgumentException("You must provide a valid RDSMUserCredentials object", 1);
    }

    $api = new RDSMAPI(API_URL, $user_credentials);
    $this->api_client = $api;
  }

  public function post($event) {
    $body = array('body' => json_encode($event->payload));
    $args = array_merge($this->default_request_args, $body);
    
    $response = $this->api_client->post(EVENTS, $args);
    RDSMLogFileHelper::write_to_log_file($response['body']);

    if (is_wp_error($response)) {
      unset($event->payload);      
    }
    return $response;
  }
}
