<?php

class RDSMLegacyUserCredentials {
  private $public_token;
  private $private_token;

  public function save_public_token($public_token) {
    if (empty($public_token)) {
      return false;
    }

    update_option('rdsm_public_token', $public_token);

    return true;
  }

  public function save_private_token($private_token) {
    if (empty($private_token)) {
      return false;
    }

    update_option('rdsm_private_token', $private_token);

    return true;
  }
}
