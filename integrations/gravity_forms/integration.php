<?php

class RDGravityFormsIntegration extends LeadConversion {

  const POST_TYPE        = 'rdgf_integrations';
  const INTEGRATION_NAME = 'Plugin Gravity Forms';

  public function send_lead_conversion($form_data, $gform) {
    // Remove all empty values from $form_data
    $form_data = array_filter($form_data);
    $this->build_form_data($form_data);

    $integrations = parent::get_forms(self::POST_TYPE);

    foreach ($integrations as $integration) {
      $fields = get_post_meta($integration->ID, 'gf_mapped_fields', true);
      $form_id = get_post_meta($integration->ID, 'form_id', true);

      if ($form_id != $gform['id']) continue;

      $this->field_mapping($fields);

      parent::generate_static_fields($integration->ID, self::INTEGRATION_NAME);
      parent::conversion($this->form_data);
    }
  }

  private function build_form_data($form_data) {
    $form_data = array_flip($form_data);

    // Remove all fields that does not have an valid id
    $fields = array_filter($form_data, 'is_numeric');

    foreach ($fields as $value => $id) {
      $this->form_data[$id] = $value;
    }
  }

  private function field_mapping($fields) {
    foreach ($this->form_data as $field_id => $field_value) {
      $field_name = $fields[$field_id];

      if(!empty($field_name)) {
        $this->form_data[$field_name] = $field_value;
      }

      unset($this->form_data[$field_id]);
    }
  }
}
