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

    $response = wp_remote_get(sprintf("%s%s", $this->api_url, $resource), $args);

    if ($this->handle_expired_token($response)) {
      return $this->get($resource, $args);
    }

    return $response;
  }

  public function post($resource, $args = array()) {
    if ($this->user_credentials->access_token()) {
      $args['headers'] = $this->authorization_header($args);
    }

    $response = wp_remote_post(sprintf("%s%s", $this->api_url, $resource), $args);

    if ($this->handle_expired_token($response)) {
      return $this->post($resource, $args);
    }

    return $response;
  }

  private function authorization_header($args) {
    $authorization_header = array('Authorization' => 'Bearer ' . $this->user_credentials->access_token());

    if (is_array($args) && $args['headers']) {
      return array_merge($args['headers'], $authorization_header);
    }

    return $authorization_header;
  }


  private function refresh_token() {
    $refresh_token = $this->user_credentials->refresh_token();

    if (empty($refresh_token)) {
      return false;
    }

    $response = wp_remote_get(sprintf("%s/%s%s", REFRESH_TOKEN_URL, "refresh_token=", $refresh_token));

    if (wp_remote_retrieve_response_code($response) == 200) {
      $parsed_credentials = json_decode(wp_remote_retrieve_body($response));
      $this->update_user_credentials($parsed_credentials);

      return true;
    }

    return false;
  }

  private function handle_expired_token($response) {
    if (wp_remote_retrieve_response_code($response) != 401) {
      return false;
    }

    $authenticate_header = wp_remote_retrieve_header($response, 'www-authenticate');

    if (empty($authenticate_header)) {
      return false;
    }

    $header_information = explode(", ", $authenticate_header);

    if (in_array('error="expired_token"', $header_information) && $this->refresh_token()) {
      return true;
    }

    return false;
  }

  private function update_user_credentials($credentials) {
    $this->user_credentials->save_access_token($credentials->access_token);
    $this->user_credentials->save_refresh_token($credentials->refresh_token);
  }
}
