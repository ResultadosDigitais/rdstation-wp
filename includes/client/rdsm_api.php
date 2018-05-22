<?php

class RDSMAPI {
  private $api_url;
  private $user_credentials;

  function __construct($server_url, $user_credentials) {
    $this->api_url = $server_url;
    $this->user_credentials = $user_credentials;
  } 

  public function get($resource, $args = array()) {
    if ($this->user_credentials->access_token()) {
      $args['headers'] = $this->authorization_header($args);
    }

    return wp_remote_get(sprintf("%s%s", $this->api_url, $resource), $args);
  }

  public function post($resource, $args = array()) {
    if ($this->user_credentials->access_token()) {
      $args['headers'] = $this->authorization_header($args);
    }

    return wp_remote_post(sprintf("%s%s", $this->api_url, $resource), $args);
  }

  private function authorization_header($args) {
    $authorization_header = array('Authorization' => 'Bearer ' . $this->user_credentials->access_token());

    if (is_array($args) && $args['headers']) {
      return array_merge($args['headers'], $authorization_header);
    }

    return $authorization_header;
  }

  private function handle_refresh_token($response) {
    $body = json_decode($response['body']);

    if ($response->code == 401) {

    }
  }
}
