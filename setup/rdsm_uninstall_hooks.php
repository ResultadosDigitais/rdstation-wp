<?php

class RDSMUninstallHooks {
  public function trigger() {
    self::delete_authentication_columns();
  }

  private function delete_authentication_columns() {
    delete_option('rdsm_refresh_token');
    delete_option('rdsm_access_token');
  }
}
