<?php

add_action('admin_enqueue_scripts', 'enqueue_rd_admin_style');
function enqueue_rd_admin_style($hook) {
  $screen = get_current_screen();

  if ( 'post.php' != $hook ) return;
  wp_enqueue_style( 'rd_admin_style', ASSETS_URL . '/styles/admin.css' );
}

add_action('admin_enqueue_scripts', 'rdsm_settings_page_scripts');
function rdsm_settings_page_scripts($hook) {
  global $rdsm_settings_page;
  if ($hook != $rdsm_settings_page) return;
  wp_enqueue_script('rdsm_authorization_script', ASSETS_URL . '/js/authorization.js');
  wp_enqueue_script('rdsm_general_settings_script', ASSETS_URL . '/js/general_settings.js');
}

add_action('admin_enqueue_scripts', 'rdsm_settings_page_style');
function rdsm_settings_page_style($hook) {
  global $rdsm_settings_page;
  if ($hook != $rdsm_settings_page) return;
  wp_enqueue_style('rdsm_settings_style', ASSETS_URL . '/styles/settings.css');
}
