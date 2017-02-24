<?php

class RDWoocommerceIntegration extends LeadConversion {
  public function send_lead_conversion($order_id) {
    $order = new WC_Order($order_id);
    $this->conversion_data = $this->build_conversion_data($_POST);
    $this->add_product_information($order);
    parent::conversion($this->conversion_data);
  }

  private function build_conversion_data($data) {
    $options = get_option('rd_settings');
    $conversion_data = $this->map_rd_fields($data);
    $conversion_data['identificador'] = $options['rd_woocommerce_conversion_identifier'];
    $conversion_data['token_rdstation'] = $options['rd_public_token'];
    return $conversion_data;
  }


  private function map_rd_fields($data) {
    $field_mapping = array(
      'billing_first_name'  => 'nome',
      'billing_last_name'   => 'sobrenome',
      'billing_email'       => 'email',
      'billing_phone'       => 'telefone',
      'billing_company'     => 'empresa',
      'billing_country'     => 'país',
      'billing_address_1'   => 'endereço',
      'billing_address_2'   => 'endereço2',
      'billing_city'        => 'cidade',
      'billing_state'       => 'estado',
      'billing_postcode'    => 'cep'
    );

    foreach ($field_mapping as $current_key => $new_key) {
      $data[$new_key] = $data[$current_key];
      unset($data[$current_key]);
    }

    return $data;
  }

  private function add_product_information($order) {
    $order_price = 0;
    $products_names = array();
    $products = $order->get_items();

    foreach ($products as $product) {
      array_push($products_names, $product['name']);
      $order_price += $product['line_total'];
    }

    $this->conversion_data['produtos'] = $products_names;
    $this->conversion_data['valor_total'] = $order_price;
  }
}
