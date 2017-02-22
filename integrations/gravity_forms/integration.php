<?php

class RDGravityFormsIntegration extends LeadConversion {
  public function send_lead_conversion($entry, $gform){

    foreach ($entry as $item => $value) {
      if (is_numeric($item)) $this->form_data[$value] = $item;
    }

    $forms = parent::get_forms('rdgf_integrations');

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
        parent::generate_static_fields($form->ID, 'Plugin Gravity Forms');
        parent::conversion($this->form_data);
      }
    }
  }
}
