<?php

require_once(RDSM_SRC_DIR . '/helpers/rdsm_card_checker.php');

class RDSMEvent {
  const INTERNAL_SOURCE = 8;

  private $ignored_fields = array(
    'password',
    'password_confirmation',
    'senha',
    'confirme_senha',
    'captcha',
    'G-recaptcha-response',
    '_wpcf7',
    '_wpcf7_version',
    '_wpcf7_unit_tag',
    '_wpnonce',
    '_wpcf7_is_ajax_call',
    '_wpcf7_locale',
    'your-email',
    'e-mail',
    'mail',
    'cielo_debit_number',
    'cielo_debit_holder_name',
    'cielo_debit_expiry',
    'cielo_debit_cvc',
    'cielo_credit_number',
    'cielo_credit_holder_name',
    'cielo_credit_expiry',
    'cielo_credit_cvc',
    'cielo_credit_installments',
    'cielo_webservice',
    'rede_credit_number',
    'rede_credit_holder_name',
    'rede_credit_expiry',
    'rede_credit_cvc',
    'rede_credit_installments',
    'erede_api',
    'erede_api_cvv',
    'erede_api_validade',
    'erede_api_titular',
    'erede_api_devicefingerprintid',
    'erede_api_bandeira',
    'erede_api_fiscal',
    'erede_api_parcela',
    'musixe_credit_card_cvc',
    'musixe_credit_card_expiry',
    'musixe_credit_card_holder_name'
  );

  public $payload;

  public function build_payload($form_data, $post_id, $integration_type) {    
    $default_payload = array(
      'event_type'      => 'CONVERSION',
      'event_family'    => 'CDP',
      'payload'         => $this->get_payload($form_data, $post_id, $integration_type)
    );
    
    $this->payload = $this->filter_fields($this->ignored_fields, $default_payload);
  }

  private function get_payload($form_data, $post_id, $integration_type) {
    $response = array(
      'client_tracking_id' => $this->set_client_id($form_data),
      'traffic_source' => $this->set_traffic_source($form_data)
    );

    switch ($integration_type) {
      case 'contact_form_7':        
        return $response + $this->contact_form7_payload($form_data, $post_id);
        break;
      case 'gravity_forms':
        return $this->gravity_forms_payload($form_data, $post_id);
        break;
      case 'woo_commerce':
        return $this->woo_commerce_payload($form_data, $post_id);
        break;      
    }
  }

  private function contact_form7_payload($form_data, $post_id) {
    $response = array();
    $conversion_identifier = get_post_meta($post_id, 'form_identifier', true);
    $form_id = get_post_meta($post_id, 'form_id', true);
    $form_map = get_post_meta($post_id, 'cf7_mapped_fields_'.$form_id, true);    
    $contact_form = WPCF7_ContactForm::get_instance( $form_id );
    $form_fields = $contact_form->scan_form_tags();

    $response += array('conversion_identifier' => $conversion_identifier);

    foreach ($form_fields as $field) {
      if ($field['type'] != "submit") {
        if(!empty($form_map[$field['name']])){
          $response += array($form_map[$field['name']] => $form_data[$field['name']]);
        }
      }
    }
    return $response;
  }

  private function gravity_forms_payload($form_data, $post_id) {
    $response = array();
    $conversion_identifier = get_post_meta($post_id, 'form_identifier', true);
    $form_id = get_post_meta($post_id, 'form_id', true);
    $gf_forms = GFAPI::get_forms();    
    $form_map = get_post_meta($post_id, 'gf_mapped_fields_'.$form_id, true);

    $response += array('conversion_identifier' => $conversion_identifier);
    
    foreach ($gf_forms as $form) {
      if ($form['id'] == $form_id) {
        foreach ($form['fields'] as $field) {
          if(!empty($form_map[$field['id']])){
            $response += array($form_map[$field['id']] => $form_data[$field['name']]);
          }
        }
      }
    }
    return $response;
  }

  private function woo_commerce_payload($form_data, $post_id) {
    $response = array();
    $options = get_option( 'rdsm_woocommerce_settings' );
    $field_mapping = $options['field_mapping'];

    $response += array(
      'conversion_identifier' => $options['conversion_identifier'],
      'billing_first_name'    => $field_mapping['billing_first_name'],
      'billing_last_name'     => $field_mapping['billing_last_name'],
      'billing_email'         => $field_mapping['billing_email'],
      'billing_phone'         => $field_mapping['billing_phone'],
      'billing_company'       => $field_mapping['billing_company'],
      'billing_country'       => $field_mapping['billing_country'],
      'billing_address_1'     => $field_mapping['billing_address_1'],
      'billing_address_2'     => $field_mapping['billing_address_2'],
      'billing_city'          => $field_mapping['billing_city'],
      'billing_state'         => $field_mapping['billing_state'],
      'billing_postcode'      => $field_mapping['billing_postcode']
    );

    return $response;
  }

  private function filter_fields(array $ignored_fields, $form_fields){
    foreach ($form_fields as $field => $value) {
      if (in_array($field, $ignored_fields)) {
        unset($form_fields[$field]);
      }
      if (RDSMCardChecker::is_credit_card_number($value)) {
        unset($form_fields[$field]);
      }
    }

    return $form_fields;
  }

  private function set_utmz($form_data) {
    if (isset($form_data["c_utmz"])) return $form_data["c_utmz"];
    if (isset($_COOKIE["__utmz"])) return $_COOKIE["__utmz"];
  }

  private function set_traffic_source($form_data) {
    if (isset($form_data["traffic_source"])) return $form_data["traffic_source"];
    if (isset($_COOKIE["__trf_src"])) return $_COOKIE["__trf_src"];
  }

  private function set_client_id($form_data) {
    if (isset($form_data["client_id"])) return $form_data["client_id"];
    if (isset($_COOKIE["rdtrk"])) {
      $client_id_format = "/(\w{8}-\w{4}-4\w{3}-\w{4}-\w{12})/";
      preg_match($client_id_format, $_COOKIE["rdtrk"], $matches);
      return $matches[0];
    }
  }

  private function get_email_field($form_data) {
    $common_email_names = array(
      'email',
      'your-email',
      'e-mail',
      'mail',
    );

    $match_keys = array_intersect_key(array_flip($common_email_names), $form_data);

    // Checks if a common email field is present, otherwise it will try to match
    // any field with the "mail" substring
    if (count($match_keys) > 0) {
       return $form_data[key($match_keys)];
    } else {
      foreach (array_keys($form_data) as $key) {
        if (preg_match('/mail/', $key)) {
          return $form_data[$key];
        }
      }
    }
  }
}
