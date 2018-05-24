<?php

class RDSMAuthorizationCheck {
  public function check() {
    $response = array('token' => get_option('rdsm_access_token'));
    wp_send_json($response);
  }
}

add_action('wp_ajax_rdsm-authorization-check',  array('RDSMAuthorizationCheck','check'));
