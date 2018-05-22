<?php

require_once(__DIR__.'./../entities/rdsm_user_credentials.php');

class RDSMTokens {
  public function __construct() {
    add_action('wp_ajax_rd-persist-tokens',  array($this, 'save'), 1);
  }

  public static function save() {
    $access_token_value = $_POST['accessToken'];
    $refresh_token_value = $_POST['refreshToken'];
    
    $user_credentials = new RDSMUserCredentials($access_token_value, $refresh_token_value);

    $user_credentials->save_access_token($access_token_value);
    $user_credentials->save_refresh_token($refresh_token_value);
    
    wp_die($user_credentials);
  }
}

