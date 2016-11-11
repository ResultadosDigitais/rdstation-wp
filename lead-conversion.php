<?php

class LeadConversion {

  public $form_data = array();

  public function __construct($callback, $submit_action){
    add_filter($submit_action, array($this, $callback), 10, 2);
  }

  private function ignore_fields(array $fields, $data){
    foreach ($data as $field => $value) {
      if(in_array($field, $fields)){
        unset($data[$field]);
      }
    }
    return $data;
  }

  private function can_save_lead($data){
    $required_fields = array('email', 'token_rdstation', 'identificador');
    foreach ($required_fields as $field) {
      if(empty($data[$field]) || is_null($data[$field])){
        return false;
      }
    }
    return strlen( $data['token_rdstation'] ) == 32 ? true : false;
  }

  public function conversion( $form_data ) {
    $api_url = "https://app.rdstation.com.br/api/1.3/conversions";

    $form_data["email"] = $this->get_email_field($form_data);

    if ( isset($_COOKIE["__utmz"]) && empty($form_data["c_utmz"]) ) {
      $form_data["c_utmz"] = $_COOKIE["__utmz"];
    }

    if ( isset($_COOKIE["__trf_src"]) && empty($form_data["traffic_source"]) ) {
      $form_data["traffic_source"] = $_COOKIE["__trf_src"];
    }

    if (empty($form_data["client_id"]) && !empty($_COOKIE["rdtrk"])) {
      preg_match("/(\w{8}-\w{4}-4\w{3}-\w{4}-\w{12})/",$_COOKIE["rdtrk"],$Matches);
      $form_data["client_id"] = $Matches[0];
    }

    $form_data = $this->ignore_fields(
      array(
        'password',
        'password_confirmation',
        'senha',
        'confirme_senha',
        'captcha',
        '_wpcf7',
        '_wpcf7_version',
        '_wpcf7_unit_tag',
        '_wpnonce',
        '_wpcf7_is_ajax_call',
        '_wpcf7_locale',
        'your-email',
        'e-mail',
        'mail',
      ), $form_data
    );

    if($this->can_save_lead($form_data)){
      $args = array(
        'timeout' => 10,
        'headers' => array('Content-Type' => 'application/json'),
        'body' => json_encode($form_data)
      );

      $response = wp_remote_post( $api_url, $args );

      if (is_wp_error($response)){
        wp_die('Erro ao enviar o formulário');
        unset($form_data);
      }
    }
    else {
      wp_die('Erro ao enviar o formulário. Certifique-se de ter preenchido os campos corretamente');
    }
  }

  private function get_forms($post_type){
    $args = array( 'post_type' => $post_type, 'posts_per_page' => 100 );
    return $forms = get_posts($args);
  }

  public function contact_form_7($cf7){
    $forms = $this->get_forms('rdcf7_integrations');
    foreach ($forms as $form) {
      $form_id = get_post_meta($form->ID, 'form_id', true);
      if ( $form_id == $cf7->id() ) {
        $submission = WPCF7_Submission::get_instance();
        if ( $submission ) $this->form_data = $submission->get_posted_data();
        $this->generate_static_fields($form->ID, 'Plugin Contact Form 7');
        $this->conversion($this->form_data);
      }
    }
  }

  public function gravity_forms($entry, $gform){

    foreach ($entry as $item => $value) {
      if (is_numeric($item)) $this->form_data[$value] = $item;
    }

    $forms = $this->get_forms('rdgf_integrations');

    foreach ($forms as $form) {
      $fields = get_post_meta($form->ID, 'gf_mapped_fields', true);
      $form_id = get_post_meta($form->ID, 'form_id', true);

      if ( $form_id == $gform['id'] ) {
        foreach ($this->form_data as $key => $value) {
          if($fields[$value] != null && !empty($fields[$value])) {
            $this->form_data[$key] = $fields[$value];
          }
          else {
            unset($this->form_data[$key]);
          }
        }
        $this->form_data = array_flip($this->form_data);
        $this->generate_static_fields($form->ID, 'Plugin Gravity Forms');
        $this->conversion($this->form_data);
      }
    }
  }

  public function generate_static_fields($form_id, $origin_form){
    $this->form_data[ 'token_rdstation' ] = get_post_meta($form_id, 'token_rdstation', true);
    $this->form_data[ 'identificador' ] = get_post_meta($form_id, 'form_identifier', true);
    $this->form_data[ 'form_origem' ] = $origin_form;
    $this->form_data[ 'identificador' ] = get_post_meta($form_id, 'form_identifier', true);
    $this->form_data[ '_is' ] = 8; // Internal source
  }

  private function get_email_field($form_data) {
    $common_email_names = array(
      'email',
      'your-email',
      'e-mail',
      'mail',
    );

    $match_keys = array_intersect_key(array_flip($common_email_names), $form_data);
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
