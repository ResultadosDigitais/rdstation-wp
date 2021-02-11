<?php

require_once(RDSM_SRC_DIR . '/entities/rdsm_user_credentials.php');
require_once(RDSM_SRC_DIR . '/client/rdsm_fields_api.php');
require_once(RDSM_SRC_DIR . '/events/rdsm_events_interface.php');

class RDSMIntegrationFormWooCommerce implements RDSMEventsInterface {
  
  public function register_hooks() {
    add_action('wp_ajax_rdsm-woocommerce-fields', array($this, 'get_fields'), 1);
  }

  public function get_fields() {   
    $select_items = array();
    $contacts_fields = $this->rdstation_fields();
    $fields = $contacts_fields["fields"];
    array_multisort(array_column($fields, 'name'), SORT_ASC, $fields);
    
    foreach ($fields as $contact_field) {
      array_push($select_items, array("api_identifier" => $contact_field["api_identifier"], "value" => $contact_field["name"]["default"]));
    }

    wp_send_json(array( 'select_items' => $select_items, 'fields_woocommerce' => $this->contact_woocommerce_fields()));
  }

  public function contact_woocommerce_fields() {
    $form_fields = array(
      'nome',
      'sobrenome',
      'email',
      'telefone',
      'empresa',
      'país',
      'endereço',
      'endereço2',
      'cidade',
      'estado',
      'cep',
      'produtos'
    );

    return $form_fields;
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
