<?php

class RDSMAPI {
  private $api_url;

  function __construct($server_url) {
    $this->api_url = $server_url;
  }

  function get($resource, $args) {
    return wp_remote_get(sprintf("%s%s", $this->api_url, $resource), $args);
  }

  function post($resource, $args) {
    return wp_remote_post(sprintf("%s%s", $this->api_url, $resource), $args);
  }
}
