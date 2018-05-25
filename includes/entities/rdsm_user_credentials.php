<?php

class RDSMUserCredentials {
  private $access_token;
  private $refresh_token;

  public function __construct($access_token, $refresh_token) {
    $this->access_token = $access_token;
    $this->refresh_token = $refresh_token;
  }

  public function access_token() {
    return $this->access_token;
  }

  public function refresh_token() {
    return $this->refresh_token;
  }

  public function save_access_token($access_token) {
    if (empty($access_token)) {
      return false;
    }

    $this->access_token = $access_token;

    update_option('rdsm_access_token', $this->access_token);

    return true;
  }

  public function save_refresh_token($refresh_token) {
    if (empty($refresh_token)) {
      return false;
    }
    
    $this->refresh_token = $refresh_token;

    update_option('rdsm_refresh_token', $this->refresh_token);

    return true;
  }

}