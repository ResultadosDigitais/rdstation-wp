<?php

require_once(RDSM_SRC_DIR . '/integrations/rdsm_integrations.php');

class RDSMGravityFormsIntegration {
  const FORM_SUBMISSION_TRIGGER = 'gform_after_submission';
  const PLUGIN_DESCRIPTION = 'Plugin Gravity Forms';

  public $form_data;

  public $default_payload = array(
    'form_origem' => self::PLUGIN_DESCRIPTION
  );

  private $submitted_form_id;

  public function __construct($resource, $api_client) {
    $this->resource = $resource;
    $this->api_client = $api_client;
    $this->integrations = new RDSMIntegrations;
  }

  public function setup() {
    add_filter(self::FORM_SUBMISSION_TRIGGER, array($this, 'send_resource_to_rd'), 10, 2);
  }

  public function send_resource_to_rd($submitted_fields, $submitted_form){
    $this->submitted_form_id = $submitted_form['id'];
    $gf_integrations = $this->integrations->get('rdgf_integrations');

    $current_form_integrations = array_filter(
      $gf_integrations,
      array($this, 'integrations_from_current_form')
    );

    $this->build_default_payload($submitted_fields);

    foreach ($current_form_integrations as $integration) {
      $field_mapping = get_post_meta($integration->ID, 'gf_mapped_fields', true);
      $this->apply_field_mapping($field_mapping);

      $this->apply_integration_fields($integration->ID);

      $this->resource->build_payload(array_merge($this->form_data, $this->default_payload));

      $this->api_client->post($this->resource);
    }
  }

  private function apply_integration_fields($integration_id) {
    $this->form_data['identificador'] =  get_post_meta($integration_id, 'form_identifier', true);

    $public_token = get_post_meta($integration_id, 'token_rdstation', true);
    $this->form_data['token_rdstation'] = $public_token ? $public_token : get_option('rdsm_public_token');
  }

  private function build_default_payload($submitted_fields) {
    $input_fields = array_filter($submitted_fields);
    $this->form_data = array_filter($input_fields, 'is_numeric', ARRAY_FILTER_USE_KEY);
  }

  private function field_is_mapped($field_id, $field_mapping) {
    return $field_mapping[$field_id] != null && !empty($field_mapping[$field_id]);
  }

  private function apply_field_mapping($field_mapping) {
    foreach ($this->form_data as $field_id => $field_value) {
      if(!empty($field_mapping[$field_id])) {
        $field_name = $field_mapping[$field_id];
        $this->form_data[$field_name] = $field_value;
        unset($this->form_data[$field_id]);
      }
    }
  }

  private function integrations_from_current_form($integration) {
    $integrated_form_id = get_post_meta($integration->ID, 'form_id', true);
    return $integrated_form_id == $this->submitted_form_id;
  }
}
