<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_integration_form_changed_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMIntegrationFormChanged implements RDSMEventsInterface {
  
  public function register_hooks() {
    add_action('wp_ajax_rdsm-custom-fields', array($this, 'get_custom_fields'), 2);
  }

  public function get_custom_fields() {    
    $access_token = get_option('rdsm_access_token');    
    $refresh_token = get_option('rdsm_refresh_token');
    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMIntegrationFormChangedAPI($user_credentials);
    $contacts_fields = json_decode($api_instance->contacts_fields()["body"], true);

    $form_id = $_POST['form_id'];
    $result = "";

    if (!empty($form_id)) {
      $contact_form = WPCF7_ContactForm::get_instance( $form_id );
      $form_fields = $contact_form->scan_form_tags();
      $select_html = '<select name="selected_form"><option value=""></option>';

      foreach ($contacts_fields["fields"] as $contact_field) {        
        $select_html .= '<option value=".$contact_field["uuid"].">'.$contact_field["name"]["default"].'</option>';
      }

      $select_html .= '</select>';
      
      foreach ($form_fields as $field) {          
        if ($field['type'] != "submit") {
          // if(!empty($form_map[$field['id']])){
          //   $value = $form_map[$field['id']];
          // }
          // else {
          //   $value = '';
          // }
          $result .= '<p class="rd-fields-mapping"><span class="rd-fields-mapping-label">' . $field['name'] . '</span> <span class="dashicons dashicons-arrow-right-alt"></span>'.$select_html.'</p>';
        }
      }
    }

    wp_send_json($result);
  }
}

?>