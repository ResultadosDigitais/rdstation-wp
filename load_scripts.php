<?php

add_action('admin_enqueue_scripts', 'enqueue_rd_admin_style');
function enqueue_rd_admin_style($hook) {
  $screen = get_current_screen();

  if ($screen->base === 'settings_page_rdstation-settings-page') {
    wp_enqueue_script( 'rd_admin_script', plugin_dir_url( __FILE__ ) . 'assets/js/admin.js' );
  }

  if ( 'post.php' != $hook ) return;
  wp_enqueue_style( 'rd_admin_style', plugin_dir_url( __FILE__ ) . 'assets/styles/admin.css' );
}

add_action('admin_enqueue_scripts', 'settings_page_style');
function settings_page_style($hook) {
  global $rdsm_settings_page;
  if ($hook != $rdsm_settings_page) return;
  wp_enqueue_style('rdsm_settings_style', plugin_dir_url( __FILE__ ) . 'assets/styles/settings.css');
}
