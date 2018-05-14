<?php

class RDSMTokens {
  public static function save() {
    $access_token_value = $_POST['accessToken'];
    $refresh_token_value = $_POST['refreshToken'];

    update_option('rdsm_access_token', $access_token_value);
    update_option('rdsm_refresh_token', $refresh_token_value);

    die();
  }
}

add_action('wp_ajax_rd-persist-tokens',  array('RDSMTokens','save'));
