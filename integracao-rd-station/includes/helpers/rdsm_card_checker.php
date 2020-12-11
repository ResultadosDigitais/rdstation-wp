<?php

class RDSMCardChecker {

  const CARD_SEPARATORS = array(
    '-',
    '_',
    '.',
    ' ',
  );

  const CARD_TYPES = array(
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

  public static function is_credit_card_number($value) {
    if (!is_string($value)) {
      return false;
    }

    $parsed_value = str_replace(self::CARD_SEPARATORS, '', $value);

    foreach(self::CARD_TYPES as $card_type) {
      $credit_card_number = preg_match($card_type, $parsed_value);

      if ($credit_card_number) {
        return true;
      }
    }

    return false;
  }
}
