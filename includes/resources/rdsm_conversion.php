<?php

class RDSMConversion {
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
    'cielo_webservice'
  );

  public $payload;

  public function valid_payload() {
    $required_fields = array('email', 'token_rdstation', 'identificador');
    foreach ($required_fields as $field) {
      if(empty($this->payload[$field]) || is_null($this->payload[$field])){
        return false;
      }
    }
    return strlen($this->payload['token_rdstation']) == 32 ? true : false;
  }

  public function build_payload($form_data) {
    $default_payload = array(
      '_is'             => self::INTERNAL_SOURCE,
      'email'           => $this->get_email_field($form_data),
      'c_utmz'          => $this->set_utmz($form_data),
      'traffic_source'  => $this->set_traffic_source($form_data),
      'client_id'       => $this->set_client_id($form_data)
    );

    $payload = array_merge($form_data, $default_payload);
    $this->payload = $this->filter_fields($this->ignored_fields, $payload);
  }

  private function filter_fields(array $ignored_fields, $form_fields){
    foreach ($form_fields as $field => $value) {
      if(in_array($field, $ignored_fields)){
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
