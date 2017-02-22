<?php

class RDContactForm7Integration extends LeadConversion {
  public function send_lead_conversion($cf7){
    $forms = parent::get_forms('rdcf7_integrations');
    foreach ($forms as $form) {
      $form_id = get_post_meta($form->ID, 'form_id', true);
      if ( $form_id == $cf7->id() ) {
        $submission = WPCF7_Submission::get_instance();
        if ( $submission ) $this->form_data = $submission->get_posted_data();
        parent::generate_static_fields($form->ID, 'Plugin Contact Form 7');
        parent::conversion($this->form_data);
      }
    }
  }
}
