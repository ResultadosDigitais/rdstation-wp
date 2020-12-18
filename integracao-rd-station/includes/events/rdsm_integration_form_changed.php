<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_fields_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMIntegrationFormChanged implements RDSMEventsInterface {
  
  public function register_hooks() {
    add_action('wp_ajax_rdsm-custom-fields', array($this, 'get_custom_fields'), 4);
  }

  public function get_custom_fields() {
    $form_id = $_POST['form_id'];
    $integrationType = $_POST['type'];
    $select_items = array();
    $contacts_fields = $this->rdstation_fields();

    if (!empty($form_id)) {
      foreach ($contacts_fields["fields"] as $contact_field) {
        array_push($select_items, array("id" => $contact_field["uuid"], "value" => $contact_field["name"]["default"]));
      }
      $json_result = array( 'select_items' => $select_items );

      if ($integrationType == "contact_form_7") {
        $json_result = array( 'select_items' => $select_items, 'fields_contact_form_7' => $this->contact_form7_fields($form_id));
      } elseif ($integrationType == "gravity_forms") {
        $json_result = array( 'select_items' => $select_items, 'fields_gravity_forms' => $this->gravity_forms_fields($form_id));
      }
    }

    wp_send_json($json_result);
  }

  public function contact_form7_fields($form_id) {
    $contact_form = WPCF7_ContactForm::get_instance( $form_id );
    $form_fields = $contact_form->scan_form_tags();
    $fields = array();

    foreach ($form_fields as $field) {          
      if ($field['type'] != "submit") {          
        array_push($fields, $field['name']);
      }
    }
    return $fields;
  }

  public function gravity_forms_fields($form_id) {
    $gf_forms = GFAPI::get_forms();
    $post_id = $_POST['post_id'];
    $form_map = get_post_meta($post_id, 'gf_mapped_fields', true);
    $fields = array();
    
    foreach ($gf_forms as $form) {
      if ($form['id'] == $form_id) {
        foreach ($form['fields'] as $field) {
          if(!empty($form_map[$field['id']])){
            $value = $form_map[$field['id']];
          }else {
            $value = '';
          }
          array_push($fields, array("label" => $field['label'], "id" => $field['id'], "value" => $value));
        }
      }
    }
    return $fields;
  }

  public function rdstation_fields() {
    $access_token = get_option('rdsm_access_token');    
    $refresh_token = get_option('rdsm_refresh_token');
    $user_credentials = new RDSMUserCredentials($access_token, $refresh_token);
    $api_instance = new RDSMFieldsAPI($user_credentials);
    return json_decode($api_instance->contacts_fields()["body"], true);
  }
}

?>
