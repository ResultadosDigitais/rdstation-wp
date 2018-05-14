<?php

// Require all setup files. We need to use a namespace here instead
$plugin_path = plugin_dir_path(__FILE__);
$plugin_file = $plugin_path."/integracao-rd-station.php";

require_once($plugin_path."/setup/rdsm_uninstall_hooks.php");

register_uninstall_hook($plugin_file, array('RDSMUninstallHooks', 'trigger'));
