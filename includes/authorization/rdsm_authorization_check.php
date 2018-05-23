<?php

class RDSMAuthorizationCheck {
  public function check() {
    get_option('rdsm_public_token');
    die();
  }
}

add_action('wp_ajax_rdsm-authorization-check',  array('RDSMAuthorizationCheck','check'));
