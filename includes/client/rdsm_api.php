<?php

class RDSMAPI {
  private $api_url;
  private $user_access_token;

  function __construct($server_url, $user_access_token) {
    $this->api_url = $server_url;
    $this->user_access_token = $user_access_token;
  } 

  function get($resource, $args = array()) {
    if ($args['headers']) {
      $args = $this->authorization_header($args);
    }

    return wp_remote_get(sprintf("%s%s", $this->api_url, $resource), $args);
  }

  function post($resource, $args) {
    if ($args['headers']) {
      $args = $this->authorization_header($args);
    }

    return wp_remote_post(sprintf("%s%s", $this->api_url, $resource), $args);
  }

  private function authorization_header($args) {
    $authorization_header = array('Authorization' => sprintf('Bearer %', $this->user_access_token));
    $args['headers'] = array_merge($args['headers'], $authorization_header);

    return $args;
  }
}
