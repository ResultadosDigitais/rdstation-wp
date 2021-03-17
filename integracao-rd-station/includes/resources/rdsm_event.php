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
        return $response + $this->gravity_forms_payload($form_data, $post_id);
        break;
      case 'woo_commerce':
        return $response + $this->woo_commerce_payload($form_data, $post_id);
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
    $identifier = 'name';

    $response += array('conversion_identifier' => $conversion_identifier);

    if (empty($form_map)) {      
      $response += array('email' => $this->get_email_field($form_data));
    }else {
      foreach ($form_fields as $field) {
        if ($field['type'] != "submit") {
          if ((strpos($field['type'], "select") !== false) && (!in_array("multiple", $field['options']))) {
            $response = $this->get_value($response, $form_map, $form_data, $field, $identifier, true, true, false, false);
          }else {
            $response = $this->get_value($response, $form_map, $form_data, $field, $identifier, true, false, false, false);
          }
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
    
    if (empty($form_map)) {      
      $form_map = get_post_meta($post_id, 'gf_mapped_fields', true);      
    }

    $response += array('conversion_identifier' => $conversion_identifier);

    foreach ($gf_forms as $form) {
      if ($form['id'] == $form_id) {
        foreach ($form['fields'] as $field) {
          if ($field['type'] == "checkbox") {
            $response = $this->get_value($response, $form_map, $form_data, $field, 'id', true, false, true, true);
          }else if ($field['type'] == "multiselect") {
            $response = $this->get_value($response, $form_map, $form_data, $field, 'id', false, false, true, true);
          }else {
            $response = $this->get_value($response, $form_map, $form_data, $field, 'id', false, false, false, true);
          }
        }
      }
    }
    return $response;
  }

  private function get_value($response, $form_map, $form_data, $field, $identifier, $is_checkbox, $parse_to_string, $parse_to_array, $is_gravity_forms) {
    $name = $form_map[$field[$identifier]];
    if(!empty($name)){
      $value = $form_data[$field[$identifier]];

      if ($name == "communications" && $is_checkbox) {
        $response = $this->legal_bases($value, $response, $field, $form_data, $identifier);
      }else {
        if ($parse_to_string) {
          $value = $value[0];
          if (empty($value)) {
            return $response;
          }
        }

        if ($parse_to_array) {
          $value = $this->parse_to_array($value, $is_checkbox, $is_gravity_forms, $field, $form_data, $identifier);
        }

        if ($is_gravity_forms && $field['type'] == "name") {
          $value = $this->concat_names_field($field, $form_data, $identifier);
        }

        $response += array($name => $value);
      }
    }
    return $response;
  }

  private function concat_names_field($field, $form_data, $identifier) {
    $concat_value = "";
    foreach ($field['inputs'] as $input) {
      $name = $form_data[$input[$identifier]];
      if (!empty($name)) {
        $concat_value .= $name . ' ';
      }
    }
    return $concat_value;
  }

  private function legal_bases($value, $response, $field, $form_data, $identifier) {
    if (!empty($value)) {
      $response += array('legal_bases' => array(array('category' => 'communications', 'type' => 'consent', 'status' => 'granted')));
    }else if ($is_gravity_forms){
      foreach ($field['inputs'] as $input) {
        $value = $form_data[$input[$identifier]];
        if (!empty($value)) {
          $response += array('legal_bases' => array(array('category' => 'communications', 'type' => 'consent', 'status' => 'granted')));
          continue;
        }
      }
    }
    return $response;
  }

  private function parse_to_array($value, $is_checkbox, $is_gravity_forms, $field, $form_data, $identifier) {
    $value = str_replace("\"", "", $value);
    $value = str_replace("[", "", $value);
    $value = str_replace("]", "", $value);
    $value = explode(",", $value);

    if ($is_checkbox && $is_gravity_forms) {
      $values = array();
      foreach ($field['inputs'] as $input) {
        $item_value = $form_data[$input[$identifier]];
        if (!empty($item_value)) {
          array_push($values, $item_value);
        }
      }
      return $values;
    }
    return $value;
  }

  private function woo_commerce_payload($form_data, $post_id) {
    $response = array();
    $options = get_option( 'rdsm_woocommerce_settings' );
    $field_mapping = $options['field_mapping'];
    
    if (empty($field_mapping)) {
      $response += $this->map_rd_default_fields($options, $form_data);      
    }else {
      $response += $this->map_rd_custom_fields($field_mapping, $options, $form_data);
    }

    return $response;
  }

  private function map_rd_custom_fields($field_mapping, $options, $form_data) {
    $response = array(
      'conversion_identifier'       => $options['conversion_identifier'],
      $field_mapping['nome']        => $form_data['nome'],
      $field_mapping['sobrenome']   => $form_data['sobrenome'],
      $field_mapping['email']       => $form_data['email'],
      $field_mapping['telefone']    => $form_data['telefone'],
      $field_mapping['empresa']     => $form_data['empresa'],
      $field_mapping['país']        => $form_data['país'],
      $field_mapping['endereço']    => $form_data['endereço'],
      $field_mapping['endereço2']   => $form_data['endereço2'],
      $field_mapping['cidade']      => $form_data['cidade'],
      $field_mapping['estado']      => $form_data['estado'],
      $field_mapping['cep']         => $form_data['cep'],
      $field_mapping['produtos']    => $form_data['produtos']
    );

    return $response;
  }

  private function map_rd_default_fields($options, $form_data) {
    $response = array(
      'conversion_identifier' => $options['conversion_identifier'],
      'name'                  => $form_data['nome']." ".$form_data['sobrenome'],
      'email'                 => $form_data['email'],
      'mobile_phone'          => $form_data['telefone'],
      'company_name'          => $form_data['empresa'],
      'country'               => $form_data['país'],
      'city'                  => $form_data['cidade'],
      'state'                 => $form_data['estado']
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
