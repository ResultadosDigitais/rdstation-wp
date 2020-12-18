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
    $post_id = $_POST['post_id'];
    $integrationType = $_POST['type'];
    $select_items = array();
    $contacts_fields = $this->rdstation_fields();
    $fields = $contacts_fields["fields"];

    array_multisort(array_column($fields, 'name'), SORT_ASC, $fields);

    if (!empty($form_id)) {
      foreach ($fields as $contact_field) {
        array_push($select_items, array("api_identifier" => $contact_field["api_identifier"], "value" => $contact_field["name"]["default"]));
      }

      if ($integrationType == "contact_form_7") {
        $json_result = array( 'select_items' => $select_items, 'fields_contact_form_7' => $this->contact_form7_fields($form_id, $post_id));
      } elseif ($integrationType == "gravity_forms") {
        $json_result = array( 'select_items' => $select_items, 'fields_gravity_forms' => $this->gravity_forms_fields($form_id, $post_id));
      }
    }

    wp_send_json($json_result);
  }

  public function contact_form7_fields($form_id, $post_id) {
    $contact_form = WPCF7_ContactForm::get_instance( $form_id );
    $form_fields = $contact_form->scan_form_tags();
    $form_map = get_post_meta($post_id, 'cf7_mapped_fields_'.$form_id, true);
    $fields = array();

    foreach ($form_fields as $field) {
      if ($field['type'] != "submit") {
        if(!empty($form_map[$field['name']])){
          $value = $form_map[$field['name']];
        }else {
          $value = '';
        }
        array_push($fields, array("label" => $field['name'], "id" => $field['name'], "value" => $value));
      }
    }
    return $fields;
  }

  public function gravity_forms_fields($form_id, $post_id) {
    $gf_forms = GFAPI::get_forms();    
    $form_map = get_post_meta($post_id, 'gf_mapped_fields_'.$form_id, true);
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
