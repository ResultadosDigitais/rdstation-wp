<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_fields_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMIntegrationFormChanged implements RDSMEventsInterface {
  
  public function register_hooks() {
    add_action('wp_ajax_rdsm-custom-fields', array($this, 'get_custom_fields'), 2);
  }

  public function get_custom_fields() {    
    $access_token = get_option('rdsm_access_token');    
    $refresh_token = get_option('rdsm_refresh_token');
    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMFieldsAPI($user_credentials);
    $contacts_fields = json_decode($api_instance->contacts_fields()["body"], true);

    $form_id = $_POST['form_id'];
    $json_result = array();
    $fields_cf7 = array();

    if (!empty($form_id)) {
      $contact_form = WPCF7_ContactForm::get_instance( $form_id );
      $form_fields = $contact_form->scan_form_tags();
      $index = 0;
      $select_items = array();
      
      foreach ($contacts_fields["fields"] as $contact_field) {
        array_push($select_items, array("id" => $contact_field["uuid"], "value" => $contact_field["name"]["default"]));
      }

      foreach ($form_fields as $field) {          
        if ($field['type'] != "submit") {          
          array_push($fields_cf7, $field['name']);
        }
      }

      $json_result = array( 'select_items' => $select_items, 'fields_cf7' => $fields_cf7 );
    }

    wp_send_json($json_result);
  }
}

?>
