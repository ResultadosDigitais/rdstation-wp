<?php

class RDSMActivationHooks {
  public static function trigger() {
    self::create_authentication_columns();
  }

  private static function create_authentication_columns() {
    add_option('rdsm_refresh_token', '255', '', 'no');
    add_option('rdsm_access_token', '255', '', 'no');
  }
}
