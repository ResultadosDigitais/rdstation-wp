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
    'erede_api_parcela'
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
    $plugin_data = get_plugin_data(RDSM_PLUGIN_FILE);

    $default_payload = array(
      '_is'             => self::INTERNAL_SOURCE,
      'email'           => $this->get_email_field($form_data),
      'c_utmz'          => $this->set_utmz($form_data),
      'traffic_source'  => $this->set_traffic_source($form_data),
      'client_id'       => $this->set_client_id($form_data),
      'plugin_version'  => $plugin_data['Version']
    );

    $payload = array_merge($form_data, $default_payload);
    $this->payload = $this->filter_fields($this->ignored_fields, $payload);
  }

  private function filter_fields(array $ignored_fields, $form_fields){
    foreach ($form_fields as $field => $value) {
      if (in_array($field, $ignored_fields)) {
        unset($form_fields[$field]);
      }

      if ($this->is_credit_card_number($value)) {
        unset($form_fields[$field]);
      }
    }

    return $form_fields;
  }

  private function is_credit_card_number($number)  {
    if (!is_string($number)) {
      return false;
    }

    $number = preg_replace('/\D/', '', $number);

    $cardtypes = array(
      'amex'       => '/^3[47]\d{13}$/',
      'aura'       => '/^(5078\d{2})(\d{2})(\d{11})$/',
      'banese'     => '/^636117/',
      'cabal'      => '/(60420[1-9]|6042[1-9][0-9]|6043[0-9]{2}|604400)/',
      'diners'     => '/^3(0[0-5]|[68]\d)\d{11}$/',
      'discover'   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
      'elo'        => '/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
      'fortbrasil' => '/^628167/',
      'grandcard'  => '/^605032/',
      'hipercard'  => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
      'jcb'        => '/^(?:2131|1800|35\d{3})\d{11}$/',
      'mastercard' => '/^(5[1-5]\d{4}|677189)\d{10}$/',
      'maestro'    => '/^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/',
      'personal'   => '/^636085/',
      'sorocred'   => '/^627892|^636414/',
      'visa'       => '/^4\d{12}(\d{3})?$/',
      'valecard'   => '/^606444|^606458|^606482/',
    );

    foreach($cardtypes as $cardtype) {
      $credit_card_number = preg_match($cardtype, $number);

      if ($credit_card_number) {
        return true;
      }
    }

    return false;
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
