<?php

class RDSMLegacyTokens {
  public function save() {
    $tokens = $_POST['tokens'][0];
    $public_token = $tokens['public'];
    $private_token = $tokens['private'];

    update_option('rdsm_public_token', $public_token);
    update_option('rdsm_private_token', $private_token);

    die();
  }
}

add_action('wp_ajax_rd-persist-legacy-tokens',  array('RDSMLegacyTokens','save'));
