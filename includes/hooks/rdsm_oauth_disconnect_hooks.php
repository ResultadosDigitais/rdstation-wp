<?php

class RDSMOauthDisconnectHooks {
  public function trigger() {
    delete_option('rdsm_public_token');
    delete_option('rdsm_private_token');
    delete_option('rdsm_access_token');
    delete_option('rdsm_refresh_token');

    die();
  }
}

add_action('wp_ajax_rdsm-disconnect-oauth',  array('RDSMOauthDisconnectHooks','trigger'));
