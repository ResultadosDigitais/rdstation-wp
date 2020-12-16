<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_fields_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMIntegrationFormWooCommerce implements RDSMEventsInterface {
  
  public function register_hooks() {
    add_action('wp_ajax_rdsm-woocommerce-fields', array($this, 'get_fields'), 1);
  }

  public function get_fields() {
    $json_result = array();    
    $select_items = array();

    $fields = array(
      'billing_first_name',
      'billing_last_name',
      'billing_email',
      'billing_phone',
      'billing_company',
      'billing_country',
      'billing_address_1',
      'billing_address_2',
      'billing_city',
      'billing_state',
      'billing_postcode'
    );    

    $access_token = get_option('rdsm_access_token');    
    $refresh_token = get_option('rdsm_refresh_token');
    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMFieldsAPI($user_credentials);
    $contacts_fields = json_decode($api_instance->contacts_fields()["body"], true);

    foreach ($contacts_fields["fields"] as $contact_field) {
      array_push($select_items, array("id" => $contact_field["uuid"], "value" => $contact_field["name"]["default"]));
    }

    $json_result = array( 'select_items' => $select_items, 'fields_woocommerce' => $fields );

    wp_send_json($json_result);
  }
}

?>
